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
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-primary/10 p-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M3 21h18" />
                                <path d="M3 7h18" />
                                <path d="M5 7v14" />
                                <path d="M19 7v14" />
                                <path d="M8 7v14" />
                                <path d="M16 7v14" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Pesanan</p>
                            <h2 class="text-3xl font-bold">1.245</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pendapatan -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-success/10 p-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M12 1v22" />
                                <path d="M17 5h-7a4 4 0 0 0 0 8h4a4 4 0 0 1 0 8h-7" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pendapatan</p>
                            <h2 class="text-3xl font-bold">Rp 125 JT</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pesanan Aktif -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v5l3 3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pesanan Aktif</p>
                            <h2 class="text-3xl font-bold">42</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paket Terlaris -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-info/10 p-3 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path
                                    d="M12 17.75l-6.172 3.245 1.179-6.873L2 9.755l6.914-1.004L12 2.5l3.086 6.251L22 9.755l-4.997 4.367 1.179 6.873z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Paket Terlaris</p>
                            <h2 class="text-xl font-semibold">Pernikahan</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pelanggan -->
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-secondary/10 p-3 text-secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M7 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                                <path d="M17 13a4 4 0 1 0 0-8 4 4 0 0 0 0 8z" />
                                <path d="M3 21v-2a4 4 0 0 1 4-4h3" />
                                <path d="M14 17h3a4 4 0 0 1 4 4v2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Pelanggan</p>
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
                                    <th class="text-right">Aksi</th>
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
                                        <td class="text-right">
                                            <div class="flex justify-end gap-2">
                                                <button class="btn btn-ghost btn-xs">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="currentColor"
                                                        class="icon icon-tabler icons-tabler-filled icon-tabler-eye">
                                                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                        <path
                                                            d="M12 4c4.29 0 7.863 2.429 10.665 7.154l.22 .379l.045 .1l.03 .083l.014 .055l.014 .082l.011 .1v.11l-.014 .111a.992 .992 0 0 1 -.026 .11l-.039 .108l-.036 .075l-.016 .03c-2.764 4.836 -6.3 7.38 -10.555 7.499l-.313 .004c-4.396 0 -8.037 -2.549 -10.868 -7.504a1 1 0 0 1 0 -.992c2.831 -4.955 6.472 -7.504 10.868 -7.504zm0 5a3 3 0 1 0 0 6a3 3 0 0 0 0 -6" />
                                                    </svg>
                                                </button>
                                            </div>
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
            (int) $doneCount,
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
