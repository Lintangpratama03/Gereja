<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Validasi;
use App\Models\Transportasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidasiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id_user = Auth::user()->id;
        // dd($id_user);
        $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();
        $rute = Rute::with('transportasi.category')->where('is_aktif', 1)->where('pendeta', $id_user)->orderBy('created_at', 'desc')->get();
        // dd($rute);
        return view('server.validasi.index', compact('rute', 'transportasi'));
    }

    public function updateStatus($id)
    {
        $rute = Rute::find($id);
        $rute->status = 1;
        $rute->save();

        return redirect()->back()->with('success', 'Status updated successfully!');
    }
    public function softDelete($id)
    {
        $rute = Rute::find($id);
        $rute->status = 2;
        $rute->save();

        return redirect()->back()->with('success', 'Status updated to 2 successfully!');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tujuan' => 'required',
            'start' => 'required',
            'end' => 'required',
            'harga' => 'required',
            'jam' => 'required',
            'transportasi_id' => 'required'
        ]);

        Rute::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'tujuan' => $request->tujuan,
                'start' => $request->start,
                'end' => $request->end,
                'harga' => $request->harga,
                'jam' => $request->jam,
                'transportasi_id' => $request->transportasi_id,
            ]
        );

        if ($request->id) {
            return redirect()->route('rute.index')->with('success', 'Success Update Rute!');
        } else {
            return redirect()->back()->with('success', 'Success Add Rute!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $rute = Rute::find($id);
        $transportasi = Transportasi::orderBy('kode')->orderBy('name')->get();
        return view('server.rute.edit', compact('rute', 'transportasi'));
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
        Rute::find($id)->delete();
        return redirect()->back()->with('success', 'Success Delete Rute!');
    }
}
