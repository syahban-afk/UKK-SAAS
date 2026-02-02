<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UsersManagementController extends Controller
{
    public function index(Request $request)
    {
        $current = Auth::user();
        $levels = $current->level === 'owner' ? ['admin', 'kurir'] : ['kurir'];
        $windowSeconds = 300;
        $threshold = now()->timestamp - $windowSeconds;
        $onlineIds = DB::table('sessions')
            ->where('last_activity', '>=', $threshold)
            ->pluck('user_id')
            ->filter()
            ->unique()
            ->values()
            ->all();
        $allowedLevels = $current->level === 'owner' ? ['admin', 'kurir'] : ['kurir'];
        $users = User::whereIn('level', $allowedLevels)->get();

        $totalUsers = $users->count();
        $totalAdmin = $users->where('level', 'admin')->count();
        $totalKurir = $users->where('level', 'kurir')->count();
        $totalOnline = $users->whereIn('id', $onlineIds)->count();

        if ($current->level === 'owner') {
            return view('dashboard.owner.user', [
                'users' => $users,
                'onlineIds' => $onlineIds,
                'levels' => $levels,
                'totalUsers' => $totalUsers,
                'totalAdmin' => $totalAdmin,
                'totalKurir' => $totalKurir,
                'totalOnline' => $totalOnline,
            ]);
        } else {
            return view('dashboard.admin.user', [
                'users' => $users,
                'onlineIds' => $onlineIds,
                'levels' => $levels,
                'totalUsers' => $totalUsers,
                'totalAdmin' => $totalAdmin,
                'totalKurir' => $totalKurir,
                'totalOnline' => $totalOnline,
            ]);
        }
    }

    public function store(Request $request)
    {
        $current = Auth::user();
        $allowed = $current->level === 'owner' ? ['admin', 'kurir'] : ['kurir'];
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'level' => ['required', 'string', 'in:admin,kurir'],
        ]);
        if (!in_array($validated['level'], $allowed, true)) {
            return back()->withErrors(['level' => 'Level tidak diizinkan.'])->withInput();
        }
        $user = User::create($validated);
        if ($current->level === 'owner') {
            return redirect()->route('dashboard.owner.users')->with('status', 'User berhasil dibuat.');
        }
        return redirect()->route('dashboard.admin.users')->with('status', 'User berhasil dibuat.');
    }
}
