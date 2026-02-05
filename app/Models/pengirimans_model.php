<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pengirimans_model extends Model
{
    protected $table = 'pengirimans';

    protected $fillable = [
        'tgl_kirim',
        'tgl_tiba',
        'status_kirim',
        'bukti_foto',
        'id_pesan',
        'id_user',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(pemesanans_model::class, 'id_pesan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
