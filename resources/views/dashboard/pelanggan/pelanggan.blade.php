@extends('layouts.dashboard', ['title' => 'Dashboard Pelanggan'])

@section('menu')
    @include('menus.pelanggan')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">


        <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ $pl->nama_pelanggan ?? 'Pengunjung' }}!</h1>
                <p class="text-gray-500">Pesan catering favorit Anda dengan mudah.</p>
            </div>
            @if (!empty($pl))
                <button onclick="profile_modal.showModal()" class="btn btn-ghost btn-circle avatar">
                    <div class="w-12 rounded-full ring ring-orange-600 ring-offset-base-100 ring-offset-2">
                        <img
                            src="{{ $pl->foto ? asset('storage/' . $pl->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($pl->nama_pelanggan) . '&color=7F9CF5&background=EBF4FF' }}" />
                    </div>
                </button>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
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
                            <h2 class="text-3xl font-bold">{{ $metrics['totalOrders'] ?? 0 }}</h2>
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
                            <p class="text-sm text-base-content/70">Pesanan Aktif</p>
                            <h2 class="text-3xl font-bold">{{ $metrics['activeOrders'] ?? 0 }}</h2>
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
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-package-check">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                <path d="M12 12l8 -4.5" />
                                <path d="M12 12l0 9" />
                                <path d="M12 12l-8 -4.5" />
                                <path d="M9 12l2 2l4 -4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Terkirim</p>
                            <h2 class="text-3xl font-bold">{{ $metrics['deliveredCount'] ?? 0 }}</h2>
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
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-receipt">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 21v-16l2 2l2 -2l2 2l2 -2l2 2l2 -2v16l-2 -2l-2 2l-2 -2l-2 2l-2 -2z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Pesanan Terakhir</p>
                            <div class="text-sm font-semibold">
                                @if (!empty($lastOrder))
                                    {{ $lastOrder->no_resi }} • {{ $lastOrder->status_pesan }}
                                @else
                                    Belum ada
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Rekomendasi Menu</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse ($recommendedPakets as $p)
                            <div class="group bg-white rounded-xl overflow-hidden border border-gray-100 hover:shadow-md transition">
                                <div class="relative h-40 overflow-hidden">
                                    <img src="{{ $p->foto1 }}" alt="{{ $p->nama_paket }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500" />
                                    <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-1 rounded-lg text-xs font-bold text-orange-600">
                                        Rp {{ number_format($p->harga_paket, 0, ',', '.') }}
                                    </div>
                                </div>
                                <div class="p-4">
                                    <h3 class="font-bold text-gray-900 truncate">{{ $p->nama_paket }}</h3>
                                    <p class="text-xs text-gray-500 mb-4">{{ $p->jenis }} • {{ $p->kategori }}</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <a href="{{ route('menu.public') }}?search={{ urlencode($p->nama_paket) }}" class="btn btn-outline btn-xs">Lihat</a>
                                        <a href="{{ route('menu.public') }}" class="btn bg-orange-600 hover:bg-orange-700 text-white btn-xs">Menu</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12 text-gray-500">Belum ada rekomendasi</div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6">Metode Pembayaran</h2>
                    <div class="space-y-3">
                        @forelse ($paymentMethods as $m)
                            <div class="border border-gray-100 rounded-xl p-4">
                                <div class="font-semibold text-gray-900 mb-2">{{ $m->metode_pembayaran }}</div>
                                <div class="grid grid-cols-1 gap-2">
                                    @forelse ($m->detailJenisPembayarans as $d)
                                        <div class="flex items-center justify-between bg-gray-50 rounded-lg p-3">
                                            <div class="flex items-center gap-3">
                                                <img src="{{ $d->logo }}" alt="{{ $d->tempat_bayar }}" class="h-6 w-6 object-contain">
                                                <div class="text-sm text-gray-700">{{ $d->tempat_bayar }}</div>
                                            </div>
                                            <div class="text-xs font-mono text-gray-500">{{ $d->no_rek }}</div>
                                        </div>
                                    @empty
                                        <div class="text-gray-400 text-sm">Tidak ada detail</div>
                                    @endforelse
                                </div>
                            </div>
                        @empty
                            <div class="text-gray-400">Metode pembayaran belum tersedia</div>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">Aksi Cepat</h2>
                    <div class="grid grid-cols-2 gap-2">
                        <a href="{{ route('menu.public') }}" class="btn">Lihat Menu</a>
                        <a href="{{ route('dashboard.pelanggan.settings') }}" class="btn btn-outline">Edit Profil</a>
                    </div>
                </div>
            </div>
        </div>

        @if (!empty($pl))
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Riwayat Pesanan</h2>
                <div class="overflow-x-auto">
                    <table class="table w-full">
                        <thead>
                            <tr class="text-gray-400 text-xs uppercase">
                                <th>No Resi</th>
                                <th>Tanggal</th>
                                <th>Status Pesanan</th>
                                <th>Status Pengiriman</th>
                                <th class="text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse ($orders as $o)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="font-bold text-orange-600">{{ $o->no_resi }}</td>
                                    <td>{{ $o->tgl_pesan_fmt }}</td>
                                    <td>
                                        <span
                                            class="px-2 py-1 rounded-full text-xs font-bold {{ $o->badgeClass }}">{{ $o->status_pesan }}</span>
                                    </td>
                                    <td>
                                        @if ($o->pengiriman)
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-bold {{ $o->pengirimanBadgeClass }}">
                                                {{ $o->pengiriman->status_kirim }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 italic">Belum dikirim</span>
                                        @endif
                                    </td>
                                    <td class="text-right font-bold">Rp {{ number_format($o->total_bayar, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-gray-500">Belum ada pesanan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">{{ $orders->links() }}</div>
            </div>
        @endif
    </div>
@endsection
