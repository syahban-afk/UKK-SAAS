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
    if (Auth::check()) {
        return match (Auth::user()->level) {
            'owner' => redirect()->route('dashboard.owner.index'),
            'admin' => redirect()->route('dashboard.admin.index'),
            'kurir' => redirect()->route('dashboard.kurir.index'),
            default => redirect()->route('login'),
        };
    }

    if (auth('pelanggan')->check()) {
        return redirect()->route('dashboard.pelanggan.index');
    }

    return redirect()->route('login');
})->name('dashboard');

Route::middleware(['auth', 'level:owner'])
    ->prefix('dashboard/owner')
    ->name('dashboard.owner.')
    ->group(function () {

        Route::get('/', function () {
            return view('dashboard.owner.owner');
        })->name('index');

        Route::get('/report', function () {
            return view('dashboard.owner.report');
        })->name('report');
        Route::get('/report/export', function (\Illuminate\Http\Request $request) {
            $range = $request->query('range', 'bulan');
            $now = \Carbon\Carbon::now();
            $end = $now->copy()->endOfDay();
            $start = match ($range) {
                'hari' => $now->copy()->startOfDay(),
                'minggu' => $now->copy()->subDays(6)->startOfDay(),
                'bulan' => $now->copy()->subDays(29)->startOfDay(),
                'tahun' => $now->copy()->subDays(365)->startOfDay(),
                default => $now->copy()->subDays(29)->startOfDay(),
            };
            $rows = \Illuminate\Support\Facades\DB::table('pemesanans')
                ->join('pelanggans', 'pelanggans.id', '=', 'pemesanans.id_pelanggan')
                ->select('pemesanans.id', 'pemesanans.no_resi', 'pelanggans.nama_pelanggan', 'pemesanans.total_bayar', 'pemesanans.tgl_pesan', 'pemesanans.status_pesan')
                ->whereBetween('pemesanans.tgl_pesan', [$start, $end])
                ->orderBy('pemesanans.tgl_pesan')
                ->get();
            $filename = 'laporan_penjualan_' . $range . '_' . now()->format('Ymd_His') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];
            $callback = function () use ($rows) {
                $handle = fopen('php://output', 'w');
                fputcsv($handle, ['ID', 'No Resi', 'Pelanggan', 'Total Bayar', 'Tanggal Pesan', 'Status']);
                foreach ($rows as $row) {
                    fputcsv($handle, [
                        $row->id,
                        $row->no_resi,
                        $row->nama_pelanggan,
                        $row->total_bayar,
                        \Carbon\Carbon::parse($row->tgl_pesan)->format('Y-m-d'),
                        $row->status_pesan,
                    ]);
                }
                fclose($handle);
            };
            return response()->stream($callback, 200, $headers);
        })->name('report.export');

        Route::get('/users', [UsersManagementController::class, 'index'])->name('users');

        Route::post('/users', [UsersManagementController::class, 'store'])->name('users.store');

        Route::get('/monitoring', function () {
            return view('dashboard.owner.monitoring');
        })->name('monitoring');
    });

Route::middleware(['auth', 'level:admin'])
    ->prefix('dashboard/admin')
    ->name('dashboard.admin.')
    ->group(function () {
        Route::get('/', fn() => view('dashboard.admin.admin'))->name('index');
    });

Route::middleware(['auth', 'level:kurir'])
    ->prefix('dashboard/kurir')
    ->name('dashboard.kurir.')
    ->group(function () {
        Route::get('/', fn() => view('dashboard.kurir.kurir'))->name('index');
    });

Route::middleware('auth:pelanggan')
    ->prefix('dashboard/pelanggan')
    ->name('dashboard.pelanggan.')
    ->group(function () {
        Route::get('/', fn() => view('dashboard.pelanggan.pelanggan'))->name('index');
    });
