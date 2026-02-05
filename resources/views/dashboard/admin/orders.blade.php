@extends('layouts.dashboard', ['title' => 'Manajemen Pesanan'])

@section('menu')
    @include('menus.admin')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Manajemen Pesanan</h1>
            <form method="GET" class="flex gap-2">
                @php $statusNow = request('status'); @endphp
                <select name="status" class="select select-bordered select-sm">
                    <option value="">Semua Status</option>
                    @foreach (['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir', 'Selesai'] as $st)
                        <option value="{{ $st }}" {{ $statusNow === $st ? 'selected' : '' }}>{{ $st }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-sm bg-orange-600 hover:bg-orange-700" type="submit">Filter</button>
            </form>
        </div>

        @php
            $orders = \App\Models\pemesanans_model::with(['pelanggan', 'detailPemesanans.paket', 'pengiriman'])
                ->when(request('status'), fn($q, $st) => $q->where('status_pesan', $st))
                ->latest()
                ->paginate(15);
            $couriers = \App\Models\User::where('level', 'kurir')->orderBy('name')->get();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-primary/10 p-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17h-11v-14h-2" />
                                <path d="M6 5l14 1l-1 7h-13" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Total Pesanan</p>
                            <h2 class="text-3xl font-bold">{{ \App\Models\pemesanans_model::count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

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
                            <p class="text-sm text-base-content/70">Sedang Diproses</p>
                            <h2 class="text-3xl font-bold">
                                {{ \App\Models\pemesanans_model::where('status_pesan', 'Sedang Diproses')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-info/10 p-3 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-truck">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Menunggu Kurir</p>
                            <h2 class="text-3xl font-bold">
                                {{ \App\Models\pemesanans_model::where('status_pesan', 'Menunggu Kurir')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-success/10 p-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-package-check">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                <path d="M12 12l8 -4.5" />
                                <path d="M12 12l0 9" />
                                <path d="M12 12l-8 -4.5" />
                                <path d="M9 12l2 2l4 -4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Selesai (Pesanan)</p>
                            <h2 class="text-3xl font-bold">
                                {{ \App\Models\pemesanans_model::where('status_pesan', 'Selesai')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No Resi</th>
                                <th>Pelanggan</th>
                                <th>Paket</th>
                                <th>Status</th>
                                <th>Kurir</th>
                                <th class="text-right">Aksi</th>
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
                                        default => 'badge-success',
                                    };
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $order->no_resi }}</td>
                                    <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                                    <td>{{ $firstPackage }}</td>
                                    <td><span class="badge {{ $badgeClass }} badge-sm">{{ $order->status_pesan }}</span>
                                    </td>
                                    <td>
                                        @if ($order->pengiriman)
                                            {{ $order->pengiriman->user->name ?? 'Kurir' }}<br>
                                            <span
                                                class="text-xs text-base-content/70">{{ $order->pengiriman->status_kirim }}</span>
                                        @else
                                            <span class="text-xs text-base-content/70">Belum diassign</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="flex flex-col gap-2">
                                            <!-- Status Change -->
                                            <div class="flex items-center gap-2">
                                                <form method="POST" action="{{ route('dashboard.admin.orders.status') }}"
                                                    class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $order->id }}">
                                                    <select name="status_pesan"
                                                        class="select select-bordered select-xs w-32">
                                                        @foreach (['Menunggu Konfirmasi', 'Sedang Diproses', 'Menunggu Kurir', 'Selesai'] as $st)
                                                            <option value="{{ $st }}"
                                                                {{ $order->status_pesan === $st ? 'selected' : '' }}>
                                                                {{ $st }}</option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-warning btn-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                            height="14" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                                            <path d="M12 12l8 -4.5" />
                                                            <path d="M12 12l0 9" />
                                                            <path d="M12 12l-8 -4.5" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Courier Assignment -->
                                            <div class="flex items-center gap-2">
                                                <form method="POST"
                                                    action="{{ route('dashboard.admin.orders.assign') }}"
                                                    class="flex items-center gap-2">
                                                    @csrf
                                                    <input type="hidden" name="id_pesan" value="{{ $order->id }}">
                                                    <select name="id_user" class="select select-bordered select-xs w-32">
                                                        <option value="">Pilih Kurir</option>
                                                        @foreach ($couriers as $c)
                                                            <option value="{{ $c->id }}"
                                                                {{ $order->pengiriman && $order->pengiriman->id_user === $c->id ? 'selected' : '' }}>
                                                                {{ $c->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-info btn-xs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14"
                                                            height="14" viewBox="0 0 24 24" fill="none"
                                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                            stroke-linejoin="round">
                                                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                            <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                            <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                                            <path d="M17 17h-11v-14h-2" />
                                                            <path d="M6 5l14 1l-1 7h-13" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Detail Button -->
                                            <button type="button" class="btn btn-success text-white btn-xs w-full"
                                                onclick="order_detail_{{ $order->id }}.showModal()">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="icon icon-tabler icons-tabler-outline icon-tabler-invoice">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                                                    <path
                                                        d="M19 12v7a1.78 1.78 0 0 1 -3.1 1.4a1.65 1.65 0 0 0 -2.6 0a1.65 1.65 0 0 1 -2.6 0a1.65 1.65 0 0 0 -2.6 0a1.78 1.78 0 0 1 -3.1 -1.4v-14a2 2 0 0 1 2 -2h7l5 5v4.25" />
                                                </svg>
                                                Detail
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <dialog id="order_detail_{{ $order->id }}" class="modal">
                                    <div class="modal-box rounded-2xl max-w-lg">
                                        <h3 class="font-bold text-lg mb-6">Detail Pesanan</h3>
                                        <div class="space-y-4">
                                            <div class="flex justify-between border-b border-gray-50 pb-2">
                                                <span class="text-gray-500">No Resi</span>
                                                <span class="font-bold text-orange-600">{{ $order->no_resi }}</span>
                                            </div>
                                            <div class="flex justify-between border-b border-gray-50 pb-2">
                                                <span class="text-gray-500">Pelanggan</span>
                                                <span
                                                    class="font-bold">{{ $order->pelanggan->nama_pelanggan ?? '-' }}</span>
                                            </div>
                                            <div class="space-y-1">
                                                <span class="text-gray-500 text-xs uppercase font-bold">Pengiriman</span>
                                                @if ($order->pengiriman)
                                                    <div class="grid grid-cols-2 gap-4 pt-2 text-xs">
                                                        <div>
                                                            <p class="text-gray-400">Status</p>
                                                            <p class="font-bold">{{ $order->pengiriman->status_kirim }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-400">Kurir</p>
                                                            <p class="font-bold">
                                                                {{ $order->pengiriman->user->name ?? '-' }}</p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-400">Tgl Kirim</p>
                                                            <p class="font-bold">
                                                                {{ $order->pengiriman->tgl_kirim ? \Carbon\Carbon::parse($order->pengiriman->tgl_kirim)->format('d M Y H:i') : '-' }}
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p class="text-gray-400">Tgl Tiba</p>
                                                            <p class="font-bold">
                                                                {{ $order->pengiriman->tgl_tiba ? \Carbon\Carbon::parse($order->pengiriman->tgl_tiba)->format('d M Y H:i') : '-' }}
                                                            </p>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-2 pt-2">
                                                        <span class="text-gray-500 text-xs uppercase font-bold">Bukti
                                                            Pengiriman</span>
                                                        @if ($order->pengiriman->bukti_foto)
                                                            <img src="{{ asset('storage/' . $order->pengiriman->bukti_foto) }}"
                                                                alt="Bukti Pengiriman"
                                                                class="rounded-xl border w-full max-h-64 object-cover">
                                                        @else
                                                            <p class="text-sm text-gray-600">Belum ada bukti diunggah.</p>
                                                        @endif
                                                    </div>
                                                @else
                                                    <p class="text-sm text-gray-600">Belum diassign ke kurir.</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="modal-action">
                                            <button type="button" class="btn btn-ghost"
                                                onclick="order_detail_{{ $order->id }}.close()">Tutup</button>
                                        </div>
                                    </div>
                                </dialog>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-base-content/70">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $orders->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
@endsection
