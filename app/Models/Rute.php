<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;

    protected $fillable = [
        'tujuan',
        'start',
        'end',
        'harga',
        'pendeta',
        'status',
        'nama_acara',
        'jemaat',
        'tanggal',
        'bukti',
        'transportasi_id'
    ];

    public function transportasi()
    {
        return $this->belongsTo('App\Models\Transportasi', 'transportasi_id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'pendeta');
    }

    protected $table = 'rute';
}
