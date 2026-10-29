<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    protected $table = 'pesanan';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'menu_id',
        'jumlah',
        'total_harga',
        'status',
    ];
}