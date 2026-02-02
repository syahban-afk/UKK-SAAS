<?php

namespace App\Http\Controllers;

use App\Models\pakets_model;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = pakets_model::query();

        if ($request->has('category') && $request->category != 'All') {
            $query->where('kategori', $request->category);
        }

        if ($request->has('search')) {
            $query->where('nama_paket', 'like', '%' . $request->search . '%');
        }

        $menus = $query->get();
        $categories = ['All', 'Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat'];

        return view('welcome', compact('menus', 'categories'));
    }

    public function show($id)
    {
        $menu = pakets_model::findOrFail($id);
        return view('menus.show', compact('menu'));
    }
}
