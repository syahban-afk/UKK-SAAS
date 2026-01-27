<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class pelanggans_model extends Model
{
    protected $table = 'pelanggans';
    protected $fillable = [
        'nama_pelanggan',
        'email',
        'password',
        'tgl_lahir',
        'telepon',
        'alamat1',
        'alamat2',
        'alamat3',
        'kartu_id',
        'foto',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function pemesanans()
    {
        return $this->hasMany(pemesanans_model::class, 'id_pelanggan');
    }
}
