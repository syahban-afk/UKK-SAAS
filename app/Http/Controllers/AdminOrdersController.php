<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pemesanans_model;
use App\Models\pengirimans_model;

class AdminOrdersController extends Controller
{
    public function status(Request $request)
    {
        $validated = $request->validate([
            'id' => ['required', 'integer', 'exists:pemesanans,id'],
            'status_pesan' => ['required', 'string', 'in:Menunggu Konfirmasi,Sedang Diproses,Menunggu Kurir'],
        ]);
        $order = pemesanans_model::findOrFail($validated['id']);
        $order->update(['status_pesan' => $validated['status_pesan']]);
        return back()->with('status', 'Status pesanan diubah.');
    }

    public function assign(Request $request)
    {
        $validated = $request->validate([
            'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
            'id_user' => ['required', 'integer', 'exists:users,id'],
        ]);
        pengirimans_model::updateOrCreate(
            ['id_pesan' => $validated['id_pesan']],
            [
                'id_user' => $validated['id_user'],
                'tgl_kirim' => now(),
                'status_kirim' => 'Sedang Dikirim',
                'bukti_foto' => '-',
                'tgl_tiba' => null,
            ]
        );
        return back()->with('status', 'Kurir diassign.');
    }

    public function finish(Request $request)
    {
        $validated = $request->validate([
            'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
        ]);
        $pengiriman = pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
        if ($pengiriman) {
            $pengiriman->update([
                'status_kirim' => 'Tiba Ditujuan',
                'tgl_tiba' => now(),
            ]);
        }
        return back()->with('status', 'Pesanan ditandai tiba.');
    }
}
