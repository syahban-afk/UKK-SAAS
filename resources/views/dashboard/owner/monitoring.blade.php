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
                            <option value="{{ $st }}" {{ $currentStatus === $st ? 'selected' : '' }}>
                                {{ $st }}</option>
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M6 6h15l-1.5 9h-12z" />
                                <path d="M6 6l-2 9h16" />
                                <path d="M9 20a1 1 0 1 0 0 -2a1 1 0 0 0 0 2" />
                                <path d="M17 20a1 1 0 1 0 0 -2a1 1 0 0 0 0 2" />
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 6v6" />
                                <path d="M12 18h.01" />
                                <path d="M5 19h14l-7 -15z" />
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M12 6l-8 8h16z" />
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
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
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
                        ->when(request('status'), fn($q, $st) => $q->where('status_pesan', $st))
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
                                <th>Aksi</th>
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
                                        'Selesai' => 'badge-success',
                                        default => 'badge-default',
                                    };
                                    $pengiriman = $order->pengiriman;
                                    $kirimInfo = $pengiriman
                                        ? ($pengiriman->status_kirim === 'Tiba Ditujuan'
                                            ? 'Tiba: ' . \Carbon\Carbon::parse($pengiriman->tgl_tiba)->format('d M Y')
                                            : 'Kirim: ' .
                                                \Carbon\Carbon::parse($pengiriman->tgl_kirim)->format('d M Y'))
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
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span>{{ $kirimInfo }}</span>

                                        </div>
                                        @if ($pengiriman && $pengiriman->bukti_foto)
                                            <dialog id="proof_{{ $order->id }}" class="modal">
                                                <div class="modal-box rounded-2xl max-w-lg">
                                                    <h3 class="font-bold text-lg mb-4">Bukti Pengiriman</h3>
                                                    <img src="{{ asset('storage/' . $pengiriman->bukti_foto) }}"
                                                        alt="Bukti Pengiriman"
                                                        class="rounded-xl border w-full max-h-[70vh] object-cover">
                                                    <div class="modal-action">
                                                        <button type="button" class="btn btn-ghost"
                                                            onclick="proof_{{ $order->id }}.close()">Tutup</button>
                                                    </div>
                                                </div>
                                            </dialog>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pengiriman && $pengiriman->bukti_foto)
                                            <button type="button" class="btn btn-success text-white btn-xs"
                                                onclick="proof_{{ $order->id }}.showModal()">Lihat Bukti</button>
                                        @endif
                                    </td>
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
