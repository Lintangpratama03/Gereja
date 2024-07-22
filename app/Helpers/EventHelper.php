<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class EventHelper
{
    public static function getEvents()
    {
        // Get current date and time
        $now = Carbon::now();

        // Get next Sunday's date
        $nextSunday = $now->copy()->next(Carbon::SUNDAY)->format('Y-m-d');
        // dd($nextSunday);
        // Fetch events linked to routes in the 'pemesanan' table
        $eventsFromPemesanan = DB::table('pemesanan')
            ->join('rute', 'pemesanan.rute_id', '=', 'rute.id')
            ->join('transportasi', 'rute.transportasi_id', '=', 'transportasi.id')
            ->select('rute.*', 'pemesanan.nama_acara as acara', 'rute.tanggal')
            ->where('transportasi.rutin', 2)
            ->where('pemesanan.status', '!=', 'Belum Bayar')
            ->get();

        // Fetch routine events that occur every Sunday
        $routineEvents = DB::table('rute')
            ->join('transportasi', 'rute.transportasi_id', '=', 'transportasi.id')
            ->join('category', 'transportasi.category_id', '=', 'category.id')
            ->where('transportasi.rutin', 1)
            ->select('category.name as acara', 'rute.*', DB::raw("'{$nextSunday}' as tanggal"))
            ->get();

        // dd($routineEvents);
        // Combine the results
        $events = $eventsFromPemesanan->merge($routineEvents);

        return $events;
    }
}
