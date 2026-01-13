<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class detail_jenis_pembayarans_model extends Model
{
    protected $table = 'detail_jenis_pembayarans';
    protected $fillable = [
        'id_jenis_pembayaran',
        'no_rek',
        'tempat_bayar',
        'logo',
    ];

    public function jenisPembayaran()
    {
        return $this->belongsTo(jenis_pembayarans_model::class, 'id_jenis_pembayaran');
    }
}
