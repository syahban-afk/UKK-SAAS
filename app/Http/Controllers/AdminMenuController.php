<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pakets_model;

class AdminMenuController extends Controller
{
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
        pakets_model::create($validated);
        return back()->with('status', 'Menu berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $paket = pakets_model::findOrFail($id);
        $validated = $request->validate([
            'nama_paket' => ['sometimes', 'string', 'max:50'],
            'jenis' => ['sometimes', 'string', 'in:Prasmanan,Box'],
            'kategori' => ['sometimes', 'string', 'in:Pernikahan,Selamatan,Ulang Tahun,Studi Tour,Rapat'],
            'jumlah_pax' => ['sometimes', 'integer', 'min:0'],
            'harga_paket' => ['sometimes', 'integer', 'min:0'],
            'deskripsi' => ['sometimes', 'string'],
        ]);
        $paket->update($validated);
        return back()->with('status', 'Menu berhasil diupdate.');
    }

    public function destroy($id)
    {
        $paket = pakets_model::findOrFail($id);
        $paket->delete();
        return back()->with('status', 'Menu berhasil dihapus.');
    }
}
