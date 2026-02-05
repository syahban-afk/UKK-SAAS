<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pemesanans_model extends Model
{
    protected $table = 'pemesanans';

    protected $fillable = [
        'id_pelanggan',
        'id_jenis_bayar',
        'no_resi',
        'tgl_pesan',
        'status_pesan',
        'total_bayar',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(pelanggans_model::class, 'id_pelanggan');
    }

    public function jenisPembayaran()
    {
        return $this->belongsTo(jenis_pembayarans_model::class, 'id_jenis_bayar');
    }

    public function detailPemesanans()
    {
        return $this->hasMany(detail_pemesanans_model::class, 'id_pemesanan');
    }

    public function pengiriman()
    {
        return $this->hasOne(pengirimans_model::class, 'id_pesan');
    }
}
