<?php

namespace Database\Seeders;

use App\Models\jenis_pembayarans_model;
use Illuminate\Database\Seeder;

class JenisPembayaransSeeder extends Seeder
{
    public function run(): void
    {
        $metodes = ['Transfer Bank', 'E-Wallet', 'Tunai', 'Kartu Kredit'];
        foreach ($metodes as $m) {
            jenis_pembayarans_model::firstOrCreate(['metode_pembayaran' => $m]);
        }
    }
}
