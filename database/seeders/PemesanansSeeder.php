<?php

namespace Database\Seeders;

use App\Models\detail_pemesanans_model;
use App\Models\jenis_pembayarans_model;
use App\Models\pakets_model;
use App\Models\pelanggans_model;
use App\Models\pemesanans_model;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PemesanansSeeder extends Seeder
{
    public function run(): void
    {
        $pelangganIds = pelanggans_model::pluck('id')->all();
        $jenisIds = jenis_pembayarans_model::pluck('id')->all();
        $pakets = pakets_model::select(['id', 'harga_paket'])->get();
        if (! $pelangganIds || ! $jenisIds || ! $pakets->count()) {
            return;
        }
        $now = Carbon::now();
        $orders = 360;
        for ($i = 0; $i < $orders; $i++) {
            $tglPesan = $now->copy()->subDays(random_int(0, 120))->setTime(random_int(9, 19), random_int(0, 59));
            $status = collect(['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir'])->random();
            $pemesanan = pemesanans_model::create([
                'id_pelanggan' => $pelangganIds[array_rand($pelangganIds)],
                'id_jenis_bayar' => $jenisIds[array_rand($jenisIds)],
                'no_resi' => 'RES'.Str::upper(Str::random(10)),
                'tgl_pesan' => $tglPesan,
                'status_pesan' => $status,
                'total_bayar' => 0,
            ]);
            $lines = random_int(1, 3);
            $total = 0;
            for ($j = 0; $j < $lines; $j++) {
                $p = $pakets->random();
                $qty = random_int(1, 5);
                $subtotal = $p->harga_paket * $qty;
                detail_pemesanans_model::create([
                    'id_pemesanan' => $pemesanan->id,
                    'id_paket' => $p->id,
                    'subtotal' => $subtotal,
                ]);
                $total += $subtotal;
            }
            $pemesanan->update(['total_bayar' => $total]);
        }
    }
}
