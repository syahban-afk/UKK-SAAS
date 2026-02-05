@extends('layouts.dashboard', ['title' => 'Status Pesanan'])

@section('menu')
    @include('menus.pelanggan')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        @php
            $orders = \App\Models\pemesanans_model::with(['pengiriman'])
                ->where('id_pelanggan', auth('pelanggan')->id())
                ->orderByDesc('tgl_pesan')
                ->paginate(10);
        @endphp
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <h2 class="card-title">Status Pesanan</h2>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>No Resi</th>
                                <th>Tanggal</th>
                                <th>Status Pesanan</th>
                                <th>Status Pengiriman</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($orders as $o)
                                <tr>
                                    <td class="font-medium">{{ $o->no_resi }}</td>
                                    <td>{{ \Carbon\Carbon::parse($o->tgl_pesan)->format('Y-m-d') }}</td>
                                    <td>
                                        @php
                                            $badge = match ($o->status_pesan) {
                                                'Menunggu Konfirmasi' => 'badge-warning',
                                                'Sedang Diproses' => 'badge-info',
                                                'Menunggu Kurir' => 'badge-secondary',
                                                'Selesai' => 'badge-success',
                                                default => 'badge-ghost',
                                            };
                                        @endphp
                                        <span class="badge {{ $badge }} badge-sm">{{ $o->status_pesan }}</span>
                                    </td>
                                    <td>
                                        @if ($o->pengiriman)
                                            <span class="badge {{ $o->pengiriman->status_kirim === 'Tiba Ditujuan' ? 'badge-success' : 'badge-info' }} badge-sm">
                                                {{ $o->pengiriman->status_kirim }}
                                            </span>
                                        @else
                                            <span class="badge badge-ghost badge-sm">Belum dikirim</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-base-content/70">Tidak ada pesanan</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
@endsection
