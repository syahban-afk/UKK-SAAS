<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\jenis_pembayarans_model;

class jenis_pembayarans_controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->query('per_page', 15));
        return response()->json(jenis_pembayarans_model::paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'metode_pembayaran' => ['required', 'string', 'max:30'],
        ]);
        $jenis = jenis_pembayarans_model::create($validated);
        return response()->json($jenis, 201);
    }

    public function show(string $id)
    {
        return response()->json(jenis_pembayarans_model::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $jenis = jenis_pembayarans_model::findOrFail($id);
        $validated = $request->validate([
            'metode_pembayaran' => ['sometimes', 'string', 'max:30'],
        ]);
        $jenis->update($validated);
        return response()->json($jenis);
    }

    public function destroy(string $id)
    {
        $jenis = jenis_pembayarans_model::findOrFail($id);
        $jenis->delete();
        return response()->json(null, 204);
    }
}
