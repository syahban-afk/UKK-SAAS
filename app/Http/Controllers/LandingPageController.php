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
}
