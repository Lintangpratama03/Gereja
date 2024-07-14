<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'harga',
        'rutin',
        'slug'
    ];

    protected $table = 'category';
    public function transportasis()
    {
        return $this->hasMany(Transportasi::class);
    }
}
