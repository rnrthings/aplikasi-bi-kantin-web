<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';

    protected $primaryKey = 'id_menu';

    public $timestamps = false;

    protected $fillable = [
        'id_kantin',
        'nama_menu',
        'deskripsi',
        'gambar',
        'harga',
        'kategori',
        'stok',
    ];
}
