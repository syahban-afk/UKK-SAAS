<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\pakets_model;
use Illuminate\Support\Arr;

class PaketsSeeder extends Seeder
{
    public function run(): void
    {
        $pakets = [
            ['Paket Pernikahan', 'Prasmanan', 'Pernikahan', 300, 45000000],
            ['Paket Ulang Tahun', 'Box', 'Ulang Tahun', 50, 7500000],
            ['Paket Rapat', 'Box', 'Rapat', 30, 4500000],
            ['Paket Studi Tour', 'Prasmanan', 'Studi Tour', 120, 18000000],
            ['Paket Selamatan', 'Prasmanan', 'Selamatan', 80, 12000000],
            ['Paket Gathering', 'Prasmanan', 'Rapat', 200, 28000000],
            ['Paket VIP Pernikahan', 'Prasmanan', 'Pernikahan', 500, 90000000],
            ['Paket Coffee Break', 'Box', 'Rapat', 40, 6000000],
            ['Paket Family', 'Box', 'Selamatan', 25, 3500000],
            ['Paket Tour Hemat', 'Prasmanan', 'Studi Tour', 80, 11000000],
        ];
        foreach ($pakets as [$nama, $jenis, $kategori, $pax, $harga]) {
            pakets_model::firstOrCreate(
                ['nama_paket' => $nama],
                [
                    'jenis' => $jenis,
                    'kategori' => $kategori,
                    'jumlah_pax' => $pax,
                    'harga_paket' => $harga,
                    'deskripsi' => 'Paket unggulan',
                    'foto1' => 'https://img.daisyui.com/images/stock/food.webp',
                    'foto2' => 'https://img.daisyui.com/images/stock/food.webp',
                    'foto3' => 'https://img.daisyui.com/images/stock/food.webp',
                ]
            );
        }
    }
}
