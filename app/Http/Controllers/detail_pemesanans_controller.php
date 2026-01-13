<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\detail_pemesanans_model;

class detail_pemesanans_controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->query('per_page', 15));
        return response()->json(detail_pemesanans_model::with(['pemesanan', 'paket'])->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pemesanan' => ['required', 'integer', 'exists:pemesanans,id'],
            'id_paket' => ['required', 'integer', 'exists:pakets,id'],
            'subtotal' => ['required', 'integer', 'min:0'],
        ]);
        $detail = detail_pemesanans_model::create($validated);
        return response()->json($detail->load(['pemesanan', 'paket']), 201);
    }

    public function show(string $id)
    {
        $detail = detail_pemesanans_model::with(['pemesanan', 'paket'])->findOrFail($id);
        return response()->json($detail);
    }

    public function update(Request $request, string $id)
    {
        $detail = detail_pemesanans_model::findOrFail($id);
        $validated = $request->validate([
            'id_pemesanan' => ['sometimes', 'integer', 'exists:pemesanans,id'],
            'id_paket' => ['sometimes', 'integer', 'exists:pakets,id'],
            'subtotal' => ['sometimes', 'integer', 'min:0'],
        ]);
        $detail->update($validated);
        return response()->json($detail->load(['pemesanan', 'paket']));
    }

    public function destroy(string $id)
    {
        $detail = detail_pemesanans_model::findOrFail($id);
        $detail->delete();
        return response()->json(null, 204);
    }
}
