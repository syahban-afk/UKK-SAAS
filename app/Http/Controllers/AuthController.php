<?php

namespace App\Http\Controllers;

use App\Models\pelanggans_model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
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

        // 1️⃣ Coba login users
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            return match ($user->role) {
                'owner' => redirect()->intended('/dashboard/owner'),
                'admin' => redirect()->intended('/dashboard/admin'),
                'kurir' => redirect()->intended('/dashboard/kurir'),
                default => redirect('/'),
            };
        }

        // 2️⃣ Kalau users gagal → coba pelanggan
        if (Auth::guard('pelanggan')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended('/dashboard/pelanggan');
        }

        // 3️⃣ Semua gagal
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
            'email' => ['required', 'email', 'unique:pelanggans,email'],
            'password' => ['required', 'min:6'],
            'tgl_lahir' => ['required', 'date'],
            'telepon' => ['required', 'string', 'max:15'],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $pelanggan = pelanggans_model::create($validated);

        Auth::guard('pelanggan')->login($pelanggan);
        $request->session()->regenerate();

        return redirect('/dashboard/pelanggan');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            Auth::guard('web')->logout();
        }
        if (Auth::guard('pelanggan')->check()) {
            Auth::guard('pelanggan')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
