<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WebSettingsController extends Controller
{
    public function profile(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);
        $user->update($validated);
        return back()->with('status', 'Profil diperbarui.');
    }

    public function password(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
        $user->update(['password' => $validated['password']]);
        return back()->with('status', 'Password diubah.');
    }

    public function logoutOthers()
    {
        $user = Auth::user();
        DB::table('sessions')->where('user_id', $user->id)->delete();
        return back()->with('status', 'Logout dari semua perangkat berhasil.');
    }

    public function deactivate(Request $request)
    {
        DB::table('sessions')->where('user_id', Auth::id())->delete();
        $guard = Auth::guard();
        $guard->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
