<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pakets_model extends Model
{
    protected $table = 'pakets';
    protected $fillable = [
        'nama_paket',
        'jenis',
        'kategori',
        'jumlah_pax',
        'harga_paket',
        'deskripsi',
        'foto1',
        'foto2',
        'foto3',
    ];

    public function detailPemesanans()
    {
        return $this->hasMany(detail_pemesanans_model::class, 'id_paket');
    }
}
