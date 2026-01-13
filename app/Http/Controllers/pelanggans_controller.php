<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use App\Models\pelanggans_model;

class pelanggans_controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->query('per_page', 15));
        return response()->json(pelanggans_model::paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:pelanggans,email'],
            'password' => ['required', 'string', 'max:255'],
            'tgl_lahir' => ['required', 'date'],
            'telepon' => ['required', 'string', 'max:15'],
            'alamat1' => ['required', 'string', 'max:255'],
            'alamat2' => ['required', 'string', 'max:255'],
            'alamat3' => ['required', 'string', 'max:255'],
            'kartu_id' => ['required', 'string', 'max:255'],
            'foto' => ['required', 'string', 'max:255'],
        ]);
        $validated['password'] = Hash::make($validated['password']);
        $pelanggan = pelanggans_model::create($validated);
        return response()->json($pelanggan, 201);
    }

    public function show(string $id)
    {
        return response()->json(pelanggans_model::findOrFail($id));
    }

    public function update(Request $request, string $id)
    {
        $pelanggan = pelanggans_model::findOrFail($id);
        $validated = $request->validate([
            'nama_pelanggan' => ['sometimes', 'string', 'max:100'],
            'email' => ['sometimes', 'string', 'email', 'max:255', Rule::unique('pelanggans', 'email')->ignore($pelanggan->id)],
            'password' => ['sometimes', 'string', 'max:255'],
            'tgl_lahir' => ['sometimes', 'date'],
            'telepon' => ['sometimes', 'string', 'max:15'],
            'alamat1' => ['sometimes', 'string', 'max:255'],
            'alamat2' => ['sometimes', 'string', 'max:255'],
            'alamat3' => ['sometimes', 'string', 'max:255'],
            'kartu_id' => ['sometimes', 'string', 'max:255'],
            'foto' => ['sometimes', 'string', 'max:255'],
        ]);
        if (array_key_exists('password', $validated)) {
            $validated['password'] = Hash::make($validated['password']);
        }
        $pelanggan->update($validated);
        return response()->json($pelanggan);
    }

    public function destroy(string $id)
    {
        $pelanggan = pelanggans_model::findOrFail($id);
        $pelanggan->delete();
        return response()->json(null, 204);
    }
}
