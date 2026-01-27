<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\UsersManagementController;
use App\Models\pakets_model;
use App\Models\pemesanans_model;
use App\Models\detail_pemesanans_model;
use App\Models\jenis_pembayarans_model;
use App\Models\pengirimans_model;
use App\Models\User;
use Illuminate\Support\Str;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user) {
        return match ($user->level) {
            'admin' => redirect()->route('dashboard.admin'),
            'owner' => redirect()->route('dashboard.owner'),
            'kurir' => redirect()->route('dashboard.kurir'),
            default => redirect()->route('login'),
        };
    }
    if (auth('pelanggan')->check()) {
        return redirect()->route('dashboard.pelanggan');
    }
    return redirect()->route('login');
})->name('dashboard');

Route::middleware(['auth', 'level:admin'])->get('/dashboard/admin', function () {
    return view('dashboard.admin');
})->name('dashboard.admin');

Route::middleware(['auth', 'level:owner'])->get('/dashboard/owner', function () {
    return view('dashboard.owner');
})->name('dashboard.owner');

Route::middleware(['auth', 'level:kurir'])->get('/dashboard/kurir', function () {
    return view('dashboard.kurir');
})->name('dashboard.kurir');

Route::middleware(['auth:pelanggan'])->get('/dashboard/pelanggan', function () {
    return view('dashboard.pelanggan');
})->name('dashboard.pelanggan');

Route::middleware(['auth', 'level:owner,admin'])->group(function () {
    Route::get('/dashboard/users', [UsersManagementController::class, 'index'])->name('dashboard.users');
    Route::post('/dashboard/users', [UsersManagementController::class, 'store'])->name('dashboard.users.store');
});
