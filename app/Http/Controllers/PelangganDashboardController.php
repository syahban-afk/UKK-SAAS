<?php

namespace App\Http\Controllers;

use App\Models\pakets_model;
use App\Models\pemesanans_model;
use App\Models\jenis_pembayarans_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganDashboardController extends Controller
{
    public function index(Request $request)
    {
        $pelanggan = Auth::guard('pelanggan')->user();
        $kategoriNow = $request->query('kategori');
        $search = $request->query('search');
        $kategoris = ['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat'];

        $pakets = pakets_model::when($kategoriNow, fn ($q) => $q->where('kategori', $kategoriNow))
            ->when($search, fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->where('nama_paket', 'like', "%{$search}%")
                    ->orWhere('jenis', 'like', "%{$search}%");
            }))
            ->orderBy('nama_paket')
            ->paginate(12)
            ->withQueryString();

        $orders = pemesanans_model::with(['pengiriman'])
            ->where('id_pelanggan', $pelanggan->id)
            ->orderByDesc('tgl_pesan')
            ->paginate(10);
        $orders->setCollection($orders->getCollection()->map(function ($o) {
            $o->badgeClass = match ($o->status_pesan) {
                'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-700',
                'Sedang Diproses' => 'bg-blue-100 text-blue-700',
                'Menunggu Kurir' => 'bg-purple-100 text-purple-700',
                default => 'bg-gray-100 text-gray-700',
            };
            $o->pengirimanBadgeClass = $o->pengiriman
                ? ($o->pengiriman->status_kirim === 'Tiba Ditujuan'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-blue-100 text-blue-700')
                : null;
            $o->tgl_pesan_fmt = \Carbon\Carbon::parse($o->tgl_pesan)->format('d M Y');
            return $o;
        }));

        $canCheckout = Auth::guard('pelanggan')->check();

        $totalOrders = pemesanans_model::where('id_pelanggan', $pelanggan->id)->count();
        $activeOrders = pemesanans_model::where('id_pelanggan', $pelanggan->id)
            ->whereIn('status_pesan', ['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir'])
            ->count();
        $lastOrder = pemesanans_model::with(['pengiriman'])
            ->where('id_pelanggan', $pelanggan->id)
            ->orderByDesc('tgl_pesan')
            ->first();
        $metrics = [
            'totalOrders' => $totalOrders,
            'activeOrders' => $activeOrders,
            'deliveredCount' => \App\Models\pengirimans_model::whereIn(
                'id_pesan',
                pemesanans_model::where('id_pelanggan', $pelanggan->id)->pluck('id')
            )->where('status_kirim', 'Tiba Ditujuan')->count(),
        ];
        $recommendedPakets = pakets_model::inRandomOrder()->take(4)->get();
        $paymentMethods = jenis_pembayarans_model::with('detailJenisPembayarans')->get();

        return view('dashboard.pelanggan.pelanggan', [
            'pl' => $pelanggan,
            'pakets' => $pakets,
            'orders' => $orders,
            'kategoris' => $kategoris,
            'kategoriNow' => $kategoriNow,
            'search' => $search,
            'canCheckout' => $canCheckout,
            'metrics' => $metrics,
            'lastOrder' => $lastOrder,
            'recommendedPakets' => $recommendedPakets,
            'paymentMethods' => $paymentMethods,
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
