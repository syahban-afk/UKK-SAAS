<?php

namespace App\Http\Controllers;

use App\Models\pakets_model;
use App\Models\pemesanans_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganDashboardController extends Controller
{
    public function index(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        $kategoriNow = $request->query('kategori');
        $kategoris = ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat'];

        $pakets = pakets_model::when($kategoriNow, fn ($q) => $q->where('kategori', $kategoriNow))
            ->orderBy('nama_paket')
            ->paginate(12);

        $orders = pemesanans_model::with(['pengiriman'])
            ->where('id_pelanggan', $pelanggan->id)
            ->orderByDesc('tgl_pesan')
            ->paginate(10);

        $canCheckout = Auth::guard('pelanggan')->check();

        return view('dashboard.pelanggan.pelanggan', [
            'pl' => $pelanggan,
            'pakets' => $pakets,
            'orders' => $orders,
            'kategoris' => $kategoris,
            'kategoriNow' => $kategoriNow,
            'canCheckout' => $canCheckout,
        ]);
    }

    public function publicIndex(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        $kategoriNow = $request->query('kategori');
        $kategoris = ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat'];

        $pakets = pakets_model::when($kategoriNow, fn ($q) => $q->where('kategori', $kategoriNow))
            ->orderBy('nama_paket')
            ->paginate(12);

        $orders = pemesanans_model::whereRaw('1=0')->paginate(10);

        $canCheckout = Auth::guard('pelanggan')->check();

        return view('dashboard.pelanggan.pelanggan', [
            'pl' => $pelanggan,
            'pakets' => $pakets,
            'orders' => $orders,
            'kategoris' => $kategoris,
            'kategoriNow' => $kategoriNow,
            'canCheckout' => $canCheckout,
        ]);
    }
}
