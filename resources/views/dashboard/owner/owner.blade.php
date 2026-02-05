@extends('layouts.dashboard', ['title' => 'Dashboard Owner'])

@section('menu')
    @include('menus.owner')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    {{-- Dashboard Content --}}
    <div class="space-y-6">

        <!-- KPI CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4">

            <!-- Total Pesanan -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-primary/10 p-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17h-11v-14h-2" />
                                <path d="M6 5l14 1l-1 7h-13" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Total Pesanan</p>
                            <h2 class="text-3xl font-bold">1.245</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendapatan -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-success/10 p-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-currency-dollar">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M16.7 8a3 3 0 0 0 -2.7 -2h-4a3 3 0 0 0 0 6h4a3 3 0 0 1 0 6h-4a3 3 0 0 1 -2.7 -2" />
                                <path d="M12 3v3m0 12v3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Pendapatan</p>
                            <h2 class="text-3xl font-bold">Rp 125 JT</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Aktif -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 7v5l3 3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Pesanan Aktif</p>
                            <h2 class="text-3xl font-bold">42</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paket Terlaris -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-info/10 p-3 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-star">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 17.75l-6.172 3.245l1.179-6.873L2 9.755l6.914-1.004L12 2.5l3.086 6.251L22 9.755l-4.997 4.367 1.179 6.873z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Paket Terlaris</p>
                            <h2 class="text-xl font-semibold">Pernikahan</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pelanggan -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-secondary/10 p-3 text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Pelanggan</p>
                            <h2 class="text-3xl font-bold">320</h2>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">

            <!-- SUMMARY -->
            <div class="card bg-base-100 border border-base-300 lg:col-span-1">
                <div class="card-body">
                    <canvas id="summaryChart"></canvas>
                </div>
            </div>

            <!-- MAIN CHART -->
            <div class="card bg-base-100 border border-base-300 lg:col-span-3">
                <div class="card-body">
                    <div class="h-64 flex items-center justify-center">
                        <canvas id="revenueChart" height="60"></canvas>
                        <div id="chartColors" class="hidden">
                            <span class="bg-primary"></span>
                            <span class="bg-secondary"></span>
                        </div>
                    </div>
                </div>
            </div>

        </div>


        <!-- BOTTOM SECTION -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

            <!-- TABLE -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title mb-4">Pesanan Terbaru</h2>

                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <!-- head -->
                            <thead class="text-base-content/70">
                                <tr>
                                    <th>#</th>
                                    <th>Pelanggan</th>
                                    <th>Paket</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @php
                                    $latestOrders = \App\Models\pemesanans_model::with(['pelanggan', 'detailPemesanans.paket'])
                                        ->orderByDesc('tgl_pesan')
                                        ->limit(10)
                                        ->get();
                                @endphp
                                @forelse ($latestOrders as $order)
                                    @php
                                        $badgeClass = match ($order->status_pesan) {
                                            'Menunggu Konfirmasi' => 'badge-warning',
                                            'Sedang Diproses' => 'badge-info',
                                            'Menunggu Kurir' => 'badge-secondary',
                                            'Selesai' => 'badge-success',
                                            default => 'badge-ghost',
                                        };
                                        $firstPackage = $order->detailPemesanans->first()?->paket?->nama_paket ?? '-';
                                    @endphp
                                    <tr class="hover">
                                        <td>
                                            <a href="#" class="link link-primary font-medium">
                                                {{ $order->no_resi }}
                                            </a>
                                        </td>
                                        <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                                        <td>{{ $firstPackage }}</td>
                                        <td>
                                            <span class="badge {{ $badgeClass }} badge-sm gap-1">
                                                {{ $order->status_pesan }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-base-content/70">Belum ada pesanan</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- TOP PACKAGE -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex gap-2 mb-4">
                        <button class="btn btn-sm btn-outline" onclick="updateChart('3hari')">3 Hari</button>
                        <button class="btn btn-sm btn-outline" onclick="updateChart('minggu')">1 Minggu</button>
                        <button class="btn btn-sm btn-outline" onclick="updateChart('bulan')">1 Bulan</button>
                        <button class="btn btn-sm btn-outline" onclick="updateChart('tahun')">1 Tahun</button>
                    </div>

                    <canvas id="pkgChart"></canvas>

                </div>

            </div>

        </div>
    @endsection

    <script>
        function updateChart(range) {
            window.pkgChart.data.datasets[0].data = window.chartDataByTime[range];

            const titleMap = {
                '3hari': 'Paket Terlaris (3 Hari Terakhir)',
                'minggu': 'Paket Terlaris (1 Minggu)',
                'bulan': 'Paket Terlaris (1 Bulan)',
                'tahun': 'Paket Terlaris (1 Tahun)'
            };

            window.pkgChart.options.plugins.title.text = titleMap[range];
            window.pkgChart.update();
        }
    </script>

    @php
        $now = \Carbon\Carbon::now();
        $end = $now->copy()->endOfDay();
        $start30 = $now->copy()->subDays(29)->startOfDay();
        $rows30 = \Illuminate\Support\Facades\DB::table('pemesanans')
            ->selectRaw('DATE(tgl_pesan) as tanggal')
            ->selectRaw('SUM(total_bayar) as total')
            ->whereBetween('tgl_pesan', [$start30, $end])
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();
        $map30 = collect($rows30)->keyBy('tanggal')->map(fn($r) => (int) $r->total);
        $revLabels = [];
        $revSeries = [];
        for ($d = $start30->copy(); $d->lte($end); $d->addDay()) {
            $revLabels[] = $d->format('d M');
            $revSeries[] = round(($map30[$d->toDateString()] ?? 0) / 1000000, 2);
        }
        $ordersStatus = \Illuminate\Support\Facades\DB::table('pemesanans')
            ->select('status_pesan', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('status_pesan')
            ->pluck('total', 'status_pesan')
            ->toArray();
        $doneCount = \Illuminate\Support\Facades\DB::table('pengirimans')
            ->where('status_kirim', 'Tiba Ditujuan')
            ->count();
        $summaryLabels = ['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir', 'Selesai'];
        $summarySeries = [
            (int) ($ordersStatus['Menunggu Konfirmasi'] ?? 0),
            (int) ($ordersStatus['Sedang Diproses'] ?? 0),
            (int) ($ordersStatus['Menunggu Kurir'] ?? 0),
            (int) ($ordersStatus['Selesai'] ?? 0),
        ];
        $start365 = $now->copy()->subDays(365)->startOfDay();
        $topYear = \Illuminate\Support\Facades\DB::table('detail_pemesanans')
            ->join('pemesanans', 'pemesanans.id', '=', 'detail_pemesanans.id_pemesanan')
            ->join('pakets', 'pakets.id', '=', 'detail_pemesanans.id_paket')
            ->whereBetween('pemesanans.tgl_pesan', [$start365, $end])
            ->select('pakets.id', 'pakets.nama_paket', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
            ->groupBy('pakets.id', 'pakets.nama_paket')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        $pkgIds = $topYear->pluck('id')->all();
        $pkgLabels = $topYear->pluck('nama_paket')->all();
        $rangeCounts = function (\Carbon\Carbon $start) use ($end, $pkgIds) {
            $rows = \Illuminate\Support\Facades\DB::table('detail_pemesanans')
                ->join('pemesanans', 'pemesanans.id', '=', 'detail_pemesanans.id_pemesanan')
                ->select('detail_pemesanans.id_paket', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
                ->whereBetween('pemesanans.tgl_pesan', [$start, $end])
                ->groupBy('detail_pemesanans.id_paket')
                ->get();
            $map = collect($rows)->keyBy('id_paket')->map(fn($r) => (int) $r->total);
            return array_map(fn($id) => (int) ($map[$id] ?? 0), $pkgIds);
        };
        $arr3 = $rangeCounts($now->copy()->subDays(2)->startOfDay());
        $arr7 = $rangeCounts($now->copy()->subDays(6)->startOfDay());
        $arr30 = $rangeCounts($now->copy()->subDays(29)->startOfDay());
        $arr365 = $rangeCounts($start365);
    @endphp
    <script>
        window.ownerData = {
            revenue: {
                labels: @json($revLabels),
                data: @json($revSeries)
            },
            summary: {
                labels: @json($summaryLabels),
                data: @json($summarySeries)
            },
            pkg: {
                labels: @json($pkgLabels)
            },
        };
        window.chartDataByTime = {
            '3hari': @json($arr3),
            'minggu': @json($arr7),
            'bulan': @json($arr30),
            'tahun': @json($arr365),
        };
    </script>
    @vite(['resources/js/owner.js'])
