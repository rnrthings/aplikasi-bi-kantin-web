<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $primaryKey = 'id_transaksi';

    public $timestamps = false;

    protected $fillable = [
        'nis',
        'id_kantin',
        'tanggal',
        'metode_bayar',
        'status_bayar',
        'status',
    ];

    public function details(): HasMany
    {
        return $this->hasMany(DetailTransaksi::class, 'id_transaksi', 'id_transaksi');
    }
}
