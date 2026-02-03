<?php

namespace App\Http\Controllers;

use App\Models\pakets_model;
use App\Models\pemesanans_model;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $featuredMenus = pakets_model::take(3)->get();
        return view('welcome', compact('featuredMenus'));
    }

    public function status(Request $request)
    {
        $resi = $request->query('resi');
        $order = null;
        if ($resi) {
            $order = pemesanans_model::with(['pengiriman', 'detailPemesanans.paket', 'pelanggan'])
                ->where('no_resi', $resi)
                ->first();
        }
        return view('status', [
            'order' => $order,
            'resi' => $resi,
        ]);
    }

    public function menu(Request $request)
    {
        $kategoriNow = $request->query('kategori');
        $search = $request->query('search');
        $pakets = pakets_model::query()
            ->when($kategoriNow, fn($q) => $q->where('kategori', $kategoriNow))
            ->when($search, fn($q) => $q->where(function($qq) use ($search) {
                $qq->where('nama_paket', 'like', "%{$search}%")
                   ->orWhere('jenis', 'like', "%{$search}%");
            }))
            ->orderBy('nama_paket')
            ->paginate(12)
            ->withQueryString();
        $kategoris = ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat'];
        return view('menu', [
            'pakets' => $pakets,
            'kategoris' => $kategoris,
            'kategoriNow' => $kategoriNow,
            'search' => $search,
        ]);
    }

    public function info()
    {
        $totalMenu = pakets_model::count();
        $totalPesanan = pemesanans_model::count();
        $totalKurir = \App\Models\User::where('level','kurir')->count();
        return view('dashboard.pelanggan.info', [
            'totalMenu' => $totalMenu,
            'totalPesanan' => $totalPesanan,
            'totalKurir' => $totalKurir,
        ]);
    }
}
