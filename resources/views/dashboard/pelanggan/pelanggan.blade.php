@extends('layouts.dashboard', ['title' => 'Dashboard Pelanggan'])

@section('menu')
    @include('menus.pelanggan')
@endsection

@section('content')
    <div class="space-y-6">
        @php
            $pl = auth('pelanggan')->user();
            $kategoriNow = request('kategori');
            $pakets = \App\Models\pakets_model::when($kategoriNow, fn($q) => $q->where('kategori', $kategoriNow))
                ->orderBy('nama_paket')
                ->paginate(12);
            $orders = \App\Models\pemesanans_model::with(['pengiriman'])
                ->where('id_pelanggan', $pl->id)
                ->orderByDesc('tgl_pesan')
                ->paginate(10);
            $kategoris = ['Pernikahan','Selamatan','Ulang Tahun','Studi Tour','Rapat'];
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            <div class="card bg-base-100 border border-base-300 lg:col-span-2">
                <div class="card-body">
                    <div class="flex items-center justify-between">
                        <h2 class="card-title">Menu Catering</h2>
                        <form method="GET" class="flex gap-2">
                            <select name="kategori" class="select select-bordered select-sm">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kat)
                                    <option value="{{ $kat }}" {{ $kategoriNow === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                        </form>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4 mt-4">
                        @forelse ($pakets as $p)
                            <div class="card bg-base-100 border border-base-300">
                                <figure><img src="{{ $p->foto1 }}" alt="{{ $p->nama_paket }}" class="h-40 w-full object-cover" /></figure>
                                <div class="card-body">
                                    <h3 class="card-title">{{ $p->nama_paket }}</h3>
                                    <p class="text-sm text-base-content/70">{{ $p->jenis }} • {{ $p->kategori }}</p>
                                    <p class="font-bold">Rp {{ number_format($p->harga_paket, 0, ',', '.') }}</p>
                                    <div class="card-actions justify-end">
                                        <button type="button" class="btn btn-outline btn-sm" onclick="detail_{{ $p->id }}.showModal()">Detail</button>
                                        <button type="button" class="btn btn-primary btn-sm"
                                            data-id="{{ $p->id }}"
                                            data-nama="{{ $p->nama_paket }}"
                                            data-harga="{{ $p->harga_paket }}"
                                            onclick="window.pelangganAddToCart(this)">
                                            Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <dialog id="detail_{{ $p->id }}" class="modal">
                                <div class="modal-box max-w-xl">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="text-lg font-bold">{{ $p->nama_paket }}</div>
                                        <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="detail_{{ $p->id }}.close()">✕</button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 mb-4">
                                        <img src="{{ $p->foto1 }}" class="h-24 w-full object-cover rounded" alt="">
                                        <img src="{{ $p->foto2 }}" class="h-24 w-full object-cover rounded" alt="">
                                        <img src="{{ $p->foto3 }}" class="h-24 w-full object-cover rounded" alt="">
                                    </div>
                                    <div class="space-y-2">
                                        <div class="text-sm text-base-content/70">Kategori</div>
                                        <div class="font-medium">{{ $p->kategori }}</div>
                                        <div class="text-sm text-base-content/70">Jenis</div>
                                        <div class="font-medium">{{ $p->jenis }}</div>
                                        <div class="text-sm text-base-content/70">Harga</div>
                                        <div class="font-bold">Rp {{ number_format($p->harga_paket, 0, ',', '.') }}</div>
                                        <div class="text-sm text-base-content/70">Deskripsi</div>
                                        <div>{{ $p->deskripsi }}</div>
                                    </div>
                                    <div class="modal-action">
                                        <button type="button" class="btn" onclick="detail_{{ $p->id }}.close()">Tutup</button>
                                    </div>
                                </div>
                            </dialog>
                        @empty
                            <div class="text-center text-base-content/70">Tidak ada menu</div>
                        @endforelse
                    </div>
                    <div class="mt-4">{{ $pakets->withQueryString()->links() }}</div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <h2 class="card-title">Keranjang</h2>
                    <div class="overflow-x-auto">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th>Harga</th>
                                    <th>Qty</th>
                                    <th class="text-right">Subtotal</th>
                                    <th class="text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody"></tbody>
                        </table>
                    </div>
                    <div class="flex items-center justify-between mt-3">
                        <div class="text-lg font-bold">Total: <span id="cartGrandTotal">Rp 0</span></div>
                        <div class="flex gap-2">
                            <button type="button" class="btn btn-outline btn-sm" onclick="window.pelangganClearCart()">Kosongkan</button>
                            <a href="#checkout" class="btn btn-primary btn-sm">Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="checkout" class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <h2 class="card-title">Checkout</h2>
                <form onsubmit="window.pelangganSimulateCheckout(event)" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Nama</span></label>
                        <input type="text" id="co_nama" class="input input-bordered" value="{{ $pl->nama_pelanggan }}" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Telepon</span></label>
                        <input type="text" id="co_telepon" class="input input-bordered" value="{{ $pl->telepon }}" required>
                    </div>
                    <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Alamat 1</span></label>
                            <input type="text" id="co_alamat1" class="input input-bordered" value="{{ $pl->alamat1 }}" required>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Alamat 2</span></label>
                            <input type="text" id="co_alamat2" class="input input-bordered" value="{{ $pl->alamat2 }}" required>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Alamat 3</span></label>
                            <input type="text" id="co_alamat3" class="input input-bordered" value="{{ $pl->alamat3 }}" required>
                        </div>
                    </div>
                    <div class="sm:col-span-2 flex items-center justify-end pt-2">
                        <button type="submit" class="btn btn-primary">Buat Pesanan (Simulasi)</button>
                    </div>
                </form>
            </div>
        </div>

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
    <script>
        const fmt = (n) => 'Rp ' + (n || 0).toLocaleString('id-ID');
        const getCart = () => {
            try { return JSON.parse(localStorage.getItem('pelanggan_cart') || '[]'); } catch { return []; }
        };
        const setCart = (arr) => {
            localStorage.setItem('pelanggan_cart', JSON.stringify(arr));
            window.pelangganRenderCart();
        };
        window.pelangganAddToCart = (btn) => {
            const id = parseInt(btn.dataset.id, 10);
            const nama = btn.dataset.nama;
            const harga = parseInt(btn.dataset.harga, 10);
            const cart = getCart();
            const idx = cart.findIndex(i => i.id === id);
            if (idx >= 0) { cart[idx].qty += 1; } else { cart.push({ id, nama, harga, qty: 1 }); }
            setCart(cart);
        };
        window.pelangganRemoveItem = (id) => {
            setCart(getCart().filter(i => i.id !== id));
        };
        window.pelangganInc = (id) => {
            const cart = getCart().map(i => i.id === id ? { ...i, qty: i.qty + 1 } : i);
            setCart(cart);
        };
        window.pelangganDec = (id) => {
            const cart = getCart().map(i => i.id === id ? { ...i, qty: Math.max(1, i.qty - 1) } : i);
            setCart(cart);
        };
        window.pelangganClearCart = () => {
            setCart([]);
        };
        window.pelangganRenderCart = () => {
            const tbody = document.getElementById('cartTableBody');
            const cart = getCart();
            tbody.innerHTML = cart.map(i => `
                <tr>
                    <td>${i.nama}</td>
                    <td>${fmt(i.harga)}</td>
                    <td>
                        <div class="join join-horizontal">
                            <button class="btn btn-xs join-item" onclick="window.pelangganDec(${i.id})">-</button>
                            <span class="px-2">${i.qty}</span>
                            <button class="btn btn-xs join-item" onclick="window.pelangganInc(${i.id})">+</button>
                        </div>
                    </td>
                    <td class="text-right">${fmt(i.harga * i.qty)}</td>
                    <td class="text-right">
                        <button class="btn btn-outline btn-xs" onclick="window.pelangganRemoveItem(${i.id})">Hapus</button>
                    </td>
                </tr>
            `).join('');
            const total = cart.reduce((s, i) => s + i.harga * i.qty, 0);
            document.getElementById('cartGrandTotal').textContent = fmt(total);
        };
        window.pelangganSimulateCheckout = (e) => {
            e.preventDefault();
            const cart = getCart();
            if (!cart.length) {
                alert('Keranjang kosong');
                return;
            }
            const nama = document.getElementById('co_nama').value;
            const telepon = document.getElementById('co_telepon').value;
            const alamat1 = document.getElementById('co_alamat1').value;
            const alamat2 = document.getElementById('co_alamat2').value;
            const alamat3 = document.getElementById('co_alamat3').value;
            const payload = { nama, telepon, alamat1, alamat2, alamat3, cart, ts: Date.now() };
            localStorage.setItem('pelanggan_last_order', JSON.stringify(payload));
            window.pelangganClearCart();
            alert('Pesanan dibuat (simulasi). Silakan pantau status pesanan.');
        };
        window.addEventListener('DOMContentLoaded', window.pelangganRenderCart);
    </script>
@endsection
