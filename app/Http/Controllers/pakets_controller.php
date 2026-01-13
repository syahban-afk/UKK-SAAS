<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pakets_model;

class pakets_controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->query('per_page', 15));
        return response()->json(pakets_model::paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_paket' => ['required', 'string', 'max:50'],
            'jenis' => ['required', 'string', 'in:Prasmanan,Box'],
            'kategori' => ['required', 'string', 'in:Pernikahan,Selamatan,Ulang Tahun,Studi Tour,Rapat'],
            'jumlah_pax' => ['required', 'integer', 'min:0'],
            'harga_paket' => ['required', 'integer', 'min:0'],
            'deskripsi' => ['required', 'string'],
            'foto1' => ['required', 'string', 'max:255'],
            'foto2' => ['required', 'string', 'max:255'],
            'foto3' => ['required', 'string', 'max:255'],
        ]);
        $paket = pakets_model::create($validated);
        return response()->json($paket, 201);
    }

    public function show(string $id)
    {
        return response()->json(pakets_model::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $paket = pakets_model::findOrFail($id);
        $validated = $request->validate([
            'nama_paket' => ['sometimes', 'string', 'max:50'],
            'jenis' => ['sometimes', 'string', 'in:Prasmanan,Box'],
            'kategori' => ['sometimes', 'string', 'in:Pernikahan,Selamatan,Ulang Tahun,Studi Tour,Rapat'],
            'jumlah_pax' => ['sometimes', 'integer', 'min:0'],
            'harga_paket' => ['sometimes', 'integer', 'min:0'],
            'deskripsi' => ['sometimes', 'string'],
            'foto1' => ['sometimes', 'string', 'max:255'],
            'foto2' => ['sometimes', 'string', 'max:255'],
            'foto3' => ['sometimes', 'string', 'max:255'],
        ]);
        $paket->update($validated);
        return response()->json($paket);
    }

    public function destroy(string $id)
    {
        $paket = pakets_model::findOrFail($id);
        $paket->delete();
        return response()->json(null, 204);
    }
}
