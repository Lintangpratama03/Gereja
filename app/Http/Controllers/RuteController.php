<?php

namespace App\Http\Controllers;

use App\Models\Rute;
use App\Models\Transportasi;
use App\Models\User;
use Illuminate\Http\Request;

class RuteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $dd = Session
        $transportasi = Transportasi::with('category')
            ->where('rutin', 1)
            ->orderBy('kode')
            ->orderBy('name')
            ->get();
        $pendeta = User::where('level', 'Pendeta')->get();
        // dd($transportasi);
        $rute = Rute::with(['transportasi.category', 'user'])
            ->whereHas('transportasi.category', function ($query) {
                $query->where('rutin', 1);
            })
            ->orderBy('created_at', 'desc')
            ->get();
        // dd($rute);
        return view('server.rute.index', compact('rute', 'transportasi', 'pendeta'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
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
        $this->validate($request, [
            'tujuan' => 'required',
            'start' => 'required',
            'end' => 'required',
            'harga' => 'required',
            'pendeta' => 'required',
            'transportasi_id' => 'required'
        ]);

        Rute::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'tujuan' => $request->tujuan,
                'start' => $request->start,
                'pendeta' => $request->pendeta,
                'end' => $request->end,
                'harga' => $request->harga,
                'is_aktif' => 1,
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
        $pendeta = User::where('level', 'Pendeta')->get();
        return view('server.rute.edit', compact('rute', 'transportasi', 'pendeta'));
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
