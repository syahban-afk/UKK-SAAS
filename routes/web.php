<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersManagementController;
use App\Models\pakets_model;
use App\Models\User;
use App\Models\pemesanans_model;
use App\Models\pengirimans_model;

use App\Http\Controllers\LandingPageController;

Route::get('/', [LandingPageController::class, 'index']);

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

        Route::get('/settings', fn() => view('dashboard.settings'))->name('settings');
        Route::post('/settings/profile', function (Request $request) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:30'],
                'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
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
        Route::get('/', fn() => view('dashboard.admin.admin'))->name('index');
        Route::get('/menu', fn() => view('dashboard.admin.menu'))->name('menu');
        Route::post('/menu', function (Request $request) {
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
            pakets_model::create($validated);
            return back()->with('status', 'Menu berhasil dibuat.');
        })->name('menu.store');
        Route::put('/menu/{id}', function (Request $request, $id) {
            $paket = pakets_model::findOrFail($id);
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
            $paket = pakets_model::findOrFail($id);
            $paket->delete();
            return back()->with('status', 'Menu berhasil dihapus.');
        })->name('menu.destroy');

        Route::get('/orders', fn() => view('dashboard.admin.orders'))->name('orders');
        Route::post('/orders/status', function (Request $request) {
            $validated = $request->validate([
                'id' => ['required', 'integer', 'exists:pemesanans,id'],
                'status_pesan' => ['required', 'string', 'in:Menunggu Konfirmasi,Sedang Diproses,Menunggu Kurir'],
            ]);
            /** @var \App\Models\pemesanans_model $order */
            $order = pemesanans_model::findOrFail($validated['id']);
            $order->update(['status_pesan' => $validated['status_pesan']]);
            return back()->with('status', 'Status pesanan diubah.');
        })->name('orders.status');
        Route::post('/orders/assign', function (Request $request) {
            $validated = $request->validate([
                'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
                'id_user' => ['required', 'integer', 'exists:users,id'],
            ]);
            pengirimans_model::updateOrCreate(
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
        Route::post('/orders/finish', function (Request $request) {
            $validated = $request->validate([
                'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
            ]);
            /** @var \App\Models\pengirimans_model|null $pengiriman */
            $pengiriman = pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
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

        Route::get('/settings', fn() => view('dashboard.settings'))->name('settings');
        Route::post('/settings/profile', function (Request $request) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:30'],
                'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
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

Route::middleware(['auth', 'level:kurir'])
    ->prefix('dashboard/kurir')
    ->name('dashboard.kurir.')
    ->group(function () {
        Route::get('/', fn() => view('dashboard.kurir.kurir'))->name('index');
        Route::post('/pengiriman/pick', function (Request $request) {
            $validated = $request->validate([
                'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
            ]);
            $user = Auth::user();
            /** @var \App\Models\pengirimans_model|null $pengiriman */
            $pengiriman = pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
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
        Route::post('/pengiriman/finish', function (Request $request) {
            $validated = $request->validate([
                'id_pesan' => ['required', 'integer', 'exists:pemesanans,id'],
            ]);
            $user = Auth::user();
            /** @var \App\Models\pengirimans_model|null $pengiriman */
            $pengiriman = pengirimans_model::where('id_pesan', $validated['id_pesan'])->first();
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

        Route::get('/settings', fn() => view('dashboard.settings'))->name('settings');
        Route::post('/settings/profile', function (Request $request) {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:30'],
                'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($user->id)],
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

use App\Http\Controllers\CheckoutController;

Route::middleware('auth:pelanggan')
    ->prefix('dashboard/pelanggan')
    ->name('dashboard.pelanggan.')
    ->group(function () {
        Route::get('/', fn() => view('dashboard.pelanggan.pelanggan'))->name('index');
        Route::get('/menu', fn() => view('dashboard.pelanggan.menu'))->name('menu');
        Route::get('/cart', fn() => view('dashboard.pelanggan.cart'))->name('cart');
        Route::get('/checkout', fn() => view('dashboard.pelanggan.checkout'))->name('checkout');
        Route::get('/status', fn() => view('dashboard.pelanggan.status'))->name('status');
        Route::get('/info', fn() => view('dashboard.pelanggan.info'))->name('info');
        Route::get('/promo', fn() => view('dashboard.pelanggan.promo'))->name('promo');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        Route::get('/settings', fn() => view('dashboard.pelanggan.settings'))->name('settings');
        Route::post('/settings/profile', function (Request $request) {
            /** @var \App\Models\pelanggans_model $pl */
            $pl = auth('pelanggan')->user();
            $validated = $request->validate([
                'nama_pelanggan' => ['required', 'string', 'max:100'],
                'email' => ['required', 'string', 'email', 'max:255', \Illuminate\Validation\Rule::unique('pelanggans', 'email')->ignore($pl->id)],
                'telepon' => ['required', 'string', 'max:15'],
                'alamat1' => ['nullable', 'string', 'max:255'],
                'alamat2' => ['nullable', 'string', 'max:255'],
                'alamat3' => ['nullable', 'string', 'max:255'],
            ]);
            $pl->update($validated);
            return back()->with('status', 'Profil pelanggan diperbarui.');
        })->name('settings.profile');
        Route::post('/settings/password', function (Request $request) {
            /** @var \App\Models\pelanggans_model $pl */
            $pl = auth('pelanggan')->user();
            $validated = $request->validate([
                'password' => ['required', 'string', 'min:6', 'confirmed'],
            ]);
            $pl->update(['password' => $validated['password']]);
            return back()->with('status', 'Password diubah.');
        })->name('settings.password');
        Route::post('/settings/logout-others', function () {
            /** @var \App\Models\pelanggans_model $pl */
            $pl = auth('pelanggan')->user();
            DB::table('sessions')->where('user_id', $pl->id)->delete();
            return back()->with('status', 'Logout dari semua perangkat berhasil.');
        })->name('settings.logout-others');
        Route::post('/settings/default-address', function (Request $request) {
            /** @var \App\Models\pelanggans_model $pl */
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
        })->name('settings.default-address');
        Route::post('/settings/deactivate', function (Request $request) {
            DB::table('sessions')->where('user_id', auth('pelanggan')->id())->delete();
            /** @var \Illuminate\Contracts\Auth\StatefulGuard $guard */
            $guard = auth('pelanggan');
            $guard->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/');
        })->name('settings.deactivate');
    });
