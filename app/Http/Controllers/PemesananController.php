<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Category;
use App\Models\Pemesanan;
use App\Models\Transportasi;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ruteAwal = Rute::orderBy('start')->get()->groupBy('start');
        if (count($ruteAwal) > 0) {
            foreach ($ruteAwal as $key => $value) {
                $data['start'][] = $key;
            }
        } else {
            $data['start'] = [];
        }
        $ruteAkhir = Rute::orderBy('end')->get()->groupBy('end');
        if (count($ruteAkhir) > 0) {
            foreach ($ruteAkhir as $key => $value) {
                $data['end'][] = $key;
            }
        } else {
            $data['end'] = [];
        }
        $category = Category::orderBy('name')->where('rutin', 2)->get();
        $pendeta = User::where('level', 'Pendeta')->get();
        return view('client.index', compact('data', 'category', 'pendeta'));
    }
    public function checkSchedule(Request $request)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $tanggal = $request->input('waktu');

        $ruteExists = Rute::where('tanggal', $tanggal)
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start', [$start, $end])
                    ->orWhereBetween('end', [$start, $end])
                    ->orWhere(function ($query) use ($start, $end) {
                        $query->where('start', '<=', $start)
                            ->where('end', '>=', $end);
                    });
            })->where('status', 1)->exists();

        if ($ruteExists) {
            return redirect()->back()->with('error', 'Jadwal sudah ada!');
        }

        return response()->json(['exists' => false]);
    }

    public function getPrice(Request $request)
    {
        // dd($request->all());
        $categoryId = $request->input('category_id');
        $category = Category::find($categoryId);

        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }

        $price = $category->harga;
        return response()->json(['price' => $price]);
    }

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $this->validate($request, [
            'start' => 'required',
            'end' => 'required',
            'harga' => 'required',
            'pendeta' => 'required',
            'name' => 'required',
            'jumlah' => 'required',
            'category' => 'required',
            'harga' => 'required'
        ]);
        $scheduleExists = Rute::where('tanggal', $request->waktu)
            ->where(function ($query) use ($request) {
                $query->whereBetween('start', [$request->start, $request->end])
                    ->orWhereBetween('end', [$request->start, $request->end])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('start', '<=', $request->start)
                            ->where('end', '>=', $request->end);
                    });
            })
            ->where('status', 1)
            ->exists();

        if ($scheduleExists) {
            return redirect()->back()->with('error', 'Jadwal sudah ada!');
        }
        // Create or update transportasi
        $transportasi = Transportasi::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'name' => $request->name,
                'kode' => 'ACARA',
                'jumlah' => $request->jumlah,
                'rutin' => 2,
                'category_id' => $request->category, // corrected to category_id
            ]
        );

        // Capture the transportasi_id for Rute creation
        $transportasi_id = $transportasi->id;
        $tanggal = Carbon::parse($request->waktu);
        $hari = $this->translateDayName($tanggal->isoFormat('dddd'));
        $jemaat =  Auth::user()->id;
        // Create or update rute
        $rute = Rute::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'tujuan' => $hari,
                'start' => $request->start,
                'nama_acara' => $request->nama_acara,
                'jemaat' => $jemaat,
                'tanggal' => $tanggal,
                'pendeta' => $request->pendeta,
                'end' => $request->end,
                'harga' => $request->harga,
                'transportasi_id' => $transportasi_id,
            ]
        );
        return redirect()->route('pemesanan.list')->with('success', 'Pengajuan Acara Success!');
    }

    public function list()
    {
        // Mengambil id pengguna saat ini
        $userId = Auth::user()->id;

        // Mengambil rute dengan informasi transportasi dan menentukan status setiap rute
        $rute = Rute::with('transportasi')
            ->where('jemaat', $userId)
            ->select('rute.*')
            ->selectRaw('CASE WHEN EXISTS (SELECT 1 FROM pemesanan WHERE pemesanan.rute_id = rute.id) THEN 1 ELSE 0 END AS status_pesan')
            ->get();
        // dd($rute);
        return view('client.pemesanan-list', compact('rute'));
    }



    public function pesan($id)
    {
        $rute = Rute::find($id);
        if (!$rute) {
            return redirect()->route('pemesanan.list')->with('error', 'Rute tidak ditemukan');
        }

        // Check if the user has already booked this route
        $existingOrder = Pemesanan::where('rute_id', $rute->id)
            ->where('penumpang_id', Auth::user()->id)
            ->first();

        if ($existingOrder) {
            return redirect()->route('pemesanan.list')->with('error', 'Anda sudah memesan acara ini!');
        }

        $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
        $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));
        $waktu = $rute->tanggal . " " . $rute->start;

        Pemesanan::create([
            'kode' => $kodePemesanan,
            'kursi' => $rute->transportasi->jumlah,
            'waktu' => $waktu,
            'total' => $rute->harga,
            'rute_id' => $rute->id,
            'nama_acara' => $rute->nama_acara,
            'penumpang_id' => Auth::user()->id
        ]);

        return redirect()->route('pemesanan.list')->with('success', 'Pemesanan berhasil ditambahkan!');
    }
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $pemesanan = Rute::find($id);
        // dd($pemesanan);
        if ($request->hasFile('bukti')) {
            $image = $request->file('bukti');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('/img/bukti');
            $image->move($destinationPath, $name);

            $pemesanan->bukti = $name;
            $pemesanan->save();
        }

        return redirect()->back()->with('success', 'Bukti transfer berhasil diupload.');
    }


    private function translateDayName($dayName)
    {
        $translations = [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];

        return isset($translations[$dayName]) ? $translations[$dayName] : $dayName;
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $data)
    {
        $data = Crypt::decrypt($data);
        $category = Category::find($data['category']);
        $rute = Rute::with('transportasi')->where('status', 1)->where('start', $data['start'])->where('end', $data['end'])->get();
        // dd($rute);
        if ($rute->count() > 0) {
            foreach ($rute as $val) {
                $pemesanan = Pemesanan::where('rute_id', $val->id)->where('waktu')->count();
                if ($val->transportasi) {
                    $kursi = Transportasi::find($val->transportasi_id)->jumlah - $pemesanan;
                    if ($val->transportasi->category_id == $category->id) {
                        $dataRute[] = [
                            'harga' => $val->harga,
                            'start' => $val->start,
                            'end' => $val->end,
                            'tujuan' => $val->tujuan,
                            'transportasi' => $val->transportasi->name,
                            'kode' => $val->transportasi->kode,
                            'kursi' => $kursi,
                            'waktu' => $data['waktu'],
                            'id' => $val->id,
                        ];
                    }
                }
            }
            sort($dataRute);
        } else {
            $dataRute = [];
        }
        $id = $category->name;
        return view('client.show', compact('id', 'dataRute'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Crypt::decrypt($id);
        $rute = Rute::find($data['id']);
        $transportasi = Transportasi::find($rute->transportasi_id);
        return view('client.kursi', compact('data', 'transportasi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    // public function pesan($kursi, $data)
    // {
    //     $d = Crypt::decrypt($data);
    //     $huruf = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
    //     $kodePemesanan = strtoupper(substr(str_shuffle($huruf), 0, 7));

    //     $rute = Rute::with('transportasi.category')->find($d['id']);

    //     $waktu = $d['waktu'] . " " . $rute->jam;

    //     Pemesanan::Create([
    //         'kode' => $kodePemesanan,
    //         'kursi' => $kursi,
    //         'waktu' => $waktu,
    //         'total' => $rute->harga,
    //         'rute_id' => $rute->id,
    //         'penumpang_id' => Auth::user()->id
    //     ]);

    //     return redirect('/')->with('success', 'Pemesanan Tiket ' . $rute->transportasi->category->name . ' Success!');
    // }
}
