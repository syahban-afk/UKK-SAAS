<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class jenis_pembayarans_model extends Model
{
    protected $table = 'jenis_pembayarans';

    protected $fillable = [
        'metode_pembayaran',
    ];

    public function detailJenisPembayarans()
    {
        return $this->hasMany(detail_jenis_pembayarans_model::class, 'id_jenis_pembayaran');
    }

    public function pemesanans()
    {
        return $this->hasMany(pemesanans_model::class, 'id_jenis_bayar');
    }
}
