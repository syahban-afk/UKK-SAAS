@extends('layouts.dashboard', ['title' => 'Dashboard Owner'])

@section('menu')
    @include('menus.owner')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Monitoring Pesanan</h1>
            <div class="flex gap-2">
                <form method="GET" class="flex gap-2">
                    <select name="status" class="select select-bordered select-sm">
                        @php
                            $statuses = ['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir', 'Selesai'];
                            $currentStatus = request('status');
                        @endphp
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $st)
                            <option value="{{ $st }}" {{ $currentStatus === $st ? 'selected' : '' }}>{{ $st }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-sm bg-orange-600 hover:bg-orange-700 text-white" type="submit">Filter</button>
                </form>
            </div>
        </div>

        @php
            $statusAgg = \Illuminate\Support\Facades\DB::table('pemesanans')
                ->select('status_pesan', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
                ->groupBy('status_pesan')
                ->pluck('total', 'status_pesan')
                ->toArray();
            $doneAgg = \Illuminate\Support\Facades\DB::table('pengirimans')
                ->where('status_kirim', 'Tiba Ditujuan')
                ->count();
            $totalAgg = array_sum($statusAgg);
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
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
                            <h2 class="text-3xl font-bold">{{ $totalAgg }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="9" />
                                <path d="M12 7v5l3 3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Menunggu Konfirmasi</p>
                            <h2 class="text-3xl font-bold">{{ (int) ($statusAgg['Menunggu Konfirmasi'] ?? 0) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-info/10 p-3 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 12h18" />
                                <path d="M12 3v18" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Sedang Diproses</p>
                            <h2 class="text-3xl font-bold">{{ (int) ($statusAgg['Sedang Diproses'] ?? 0) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-success/10 p-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M5 12l5 5l10 -10" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Selesai</p>
                            <h2 class="text-3xl font-bold">{{ (int) $doneAgg }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                @php
                    $orders = \App\Models\pemesanans_model::with(['pelanggan', 'detailPemesanans.paket', 'pengiriman'])
                        ->when(request('status'), fn ($q, $st) => $q->where('status_pesan', $st))
                        ->orderByDesc('tgl_pesan')
                        ->paginate(15);
                @endphp

                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No Resi</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Tgl Pesan</th>
                                <th>Status</th>
                                <th>Pengiriman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $order)
                                @php
                                    $firstPackage = $order->detailPemesanans->first()?->paket?->nama_paket ?? '-';
                                    $badgeClass = match ($order->status_pesan) {
                                        'Menunggu Konfirmasi' => 'badge-warning',
                                        'Sedang Diproses' => 'badge-info',
                                        'Menunggu Kurir' => 'badge-secondary',
                                        default => 'badge-success',
                                    };
                                    $pengiriman = $order->pengiriman;
                                    $kirimInfo = $pengiriman
                                        ? ($pengiriman->status_kirim === 'Tiba Ditujuan'
                                            ? 'Tiba: ' . \Carbon\Carbon::parse($pengiriman->tgl_tiba)->format('d M Y')
                                            : 'Kirim: ' . \Carbon\Carbon::parse($pengiriman->tgl_kirim)->format('d M Y'))
                                        : '-';
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $order->no_resi }}</td>
                                    <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                                    <td>{{ $firstPackage }}</td>
                                    <td>{{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d M Y') }}</td>
                                    <td>
                                        <span class="badge {{ $badgeClass }} badge-sm">{{ $order->status_pesan }}</span>
                                    </td>
                                    <td>{{ $kirimInfo }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-base-content/70">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $orders->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </div>
@endsection
