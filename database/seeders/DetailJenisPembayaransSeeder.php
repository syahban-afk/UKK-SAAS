<?php

namespace Database\Seeders;

use App\Models\detail_jenis_pembayarans_model as DetailJenis;
use App\Models\jenis_pembayarans_model as Jenis;
use Illuminate\Database\Seeder;

class DetailJenisPembayaransSeeder extends Seeder
{

    public function run(): void
    {
        $bankIcon = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 10l9 -6l9 6" /><path d="M4 10h16v10H4z" /><path d="M7 14h2v3h-2z" /><path d="M11 14h2v3h-2z" /><path d="M15 14h2v3h-2z" /></svg>';
        $walletIcon = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 6h12a2 2 0 0 1 2 2v2h-4a2 2 0 0 0 -2 2v0a2 2 0 0 0 2 2h4v2a2 2 0 0 1 -2 2h-12a4 4 0 0 1 -4 -4v-6a4 4 0 0 1 4 -4z" /><path d="M18 12h2" /></svg>';
        $cashIcon = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="4" y="6" width="16" height="12" rx="2" /><path d="M8 12h8" /><circle cx="12" cy="12" r="2" /></svg>';
        $cardIcon = 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="%23555" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="3" /><path d="M3 10h18" /><path d="M7 15h.01" /><path d="M11 15h2" /></svg>';

        $transfer = Jenis::firstOrCreate(['metode_pembayaran' => 'Transfer Bank']);
        $ewallet = Jenis::firstOrCreate(['metode_pembayaran' => 'E-Wallet']);
        $tunai = Jenis::firstOrCreate(['metode_pembayaran' => 'Tunai']);
        $kartu = Jenis::firstOrCreate(['metode_pembayaran' => 'Kartu Kredit']);

        $details = [
            [
                'jenis' => $transfer->id,
                'no_rek' => '1234567890',
                'tempat_bayar' => 'Bank BCA',
                'logo' => $bankIcon,
            ],
            [
                'jenis' => $transfer->id,
                'no_rek' => '9876543210',
                'tempat_bayar' => 'Bank Mandiri',
                'logo' => $bankIcon,
            ],
            [
                'jenis' => $ewallet->id,
                'no_rek' => '081234567890',
                'tempat_bayar' => 'DANA',
                'logo' => $walletIcon,
            ],
            [
                'jenis' => $ewallet->id,
                'no_rek' => '081234567891',
                'tempat_bayar' => 'OVO',
                'logo' => $walletIcon,
            ],
            [
                'jenis' => $ewallet->id,
                'no_rek' => '081234567892',
                'tempat_bayar' => 'GoPay',
                'logo' => $walletIcon,
            ],
            [
                'jenis' => $tunai->id,
                'no_rek' => null,
                'tempat_bayar' => 'Bayar di Tempat',
                'logo' => $cashIcon,
            ],
            [
                'jenis' => $kartu->id,
                'no_rek' => null,
                'tempat_bayar' => 'Kartu Kredit (POS)',
                'logo' => $cardIcon,
            ],
        ];

        foreach ($details as $d) {
            DetailJenis::firstOrCreate(
                [
                    'id_jenis_pembayaran' => $d['jenis'],
                    'tempat_bayar' => $d['tempat_bayar'],
                ],
                [
                    'no_rek' => $d['no_rek'],
                    'logo' => $d['logo'],
                ]
            );
        }
    }
}
