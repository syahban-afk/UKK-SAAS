<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\pemesanans_model;

class pemesanans_controller extends Controller
{
    public function index(Request $request)
    {
        $perPage = (int) ($request->query('per_page', 15));
        $query = pemesanans_model::with(['pelanggan', 'jenisPembayaran', 'detailPemesanans.paket', 'pengiriman']);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pelanggan' => ['required', 'integer', 'exists:pelanggans,id'],
            'id_jenis_bayar' => ['required', 'integer', 'exists:jenis_pembayarans,id'],
            'no_resi' => ['required', 'string', 'max:30', 'unique:pemesanans,no_resi'],
            'tgl_pesan' => ['required', 'date'],
            'status_pesan' => ['required', 'string', 'in:Menunggu Konfirmasi,Sedang Diproses,Menunggu Kurir'],
            'total_bayar' => ['required', 'integer', 'min:0'],
        ]);
        $pemesanan = pemesanans_model::create($validated);
        return response()->json($pemesanan->load(['pelanggan', 'jenisPembayaran']), 201);
    }

    public function show(string $id)
    {
        $pemesanan = pemesanans_model::with(['pelanggan', 'jenisPembayaran', 'detailPemesanans.paket', 'pengiriman'])->findOrFail($id);
        return response()->json($pemesanan);
    }

    public function update(Request $request, string $id)
    {
        $pemesanan = pemesanans_model::findOrFail($id);
        $validated = $request->validate([
            'id_pelanggan' => ['sometimes', 'integer', 'exists:pelanggans,id'],
            'id_jenis_bayar' => ['sometimes', 'integer', 'exists:jenis_pembayarans,id'],
            'no_resi' => ['sometimes', 'string', 'max:30', Rule::unique('pemesanans', 'no_resi')->ignore($pemesanan->id)],
            'tgl_pesan' => ['sometimes', 'date'],
            'status_pesan' => ['sometimes', 'string', 'in:Menunggu Konfirmasi,Sedang Diproses,Menunggu Kurir'],
            'total_bayar' => ['sometimes', 'integer', 'min:0'],
        ]);
        $pemesanan->update($validated);
        return response()->json($pemesanan->load(['pelanggan', 'jenisPembayaran', 'detailPemesanans.paket', 'pengiriman']));
    }

    public function destroy(string $id)
    {
        $pemesanan = pemesanans_model::findOrFail($id);
        $pemesanan->delete();
        return response()->json(null, 204);
    }
}
