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
                    @foreach (['Menunggu Konfirmasi','Sedang Diproses','Menunggu Kurir'] as $st)
                        <option value="{{ $st }}" {{ $statusNow === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
                <button class="btn btn-sm btn-primary" type="submit">Filter</button>
            </form>
        </div>

        @php
            $orders = \App\Models\pemesanans_model::with(['pelanggan','detailPemesanans.paket','pengiriman'])
                ->when(request('status'), fn($q, $st) => $q->where('status_pesan',$st))
                ->orderByDesc('tgl_pesan')->paginate(15);
            $couriers = \App\Models\User::where('level','kurir')->orderBy('name')->get();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="card bg-base-100 border border-base-300"><div class="card-body">
                <p class="text-sm text-base-content/70">Total Pesanan</p>
                <h2 class="text-3xl font-bold">{{ \App\Models\pemesanans_model::count() }}</h2>
            </div></div>
            <div class="card bg-base-100 border border-base-300"><div class="card-body">
                <p class="text-sm text-base-content/70">Sedang Diproses</p>
                <h2 class="text-3xl font-bold">{{ \App\Models\pemesanans_model::where('status_pesan','Sedang Diproses')->count() }}</h2>
            </div></div>
            <div class="card bg-base-100 border border-base-300"><div class="card-body">
                <p class="text-sm text-base-content/70">Menunggu Kurir</p>
                <h2 class="text-3xl font-bold">{{ \App\Models\pemesanans_model::where('status_pesan','Menunggu Kurir')->count() }}</h2>
            </div></div>
            <div class="card bg-base-100 border border-base-300"><div class="card-body">
                <p class="text-sm text-base-content/70">Selesai (Pengiriman)</p>
                <h2 class="text-3xl font-bold">{{ \App\Models\pengirimans_model::where('status_kirim','Tiba Ditujuan')->count() }}</h2>
            </div></div>
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
                                        default => 'badge-success',
                                    };
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $order->no_resi }}</td>
                                    <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
                                    <td>{{ $firstPackage }}</td>
                                    <td><span class="badge {{ $badgeClass }} badge-sm">{{ $order->status_pesan }}</span></td>
                                    <td>
                                        @if ($order->pengiriman)
                                            {{ $order->pengiriman->user->name ?? 'Kurir' }}<br>
                                            <span class="text-xs text-base-content/70">{{ $order->pengiriman->status_kirim }}</span>
                                        @else
                                            <span class="text-xs text-base-content/70">Belum diassign</span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <form method="POST" action="{{ route('dashboard.admin.orders.status') }}">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $order->id }}">
                                                <select name="status_pesan" class="select select-bordered select-xs">
                                                    @foreach (['Menunggu Konfirmasi','Sedang Diproses','Menunggu Kurir'] as $st)
                                                        <option value="{{ $st }}" {{ $order->status_pesan === $st ? 'selected' : '' }}>{{ $st }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-outline btn-xs">Ubah</button>
                                            </form>

                                            <form method="POST" action="{{ route('dashboard.admin.orders.assign') }}">
                                                @csrf
                                                <input type="hidden" name="id_pesan" value="{{ $order->id }}">
                                                <select name="id_user" class="select select-bordered select-xs">
                                                    @foreach ($couriers as $c)
                                                        <option value="{{ $c->id }}" {{ ($order->pengiriman && $order->pengiriman->id_user === $c->id) ? 'selected' : '' }}>
                                                            {{ $c->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="btn btn-outline btn-xs">Assign</button>
                                            </form>

                                            @if ($order->pengiriman && $order->pengiriman->status_kirim !== 'Tiba Ditujuan')
                                                <form method="POST" action="{{ route('dashboard.admin.orders.finish') }}">
                                                    @csrf
                                                    <input type="hidden" name="id_pesan" value="{{ $order->id }}">
                                                    <button type="submit" class="btn btn-success btn-xs">Tiba</button>
                                                </form>
                                            @endif
                                        </div>
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
                <div class="mt-4">{{ $orders->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>
@endsection
