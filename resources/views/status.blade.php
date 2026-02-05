@extends('layouts.dashboard', ['title' => 'Cek Status Pesanan'])

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h1 class="text-2xl font-bold text-gray-900 mb-4">Cek Status Pesanan</h1>
            <form method="GET" action="{{ url('/status') }}" class="flex gap-2">
                <input type="text" name="resi" value="{{ $resi }}" class="input input-bordered flex-1" placeholder="Masukkan No Resi" required>
                <button class="btn bg-orange-600 hover:bg-orange-700 text-white" type="submit">Cari</button>
            </form>
        </div>

        @if ($resi)
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                @if ($order)
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-sm text-gray-500">No Resi</div>
                                <div class="text-xl font-bold text-orange-600">{{ $order->no_resi }}</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-500">Pelanggan</div>
                                <div class="font-semibold text-gray-900">{{ $order->pelanggan->nama_pelanggan ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="border border-gray-100 rounded-xl p-4">
                                <div class="text-sm text-gray-500">Tanggal Pesan</div>
                                <div class="font-semibold">{{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d M Y') }}</div>
                            </div>
                            <div class="border border-gray-100 rounded-xl p-4">
                                <div class="text-sm text-gray-500">Status Pesanan</div>
                                @php
                                    $badgeClass = match ($order->status_pesan) {
                                        'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-700',
                                        'Sedang Diproses' => 'bg-blue-100 text-blue-700',
                                        'Menunggu Kurir' => 'bg-purple-100 text-purple-700',
                                        default => 'bg-gray-100 text-gray-700',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">{{ $order->status_pesan }}</span>
                            </div>
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <div class="text-sm text-gray-500">Status Pengiriman</div>
                            @if ($order->pengiriman)
                                <div class="font-semibold">
                                    {{ $order->pengiriman->status_kirim }}
                                    @if ($order->pengiriman->status_kirim === 'Tiba Ditujuan' && $order->pengiriman->tgl_tiba)
                                        • {{ \Carbon\Carbon::parse($order->pengiriman->tgl_tiba)->format('d M Y') }}
                                    @elseif ($order->pengiriman->tgl_kirim)
                                        • {{ \Carbon\Carbon::parse($order->pengiriman->tgl_kirim)->format('d M Y') }}
                                    @endif
                                </div>
                                @if ($order->pengiriman->bukti_foto)
                                    <div class="mt-3">
                                        <div class="text-sm text-gray-500 mb-2">Bukti Pengiriman</div>
                                        <img src="{{ asset('storage/' . $order->pengiriman->bukti_foto) }}" alt="Bukti Pengiriman" class="rounded-xl border w-full max-h-64 object-cover">
                                    </div>
                                @endif
                            @else
                                <div class="text-gray-400">Belum dikirim</div>
                            @endif
                        </div>
                        <div class="border border-gray-100 rounded-xl p-4">
                            <div class="text-sm text-gray-500 mb-2">Item Pesanan</div>
                            <div class="space-y-2">
                                @forelse ($order->detailPemesanans as $d)
                                    <div class="flex items-center justify-between">
                                        <div class="font-semibold text-gray-900">{{ $d->paket->nama_paket ?? '-' }}</div>
                                        <div class="text-gray-600">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</div>
                                    </div>
                                @empty
                                    <div class="text-gray-400">Tidak ada item</div>
                                @endforelse
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="text-gray-500">Total Bayar</div>
                            <div class="text-xl font-bold text-gray-900">Rp {{ number_format($order->total_bayar, 0, ',', '.') }}</div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-gray-500">Pesanan dengan resi tersebut tidak ditemukan.</div>
                @endif
            </div>
        @endif
    </div>
@endsection
