<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';

    protected $primaryKey = 'id_detail';

    public $timestamps = false;

    protected $fillable = [
        'id_menu',
        'jumlah',
        'harga_saat_transaksi',
        'subtotal',
    ];
}
