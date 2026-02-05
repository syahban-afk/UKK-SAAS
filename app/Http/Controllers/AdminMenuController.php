<?php

namespace App\Http\Controllers;

use App\Models\pakets_model;
use Illuminate\Http\Request;

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
            'foto1' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'foto2' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'foto3' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);
        $paths = [
            'foto1' => '/storage/'.$request->file('foto1')->store('pakets', 'public'),
            'foto2' => '/storage/'.$request->file('foto2')->store('pakets', 'public'),
            'foto3' => '/storage/'.$request->file('foto3')->store('pakets', 'public'),
        ];
        pakets_model::create(array_merge($validated, $paths));

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
            'foto1' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'foto2' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'foto3' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ]);

        foreach ([1, 2, 3] as $i) {
            if ($request->hasFile("foto{$i}")) {
                $validated["foto{$i}"] =
                    '/storage/'.$request->file("foto{$i}")->store('pakets', 'public');
            }
        }

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
