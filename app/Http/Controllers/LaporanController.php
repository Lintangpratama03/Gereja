<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Get the date from the request, default to today's date
        $date = $request->input('date', Carbon::today()->toDateString());

        $query = DB::table('pemesanan')
            ->leftJoin('rute', 'pemesanan.rute_id', '=', 'rute.id')
            ->leftJoin('users as a', 'pemesanan.penumpang_id', '=', 'a.id')
            ->leftJoin('users as b', 'rute.pendeta', '=', 'b.id')
            ->select(
                'pemesanan.*',
                'rute.tujuan',
                'rute.start',
                'rute.end',
                'rute.tanggal',
                'b.name as pendeta_name',
                'a.*'
            )
            ->whereDate('pemesanan.created_at', '=', $date)
            ->orderBy('pemesanan.created_at', 'desc');

        // Add filtering based on payment status
        if ($request->has('payment_status')) {
            if ($request->payment_status === 'paid') {
                $query->whereNotNull('pemesanan.bukti');
            } elseif ($request->payment_status === 'unpaid') {
                $query->whereNull('pemesanan.bukti');
            }
        }

        $pemesanan = $query->get();
        // dd($pemesanan);
        return view('server.laporan.index', compact('pemesanan', 'date'));
    }



    public function petugas()
    {
        return view('client.petugas');
    }

    public function kode(Request $request)
    {
        return redirect()->route('transaksi.show', $request->kode);
    }

    public function show($id)
    {
        $data = Pemesanan::with('rute.transportasi.category', 'penumpang')->where('kode', $id)->first();
        // dd($data);
        if ($data) {
            return view('server.laporan.show', compact('data'));
        } else {
            return redirect()->back()->with('error', 'Kode Transaksi Tidak Ditemukan!');
        }
    }

    public function pembayaran($id)
    {
        Pemesanan::find($id)->update([
            'status' => 'Sudah Bayar',
            'petugas_id' => Auth::user()->id
        ]);

        return redirect()->back()->with('success', 'Pembayaran Ticket Success!');
    }

    public function history()
    {
        $pemesanan = Pemesanan::with('rute.transportasi')->where('penumpang_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return view('client.history', compact('pemesanan'));
    }
}
