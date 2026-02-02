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
        Route::get('/menu', fn() => view('dashboard.admin.menu'))->name('menu');
        Route::post('/menu', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'nama_paket' => ['required', 'string', 'max:50'],
                'jenis' => ['required', 'string', 'in:Prasmanan,Box'],
                'kategori' => ['required', 'string', 'in:Pernikahan,Selamatan,Ulang Tahun,Studi Tour,Rapat'],
                'jumlah_pax' => ['required', 'integer', 'min:0'],
                'harga_paket' => ['required', 'integer', 'min:0'],
                'deskripsi' => ['required', 'string'],
                'foto1' => ['required', 'string', 'max:255'],
                'foto2' => ['required', 'string', 'max:255'],
                'foto3' => ['required', 'string', 'max:255'],
            ]);
            \App\Models\pakets_model::create($validated);
            return back()->with('status', 'Menu berhasil dibuat.');
        })->name('menu.store');
        Route::put('/menu/{id}', function (\Illuminate\Http\Request $request, $id) {
            $paket = \App\Models\pakets_model::findOrFail($id);
            $validated = $request->validate([
                'nama_paket' => ['sometimes', 'string', 'max:50'],
                'jenis' => ['sometimes', 'string', 'in:Prasmanan,Box'],
                'kategori' => ['sometimes', 'string', 'in:Pernikahan,Selamatan,Ulang Tahun,Studi Tour,Rapat'],
                'jumlah_pax' => ['sometimes', 'integer', 'min:0'],
                'harga_paket' => ['sometimes', 'integer', 'min:0'],
                'deskripsi' => ['sometimes', 'string'],
            ]);
            $paket->update($validated);
            return back()->with('status', 'Menu berhasil diupdate.');
        })->name('menu.update');
        Route::delete('/menu/{id}', function ($id) {
            $paket = \App\Models\pakets_model::findOrFail($id);
            $paket->delete();
            return back()->with('status', 'Menu berhasil dihapus.');
        })->name('menu.destroy');

        Route::get('/orders', fn() => view('dashboard.admin.orders'))->name('orders');
        Route::post('/orders/status', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'id' => ['required','integer','exists:pemesanans,id'],
                'status_pesan' => ['required','string','in:Menunggu Konfirmasi,Sedang Diproses,Menunggu Kurir'],
            ]);
            $order = \App\Models\pemesanans_model::findOrFail($validated['id']);
            $order->update(['status_pesan' => $validated['status_pesan']]);
            return back()->with('status', 'Status pesanan diubah.');
        })->name('orders.status');
        Route::post('/orders/assign', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'id_pesan' => ['required','integer','exists:pemesanans,id'],
                'id_user' => ['required','integer','exists:users,id'],
            ]);
            \App\Models\pengirimans_model::updateOrCreate(
                ['id_pesan' => $validated['id_pesan']],
                [
                    'id_user' => $validated['id_user'],
                    'tgl_kirim' => now(),
                    'status_kirim' => 'Sedang Dikirim',
                    'bukti_foto' => '-',
                    'tgl_tiba' => null,
                ]
            );
            return back()->with('status', 'Kurir diassign.');
        })->name('orders.assign');
        Route::post('/orders/finish', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'id_pesan' => ['required','integer','exists:pemesanans,id'],
            ]);
            $pengiriman = \App\Models\pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
            if ($pengiriman) {
                $pengiriman->update([
                    'status_kirim' => 'Tiba Ditujuan',
                    'tgl_tiba' => now(),
                ]);
            }
            return back()->with('status', 'Pesanan ditandai tiba.');
        })->name('orders.finish');

        Route::get('/users', [UsersManagementController::class, 'index'])->name('users');
        Route::post('/users', [UsersManagementController::class, 'store'])->name('users.store');

        Route::get('/report', fn() => view('dashboard.admin.report'))->name('report');
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
            $filename = 'laporan_penjualan_admin_' . $range . '_' . now()->format('Ymd_His') . '.csv';
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
    });

Route::middleware(['auth', 'level:kurir'])
    ->prefix('dashboard/kurir')
    ->name('dashboard.kurir.')
    ->group(function () {
        Route::get('/', fn() => view('dashboard.kurir.kurir'))->name('index');
        Route::post('/pengiriman/pick', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'id_pesan' => ['required','integer','exists:pemesanans,id'],
            ]);
            $user = \Illuminate\Support\Facades\Auth::user();
            $pengiriman = \App\Models\pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
            if (! $pengiriman || $pengiriman->id_user !== $user->id) {
                abort(403, 'Unauthorized.');
            }
            if ($pengiriman->status_kirim === 'Tiba Ditujuan') {
                return back()->withErrors(['status' => 'Pengiriman sudah selesai.']);
            }
            if ($pengiriman->status_kirim === 'Sedang Dikirim') {
                return back()->with('status', 'Pengiriman sudah dalam perjalanan.');
            }
            $pengiriman->update([
                'status_kirim' => 'Sedang Dikirim',
                'tgl_kirim' => now(),
            ]);
            return back()->with('status', 'Pesanan diambil untuk dikirim.');
        })->name('pick');
        Route::post('/pengiriman/finish', function (\Illuminate\Http\Request $request) {
            $validated = $request->validate([
                'id_pesan' => ['required','integer','exists:pemesanans,id'],
            ]);
            $user = \Illuminate\Support\Facades\Auth::user();
            $pengiriman = \App\Models\pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
            if (! $pengiriman || $pengiriman->id_user !== $user->id) {
                abort(403, 'Unauthorized.');
            }
            if ($pengiriman->status_kirim === 'Tiba Ditujuan') {
                return back()->with('status', 'Pengiriman sudah tiba di tujuan.');
            }
            if ($pengiriman->status_kirim !== 'Sedang Dikirim') {
                return back()->withErrors(['status' => 'Pesanan belum dalam pengiriman.']);
            }
            $pengiriman->update([
                'status_kirim' => 'Tiba Ditujuan',
                'tgl_tiba' => now(),
            ]);
            return back()->with('status', 'Pesanan ditandai tiba di tujuan.');
        })->name('finish');
    });

Route::middleware('auth:pelanggan')
    ->prefix('dashboard/pelanggan')
    ->name('dashboard.pelanggan.')
    ->group(function () {
        Route::get('/', fn() => view('dashboard.pelanggan.pelanggan'))->name('index');
    });
