<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_pemesanans_model extends Model
{
    protected $table = 'detail_pemesanans';

    protected $fillable = [
        'id_pemesanan',
        'id_paket',
        'subtotal',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(pemesanans_model::class, 'id_pemesanan');
    }

    public function paket()
    {
        return $this->belongsTo(pakets_model::class, 'id_paket');
    }
}
