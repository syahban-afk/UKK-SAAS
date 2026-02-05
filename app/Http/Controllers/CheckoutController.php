<?php

namespace App\Http\Controllers;

use App\Models\detail_pemesanans_model;
use App\Models\jenis_pembayarans_model;
use App\Models\pakets_model;
use App\Models\pelanggans_model as Pelanggan;
use App\Models\pemesanans_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function show()
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        $paymentMethods = jenis_pembayarans_model::with('detailJenisPembayarans')->get();

        return view('dashboard.pelanggan.checkout', [
            'pl' => $pelanggan,
            'paymentMethods' => $paymentMethods,
        ]);
    }

    public function store(Request $request)
    {
        $isJsonFlow = $request->has('cart') && $request->has('use_address');
        if ($isJsonFlow) {
            $request->validate([
                'cart' => ['required', 'array', 'min:1'],
                'cart.*.id' => ['required', 'exists:pakets,id'],
                'cart.*.qty' => ['required', 'integer', 'min:1'],
                'use_address' => ['required', 'string', 'in:alamat1,alamat2,alamat3'],
                'payment_id' => ['required', 'integer', 'exists:jenis_pembayarans,id'],
            ]);
        } else {
            $request->validate([
                'nama' => ['required', 'string', 'max:100'],
                'telepon' => ['required', 'string', 'max:15'],
                'alamat1' => ['required', 'string', 'max:255'],
                'alamat2' => ['nullable', 'string', 'max:255'],
                'alamat3' => ['nullable', 'string', 'max:255'],
                'cart' => ['required', 'array', 'min:1'],
                'cart.*.id' => ['required', 'exists:pakets,id'],
                'cart.*.qty' => ['required', 'integer', 'min:1'],
            ]);
        }

        /** @var Pelanggan $pelanggan */
        $pelanggan = Auth::guard('pelanggan')->user();

        if ($isJsonFlow) {
            $use = $request->input('use_address');
            $selectedAddress = $pelanggan->$use;
            if (! $selectedAddress) {
                return response()->json(['message' => 'Alamat tidak tersedia'], 422);
            }
            $pelanggan->update([
                'alamat1' => $selectedAddress,
                'alamat2' => $pelanggan->alamat2,
                'alamat3' => $pelanggan->alamat3,
            ]);
        } else {
            $pelanggan->update([
                'nama_pelanggan' => $request->nama,
                'telepon' => $request->telepon,
                'alamat1' => $request->alamat1,
                'alamat2' => $request->alamat2,
                'alamat3' => $request->alamat3,
            ]);
        }

        return DB::transaction(function () use ($request, $pelanggan, $isJsonFlow) {
            $totalBayar = 0;
            $items = [];

            foreach ($request->cart as $item) {
                $paket = pakets_model::find($item['id']);
                $subtotal = $paket->harga_paket * $item['qty'];
                $totalBayar += $subtotal;
                $items[] = [
                    'id_paket' => $item['id'],
                    'qty' => $item['qty'],
                    'subtotal' => $subtotal,
                ];
            }

            $pemesanan = pemesanans_model::create([
                'id_pelanggan' => $pelanggan->id,
                'id_jenis_bayar' => $isJsonFlow ? $request->integer('payment_id') : 1,
                'no_resi' => 'RES'.strtoupper(Str::random(10)),
                'tgl_pesan' => now(),
                'status_pesan' => 'Menunggu Konfirmasi',
                'total_bayar' => $totalBayar,
            ]);

            foreach ($items as $item) {
                $item['id_pemesanan'] = $pemesanan->id;
                detail_pemesanans_model::create($item);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'no_resi' => $pemesanan->no_resi,
            ]);
        });
    }
}
