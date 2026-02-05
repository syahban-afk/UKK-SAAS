<?php

use App\Http\Controllers\AdminMenuController;
use App\Http\Controllers\AdminOrdersController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KurirPengirimanController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\PelangganDashboardController;
use App\Http\Controllers\PelangganSettingsController;
use App\Http\Controllers\UsersManagementController;
use App\Http\Controllers\WebSettingsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;

Route::get('/', [LandingPageController::class, 'index']);
Route::get('/menu', [LandingPageController::class, 'menu'])->name('menu.public');

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
        Route::get('/report/export', function (Request $request) {
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
            $rows = DB::table('pemesanans')
                ->join('pelanggans', 'pelanggans.id', '=', 'pemesanans.id_pelanggan')
                ->select('pemesanans.id', 'pemesanans.no_resi', 'pelanggans.nama_pelanggan', 'pemesanans.total_bayar', 'pemesanans.tgl_pesan', 'pemesanans.status_pesan')
                ->whereBetween('pemesanans.tgl_pesan', [$start, $end])
                ->orderBy('pemesanans.tgl_pesan')
                ->get();
            $filename = 'laporan_penjualan_'.$range.'_'.now()->format('Ymd_His').'.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
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

        Route::get('/settings', fn () => view('dashboard.settings'))->name('settings');
        Route::post('/settings/profile', function (Request $request) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:30'],
                'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            ]);
            $user->update($validated);

            return back()->with('status', 'Profil diperbarui.');
        })->name('settings.profile');
        Route::post('/settings/password', function (Request $request) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $validated = $request->validate([
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
            $user->update(['password' => $validated['password']]);

            return back()->with('status', 'Password diubah.');
        })->name('settings.password');
        Route::post('/settings/logout-others', function () {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            DB::table('sessions')->where('user_id', $user->id)->delete();

            return back()->with('status', 'Logout dari semua perangkat berhasil.');
        })->name('settings.logout-others');
        Route::post('/settings/deactivate', function (Request $request) {
            DB::table('sessions')->where('user_id', Auth::id())->delete();
            /** @var \Illuminate\Contracts\Auth\StatefulGuard $guard */
            $guard = Auth::guard();
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/');
        })->name('settings.deactivate');
    });

Route::middleware(['auth', 'level:admin'])
    ->prefix('dashboard/admin')
    ->name('dashboard.admin.')
    ->group(function () {
        Route::get('/', fn () => view('dashboard.admin.admin'))->name('index');
        Route::get('/menu', fn () => view('dashboard.admin.menu'))->name('menu');
        Route::post('/menu', [AdminMenuController::class, 'store'])->name('menu.store');
        Route::put('/menu/{id}', [AdminMenuController::class, 'update'])->name('menu.update');
        Route::delete('/menu/{id}', [AdminMenuController::class, 'destroy'])->name('menu.destroy');

        Route::get('/orders', fn () => view('dashboard.admin.orders'))->name('orders');
        Route::post('/orders/status', [AdminOrdersController::class, 'status'])->name('orders.status');
        Route::post('/orders/assign', [AdminOrdersController::class, 'assign'])->name('orders.assign');
        Route::post('/orders/finish', [AdminOrdersController::class, 'finish'])->name('orders.finish');

        Route::get('/users', [UsersManagementController::class, 'index'])->name('users');
        Route::post('/users', [UsersManagementController::class, 'store'])->name('users.store');

        Route::get('/report', fn () => view('dashboard.admin.report'))->name('report');
        Route::get('/report/export', function (Request $request) {
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
            $rows = DB::table('pemesanans')
                ->join('pelanggans', 'pelanggans.id', '=', 'pemesanans.id_pelanggan')
                ->select('pemesanans.id', 'pemesanans.no_resi', 'pelanggans.nama_pelanggan', 'pemesanans.total_bayar', 'pemesanans.tgl_pesan', 'pemesanans.status_pesan')
                ->whereBetween('pemesanans.tgl_pesan', [$start, $end])
                ->orderBy('pemesanans.tgl_pesan')
                ->get();
            $filename = 'laporan_penjualan_admin_'.$range.'_'.now()->format('Ymd_His').'.csv';
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="'.$filename.'"',
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

        Route::get('/settings', fn () => view('dashboard.settings'))->name('settings');
        Route::post('/settings/profile', [WebSettingsController::class, 'profile'])->name('settings.profile');
        Route::post('/settings/password', [WebSettingsController::class, 'password'])->name('settings.password');
        Route::post('/settings/logout-others', [WebSettingsController::class, 'logoutOthers'])->name('settings.logout-others');
        Route::post('/settings/deactivate', [WebSettingsController::class, 'deactivate'])->name('settings.deactivate');
    });

Route::middleware(['auth', 'level:kurir'])
    ->prefix('dashboard/kurir')
    ->name('dashboard.kurir.')
    ->group(function () {
        Route::get('/', fn () => view('dashboard.kurir.kurir'))->name('index');
        Route::post('/pengiriman/pick', [KurirPengirimanController::class, 'pick'])->name('pick');
        Route::post('/pengiriman/finish', [KurirPengirimanController::class, 'finish'])->name('finish');

        Route::get('/settings', fn () => view('dashboard.settings'))->name('settings');
        Route::post('/settings/profile', [WebSettingsController::class, 'profile'])->name('settings.profile');
        Route::post('/settings/password', [WebSettingsController::class, 'password'])->name('settings.password');
        Route::post('/settings/logout-others', [WebSettingsController::class, 'logoutOthers'])->name('settings.logout-others');
        Route::post('/settings/deactivate', [WebSettingsController::class, 'deactivate'])->name('settings.deactivate');
    });

use App\Http\Controllers\CheckoutController;

Route::middleware('auth:pelanggan')
    ->prefix('dashboard/pelanggan')
    ->name('dashboard.pelanggan.')
    ->group(function () {
        Route::get('/', [PelangganDashboardController::class, 'index'])->name('index');
        Route::get('/cart', fn () => view('dashboard.pelanggan.cart'))->name('cart');
        Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout');
        Route::get('/status', fn () => view('dashboard.pelanggan.status'))->name('status');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        Route::get('/settings', [PelangganSettingsController::class, 'settingsView'])->name('settings');
        Route::post('/settings/profile', [PelangganSettingsController::class, 'profile'])->name('settings.profile');
        Route::post('/settings/password', [PelangganSettingsController::class, 'password'])->name('settings.password');
        Route::post('/settings/logout-others', [PelangganSettingsController::class, 'logoutOthers'])->name('settings.logout-others');
        Route::post('/settings/default-address', [PelangganSettingsController::class, 'defaultAddress'])->name('settings.default-address');
        Route::post('/settings/avatar', [PelangganSettingsController::class, 'avatar'])->name('settings.avatar');
        Route::delete('/settings/avatar', [PelangganSettingsController::class, 'removeAvatar'])->name('settings.avatar.remove');
        Route::post('/settings/deactivate', [PelangganSettingsController::class, 'deactivate'])->name('settings.deactivate');
    });
