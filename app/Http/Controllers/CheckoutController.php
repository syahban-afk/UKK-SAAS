<?php

namespace App\Http\Controllers;

use App\Models\detail_pemesanans_model;
use App\Models\pemesanans_model;
use App\Models\pakets_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'alamat1' => 'required|string|max:255',
            'alamat2' => 'nullable|string|max:255',
            'alamat3' => 'nullable|string|max:255',
            'cart' => 'required|array|min:1',
            'cart.*.id' => 'required|exists:pakets,id',
            'cart.*.qty' => 'required|integer|min:1',
        ]);

        $pelanggan = Auth::guard('pelanggan')->user();

        // Update address if changed
        $pelanggan->update([
            'nama_pelanggan' => $request->nama,
            'telepon' => $request->telepon,
            'alamat1' => $request->alamat1,
            'alamat2' => $request->alamat2,
            'alamat3' => $request->alamat3,
        ]);

        return DB::transaction(function () use ($request, $pelanggan) {
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
                'id_jenis_bayar' => 1, // Default to first payment method
                'no_resi' => 'RES' . strtoupper(Str::random(10)),
                'tgl_pesan' => now(),
                'status_pesan' => 'Menunggu Konfirmasi',
                'total_bayar' => $totalBayar,
            ]);

            foreach ($items as $item) {
                $item['id_pesan'] = $pemesanan->id;
                detail_pemesanans_model::create($item);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat!',
                'no_resi' => $pemesanan->no_resi
            ]);
        });
    }
}
