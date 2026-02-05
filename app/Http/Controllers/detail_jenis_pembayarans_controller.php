<?php

namespace App\Http\Controllers;

use App\Models\detail_jenis_pembayarans_model;
use Illuminate\Http\Request;

class detail_jenis_pembayarans_controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->query('per_page', 15));

        return response()->json(detail_jenis_pembayarans_model::with('jenisPembayaran')->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jenis_pembayaran' => ['required', 'integer', 'exists:jenis_pembayarans,id'],
            'no_rek' => ['required', 'string', 'max:25'],
            'tempat_bayar' => ['required', 'string', 'max:50'],
            'logo' => ['required', 'string', 'max:255'],
        ]);
        $detail = detail_jenis_pembayarans_model::create($validated);

        return response()->json($detail, 201);
    }

    public function show(string $id)
    {
        $detail = detail_jenis_pembayarans_model::with('jenisPembayaran')->findOrFail($id);

        return response()->json($detail);
    }

    public function update(Request $request, string $id)
    {
        $detail = detail_jenis_pembayarans_model::findOrFail($id);
        $validated = $request->validate([
            'id_jenis_pembayaran' => ['sometimes', 'integer', 'exists:jenis_pembayarans,id'],
            'no_rek' => ['sometimes', 'string', 'max:25'],
            'tempat_bayar' => ['sometimes', 'string', 'max:50'],
            'logo' => ['sometimes', 'string', 'max:255'],
        ]);
        $detail->update($validated);

        return response()->json($detail);
    }

    public function destroy(string $id)
    {
        $detail = detail_jenis_pembayarans_model::findOrFail($id);
        $detail->delete();

        return response()->json(null, 204);
    }
}
