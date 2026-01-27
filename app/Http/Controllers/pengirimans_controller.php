<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pengirimans_model;
use App\Models\User;

class pengirimans_controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->query('per_page', 15));
        return response()->json(pengirimans_model::with(['pemesanan', 'user'])->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl_kirim' => ['required', 'date'],
            'tgl_tiba' => ['required', 'date'],
            'status_kirim' => ['required', 'string', 'in:Sedang Dikirim,Tiba Ditujuan'],
            'bukti_foto' => ['required', 'string', 'max:255'],
            'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
            'id_user' => ['required', 'integer', 'exists:users,id'],
        ]);
        $pengiriman = pengirimans_model::create($validated);
        return response()->json($pengiriman->load(['pemesanan', 'user']), 201);
    }

    public function show(string $id)
    {
        $pengiriman = pengirimans_model::with(['pemesanan', 'user'])->findOrFail($id);
        return response()->json($pengiriman);
    }

    public function update(Request $request, string $id)
    {
        $pengiriman = pengirimans_model::findOrFail($id);
        $validated = $request->validate([
            'tgl_kirim' => ['sometimes', 'date'],
            'tgl_tiba' => ['sometimes', 'date'],
            'status_kirim' => ['sometimes', 'string', 'in:Sedang Dikirim,Tiba Ditujuan'],
            'bukti_foto' => ['sometimes', 'string', 'max:255'],
            'id_pesan' => ['sometimes', 'integer', 'exists:pemesanans,id'],
            'id_user' => ['sometimes', 'integer', 'exists:users,id'],
        ]);
        $pengiriman->update($validated);
        return response()->json($pengiriman->load(['pemesanan', 'user']));
    }

    public function destroy(string $id)
    {
        $pengiriman = pengirimans_model::findOrFail($id);
        $pengiriman->delete();
        return response()->json(null, 204);
    }
}
