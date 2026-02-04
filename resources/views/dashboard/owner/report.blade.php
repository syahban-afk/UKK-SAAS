@extends('layouts.dashboard', ['title' => 'Dashboard Owner'])

@section('menu')
    @include('menus.owner')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        @php
            $range = request('range', 'bulan'); // hari, minggu, bulan, tahun
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
                ->selectRaw('DATE(tgl_pesan) as tanggal')
                ->selectRaw('SUM(total_bayar) as total')
                ->whereBetween('tgl_pesan', [$start, $end])
                ->groupBy('tanggal')
                ->orderBy('tanggal')
                ->get();
            $map = collect($rows)->keyBy('tanggal')->map(fn($r) => (int) $r->total);
            $labels = [];
            $series = [];
            $grandTotal = 0;
            for ($d = $start->copy(); $d->lte($end); $d->addDay()) {
                $labels[] = $d->format('d M');
                $val = $map[$d->toDateString()] ?? 0;
                $grandTotal += $val;
                $series[] = round($val / 1000000, 2);
            }

            $topMenu = \Illuminate\Support\Facades\DB::table('detail_pemesanans')
                ->join('pemesanans', 'pemesanans.id', '=', 'detail_pemesanans.id_pemesanan')
                ->join('pakets', 'pakets.id', '=', 'detail_pemesanans.id_paket')
                ->whereBetween('pemesanans.tgl_pesan', [$start, $end])
                ->select('pakets.nama_paket')
                ->selectRaw('COUNT(*) as pesanan')
                ->selectRaw('SUM(detail_pemesanans.subtotal) as omzet')
                ->groupBy('pakets.nama_paket')
                ->orderByDesc('pesanan')
                ->limit(10)
                ->get();
            $ordersCount = \Illuminate\Support\Facades\DB::table('pemesanans')
                ->whereBetween('tgl_pesan', [$start, $end])
                ->count();
            $avgOmzet = $ordersCount > 0 ? intdiv($grandTotal, $ordersCount) : 0;
            $uniqueCustomers = \Illuminate\Support\Facades\DB::table('pemesanans')
                ->whereBetween('tgl_pesan', [$start, $end])
                ->distinct('id_pelanggan')
                ->count('id_pelanggan');
        @endphp

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Laporan Penjualan</h1>
            <div class="flex items-center gap-2">
                <form method="GET" class="flex gap-2">
                    <select name="range" class="select select-bordered select-sm">
                        @php $rangeNow = request('range', 'bulan'); @endphp
                        <option value="hari" {{ $rangeNow === 'hari' ? 'selected' : '' }}>Hari ini</option>
                        <option value="minggu" {{ $rangeNow === 'minggu' ? 'selected' : '' }}>1 Minggu</option>
                        <option value="bulan" {{ $rangeNow === 'bulan' ? 'selected' : '' }}>1 Bulan</option>
                        <option value="tahun" {{ $rangeNow === 'tahun' ? 'selected' : '' }}>1 Tahun</option>
                    </select>
                    <button class="btn btn-sm btn-outline" type="submit">Terapkan</button>
                    <a class="btn btn-sm bg-orange-600 hover:bg-orange-700"
                       href="{{ route('dashboard.owner.report.export', ['range' => $rangeNow]) }}">
                        Export CSV
                    </a>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-success/10 p-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M12 1v22" />
                                <path d="M17 5h-7a4 4 0 0 0 0 8h4a4 4 0 0 1 0 8h-7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Total Omzet</p>
                            <h2 class="text-2xl font-bold">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-primary/10 p-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 7h18" />
                                <path d="M5 7v14" />
                                <path d="M19 7v14" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Total Pesanan</p>
                            <h2 class="text-2xl font-bold">{{ $ordersCount }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M6 7h12" />
                                <path d="M6 11h12" />
                                <path d="M6 15h12" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Rata-rata/Pesanan</p>
                            <h2 class="text-2xl font-bold">Rp {{ number_format($avgOmzet, 0, ',', '.') }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-info/10 p-3 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M7 11a4 4 0 1 0 0-8a4 4 0 0 0 0 8z" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Pelanggan Unik</p>
                            <h2 class="text-2xl font-bold">{{ $uniqueCustomers }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <div class="h-64">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <h2 class="card-title mb-4">Menu Terlaris</h2>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Paket</th>
                                <th>Jumlah Pesanan</th>
                                <th>Omzet</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topMenu as $row)
                                <tr>
                                    <td>{{ $row->nama_paket }}</td>
                                    <td>{{ $row->pesanan }}</td>
                                    <td>Rp {{ number_format($row->omzet, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center text-base-content/70">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.ownerData = {
            revenue: {
                labels: @json($labels),
                data: @json($series)
            }
        };
    </script>
    @vite(['resources/js/owner.js'])
@endsection
