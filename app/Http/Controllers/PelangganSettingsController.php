<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PelangganSettingsController extends Controller
{
    public function settingsView()
    {
        return view('dashboard.pelanggan.settings');
    }

    public function profile(Request $request)
    {
        $pl = auth('pelanggan')->user();
        $validated = $request->validate([
            'nama_pelanggan' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('pelanggans', 'email')->ignore($pl->id)],
            'telepon' => ['required', 'string', 'max:15'],
            'alamat1' => ['nullable', 'string', 'max:255'],
            'alamat2' => ['nullable', 'string', 'max:255'],
            'alamat3' => ['nullable', 'string', 'max:255'],
        ]);
        $pl->update($validated);

        return back()->with('status', 'Profil pelanggan diperbarui.');
    }

    public function password(Request $request)
    {
        $pl = auth('pelanggan')->user();
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        $pl->update(['password' => $validated['password']]);

        return back()->with('status', 'Password diubah.');
    }

    public function logoutOthers()
    {
        $pl = auth('pelanggan')->user();
        DB::table('sessions')->where('user_id', $pl->id)->delete();

        return back()->with('status', 'Logout dari semua perangkat berhasil.');
    }

    public function defaultAddress(Request $request)
    {
        $pl = auth('pelanggan')->user();
        $validated = $request->validate([
            'default_address' => ['required', 'string', 'in:alamat1,alamat2,alamat3'],
        ]);
        $field = $validated['default_address'];
        $value = $pl->$field;
        if ($value) {
            $pl->update(['alamat1' => $value]);

            return back()->with('status', 'Alamat utama diperbarui.');
        }

        return back()->withErrors(['default_address' => 'Alamat tidak tersedia.']);
    }

    public function avatar(Request $request)
    {
        $pl = auth('pelanggan')->user();

        $validated = $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'], // Max 2MB
        ]);

        // Remove old avatar if exists
        if ($pl->foto) {
            Storage::disk('public')->delete($pl->foto);
        }

        // Store new avatar
        $path = $request->file('avatar')->store('avatars', 'public');
        $pl->update(['foto' => $path]);

        return back()->with('status', 'Foto profil berhasil diperbarui.');
    }

    public function removeAvatar(Request $request)
    {
        $pl = auth('pelanggan')->user();

        // Remove avatar file
        if ($pl->foto) {
            Storage::disk('public')->delete($pl->foto);
            $pl->update(['foto' => null]);
        }

        return back()->with('status', 'Foto profil berhasil dihapus.');
    }

    public function deactivate(Request $request)
    {
        DB::table('sessions')->where('user_id', auth('pelanggan')->id())->delete();
        $guard = auth('pelanggan');
        $guard->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
