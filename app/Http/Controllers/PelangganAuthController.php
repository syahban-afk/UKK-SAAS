<?php

namespace App\Http\Controllers;

use App\Models\pelanggans_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelangganAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if (Auth::guard('pelanggan')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard/pelanggan');
        }
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:pelanggans,email'],
            'password' => ['required', 'string', 'min:6'],
            'tgl_lahir' => ['required', 'date'],
            'telepon' => ['required', 'string', 'max:15'],
            'alamat1' => ['required', 'string', 'max:255'],
            'alamat2' => ['required', 'string', 'max:255'],
            'alamat3' => ['required', 'string', 'max:255'],
            'kartu_id' => ['required', 'string', 'max:255'],
            'foto' => ['required', 'string', 'max:255'],
        ]);
        $pelanggan = pelanggans_model::create($validated);
        Auth::guard('pelanggan')->login($pelanggan);
        return redirect('/dashboard/pelanggan');
    }

    public function logout(Request $request)
    {
        Auth::guard('pelanggan')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
