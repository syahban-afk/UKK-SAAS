<?php

namespace App\Http\Controllers;

use App\Models\pemesanans_model;
use App\Models\pengirimans_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KurirPengirimanController extends Controller
{
    public function pick(Request $request)
    {
        $validated = $request->validate([
            'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
        ]);
        $user = Auth::user();
        $pengiriman = pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
        if (! $pengiriman || $pengiriman->id_user !== $user->id) {
            abort(403, 'Unauthorized.');
        }
        if ($pengiriman->status_kirim === 'Tiba Ditujuan') {
            return back()->withErrors(['status' => 'Pengiriman sudah selesai.']);
        }
        if ($pengiriman->status_kirim === 'Sedang Dikirim') {
            return back()->with('status', 'Pengiriman sudah dalam perjalanan.');
        }
        $pengiriman->update([
            'status_kirim' => 'Sedang Dikirim',
            'tgl_kirim' => now(),
        ]);

        return back()->with('status', 'Pesanan diambil untuk dikirim.');
    }

    public function finish(Request $request)
    {
        $validated = $request->validate([
            'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
            'bukti_foto' => ['required', 'file', 'image', 'max:5120'],
        ]);
        $user = Auth::user();
        $pengiriman = pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
        if (! $pengiriman || $pengiriman->id_user !== $user->id) {
            abort(403, 'Unauthorized.');
        }
        if ($pengiriman->status_kirim === 'Tiba Ditujuan') {
            return back()->with('status', 'Pengiriman sudah tiba di tujuan.');
        }
        if ($pengiriman->status_kirim !== 'Sedang Dikirim') {
            return back()->withErrors(['status' => 'Pesanan belum dalam pengiriman.']);
        }
        $path = $request->file('bukti_foto')->store('pengiriman_bukti', 'public');
        $pengiriman->update([
            'status_kirim' => 'Tiba Ditujuan',
            'tgl_tiba' => now(),
            'bukti_foto' => $path,
        ]);
        $order = pemesanans_model::find($validated['id_pesan']);
        if ($order && $order->status_pesan !== 'Selesai') {
            $order->update(['status_pesan' => 'Selesai']);
        }

        return back()->with('status', 'Pesanan ditandai tiba di tujuan.');
    }
}
