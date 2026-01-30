<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\pengirimans_model;
use App\Models\pemesanans_model;
use App\Models\User;
use Carbon\Carbon;

class PengirimansSeeder extends Seeder
{
    public function run(): void
    {
        $kurir = User::where('level', 'kurir')->first();
        if (!$kurir) {
            return;
        }
        $orders = pemesanans_model::select(['id', 'tgl_pesan'])->inRandomOrder()->limit(240)->get();
        foreach ($orders as $order) {
            $tglKirim = Carbon::parse($order->tgl_pesan)->addDays(random_int(0, 3))->setTime(random_int(9, 18), random_int(0, 59));
            $isArrived = (bool) random_int(0, 1);
            $tglTiba = $tglKirim->copy()->addDays($isArrived ? random_int(1, 3) : random_int(4, 7));
            $status = $isArrived ? 'Tiba Ditujuan' : 'Sedang Dikirim';
            pengirimans_model::create([
                'tgl_kirim' => $tglKirim,
                'tgl_tiba' => $tglTiba,
                'status_kirim' => $status,
                'bukti_foto' => 'https://img.daisyui.com/images/stock/photo-1.webp',
                'id_pesan' => $order->id,
                'id_user' => $kurir->id,
            ]);
        }
    }
}
