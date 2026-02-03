@extends('layouts.dashboard', ['title' => 'Dashboard Pelanggan'])

@section('menu')
    @include('menus.pelanggan')
@endsection

@section('content')
    <div class="space-y-6">
        

        <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ $pl->nama_pelanggan ?? 'Pengunjung' }}!</h1>
                <p class="text-gray-500">Pesan catering favorit Anda dengan mudah.</p>
            </div>
            @if(!empty($pl))
                <button onclick="profile_modal.showModal()" class="btn btn-ghost btn-circle avatar">
                    <div class="w-12 rounded-full ring ring-orange-600 ring-offset-base-100 ring-offset-2">
                        <img src="{{ $pl->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pl->nama_pelanggan).'&color=7F9CF5&background=EBF4FF' }}" />
                    </div>
                </button>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                        <h2 class="text-xl font-bold text-gray-900">Menu Catering</h2>
                        <form method="GET" class="flex gap-2 w-full sm:w-auto">
                            <select name="kategori" class="select select-bordered select-sm flex-1 sm:flex-none">
                                <option value="">Semua Kategori</option>
                                @foreach ($kategoris as $kat)
                                    <option value="{{ $kat }}" {{ $kategoriNow === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-primary" type="submit">Filter</button>
                        </form>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse ($pakets as $p)
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
                                        <button type="button" class="btn btn-outline btn-xs" onclick="detail_{{ $p->id }}.showModal()">Detail</button>
                                        <button type="button" class="btn btn-primary btn-xs"
                                            data-id="{{ $p->id }}"
                                            data-nama="{{ $p->nama_paket }}"
                                            data-harga="{{ $p->harga_paket }}"
                                            onclick="window.pelangganAddToCart(this)">
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <dialog id="detail_{{ $p->id }}" class="modal">
                                <div class="modal-box max-w-2xl rounded-2xl p-0 overflow-hidden">
                                    <div class="relative h-64">
                                        <img src="{{ $p->foto1 }}" class="w-full h-full object-cover" alt="">
                                        <button type="button" class="absolute top-4 right-4 btn btn-sm btn-circle btn-ghost bg-black/20 text-white" onclick="detail_{{ $p->id }}.close()">✕</button>
                                    </div>
                                    <div class="p-8">
                                        <div class="flex justify-between items-start mb-4">
                                            <div>
                                                <h3 class="text-2xl font-bold text-gray-900">{{ $p->nama_paket }}</h3>
                                                <div class="flex gap-2 mt-1">
                                                    <span class="badge badge-orange-100 text-orange-600 border-none">{{ $p->kategori }}</span>
                                                    <span class="badge badge-gray-100 text-gray-600 border-none">{{ $p->jenis }}</span>
                                                </div>
                                            </div>
                                            <div class="text-2xl font-bold text-orange-600">Rp {{ number_format($p->harga_paket, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="grid grid-cols-3 gap-4 mb-6">
                                            <img src="{{ $p->foto1 }}" class="h-24 w-full object-cover rounded-xl" alt="">
                                            <img src="{{ $p->foto2 }}" class="h-24 w-full object-cover rounded-xl" alt="">
                                            <img src="{{ $p->foto3 }}" class="h-24 w-full object-cover rounded-xl" alt="">
                                        </div>
                                        <div class="prose prose-sm max-w-none text-gray-600 mb-8">
                                            <p>{{ $p->deskripsi }}</p>
                                            <p class="font-semibold text-gray-900 mt-4">Porsi: {{ $p->jumlah_pax }} Pax</p>
                                        </div>
                                        <button type="button" class="btn btn-primary w-full"
                                            data-id="{{ $p->id }}"
                                            data-nama="{{ $p->nama_paket }}"
                                            data-harga="{{ $p->harga_paket }}"
                                            onclick="window.pelangganAddToCart(this); detail_{{ $p->id }}.close()">
                                            Tambah ke Keranjang
                                        </button>
                                    </div>
                                </div>
                            </dialog>
                        @empty
                            <div class="col-span-full text-center py-12 text-gray-500">Tidak ada menu ditemukan</div>
                        @endforelse
                    </div>
                    <div class="mt-8">{{ $pakets->withQueryString()->links() }}</div>
                </div>

                @if(!empty($pl))
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
                                        <td>{{ \Carbon\Carbon::parse($o->tgl_pesan)->format('d M Y') }}</td>
                                        <td>
                                            @php
                                                $badgeClass = match ($o->status_pesan) {
                                                    'Menunggu Konfirmasi' => 'bg-yellow-100 text-yellow-700',
                                                    'Sedang Diproses' => 'bg-blue-100 text-blue-700',
                                                    'Menunggu Kurir' => 'bg-purple-100 text-purple-700',
                                                    default => 'bg-gray-100 text-gray-700',
                                                };
                                            @endphp
                                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">{{ $o->status_pesan }}</span>
                                        </td>
                                        <td>
                                            @if ($o->pengiriman)
                                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $o->pengiriman->status_kirim === 'Tiba Ditujuan' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                                    {{ $o->pengiriman->status_kirim }}
                                                </span>
                                            @else
                                                <span class="text-gray-400 italic">Belum dikirim</span>
                                            @endif
                                        </td>
                                        <td class="text-right font-bold">Rp {{ number_format($o->total_bayar, 0, ',', '.') }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center py-8 text-gray-500">Belum ada pesanan</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6">{{ $orders->links() }}</div>
                </div>
                @endif
            </div>

            <div class="space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 118 0m-4 4v2m0-6V4m-8 8H4m10 0h4M4 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                        Keranjang
                    </h2>
                    <div id="cartItemsList" class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2">
                        <!-- Cart items injected here -->
                    </div>
                    <div class="border-t border-gray-100 pt-6 space-y-4">
                        <div class="flex justify-between items-center text-gray-500">
                            <span>Subtotal</span>
                            <span id="cartSubtotal">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center text-xl font-bold text-gray-900">
                            <span>Total</span>
                            <span id="cartGrandTotal">Rp 0</span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 pt-2">
                            <button type="button" class="btn btn-outline btn-sm" onclick="window.pelangganClearCart()">Clear</button>
                            @if (!empty($canCheckout))
                                <a href="#checkout_section" class="btn btn-primary btn-sm">Checkout</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login untuk Checkout</a>
                            @endif
                        </div>
                    </div>
                </div>

                @if (!empty($canCheckout))
                <div id="checkout_section" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Checkout
                    </h2>
                    <form id="checkoutForm" onsubmit="window.handleRealCheckout(event)" class="space-y-4">
                        @csrf
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Nama Penerima</span></label>
                            <input type="text" name="nama" class="input input-bordered focus:ring-orange-600" value="{{ $pl->nama_pelanggan }}" required>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Nomor Telepon</span></label>
                            <input type="text" name="telepon" class="input input-bordered focus:ring-orange-600" value="{{ $pl->telepon }}" required>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text font-semibold">Alamat Lengkap</span></label>
                            <textarea name="alamat1" class="textarea textarea-bordered h-24 focus:ring-orange-600" placeholder="Alamat Utama" required>{{ $pl->alamat1 }}</textarea>
                            <input type="text" name="alamat2" class="input input-bordered mt-2" placeholder="Detail Alamat 2 (Opsional)" value="{{ $pl->alamat2 }}">
                            <input type="text" name="alamat3" class="input input-bordered mt-2" placeholder="Detail Alamat 3 (Opsional)" value="{{ $pl->alamat3 }}">
                        </div>
                        <div class="pt-4">
                            <button type="submit" id="submitOrderBtn" class="btn btn-primary w-full shadow-lg shadow-orange-200">
                                Buat Pesanan Sekarang
                            </button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Profile Modal -->
    <dialog id="profile_modal" class="modal">
        <div class="modal-box rounded-2xl max-w-md">
            <h3 class="font-bold text-lg mb-6">Profile Settings</h3>
            <form action="{{ route('dashboard.pelanggan.checkout.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="flex justify-center mb-6">
                    <div class="avatar">
                        <div class="w-24 rounded-full ring ring-orange-600 ring-offset-base-100 ring-offset-2">
                            <img src="{{ $pl->foto ?: 'https://ui-avatars.com/api/?name='.urlencode($pl->nama_pelanggan).'&color=7F9CF5&background=EBF4FF' }}" />
                        </div>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Full Name</span></label>
                    <input type="text" name="nama" value="{{ $pl->nama_pelanggan }}" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Email</span></label>
                    <input type="email" value="{{ $pl->email }}" class="input input-bordered" readonly>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text font-semibold">Phone</span></label>
                    <input type="text" name="telepon" value="{{ $pl->telepon }}" class="input input-bordered" required>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="profile_modal.close()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </dialog>

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
            
            // Show toast or something
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-[100] animate-bounce';
            toast.textContent = 'Ditambahkan ke keranjang!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
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
            if(confirm('Kosongkan keranjang?')) setCart([]);
        };

        window.pelangganRenderCart = () => {
            const list = document.getElementById('cartItemsList');
            const cart = getCart();
            
            if (cart.length === 0) {
                list.innerHTML = '<div class="text-center py-8 text-gray-400">Keranjang masih kosong</div>';
                document.getElementById('cartSubtotal').textContent = 'Rp 0';
                document.getElementById('cartGrandTotal').textContent = 'Rp 0';
                return;
            }

            list.innerHTML = cart.map(i => `
                <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-xl">
                    <div class="flex-1 min-w-0">
                        <div class="font-bold text-gray-900 truncate">${i.nama}</div>
                        <div class="text-xs text-gray-500">${fmt(i.harga)} x ${i.qty}</div>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="join join-horizontal bg-white border border-gray-200">
                            <button class="btn btn-xs join-item bg-transparent border-none" onclick="window.pelangganDec(${i.id})">-</button>
                            <span class="px-2 text-xs self-center">${i.qty}</span>
                            <button class="btn btn-xs join-item bg-transparent border-none" onclick="window.pelangganInc(${i.id})">+</button>
                        </div>
                        <button class="btn btn-ghost btn-xs text-red-500" onclick="window.pelangganRemoveItem(${i.id})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </div>
                </div>
            `).join('');
            
            const total = cart.reduce((s, i) => s + i.harga * i.qty, 0);
            document.getElementById('cartSubtotal').textContent = fmt(total);
            document.getElementById('cartGrandTotal').textContent = fmt(total);
        };

        window.handleRealCheckout = async (e) => {
            e.preventDefault();
            const cart = getCart();
            if (!cart.length) {
                alert('Keranjang kosong');
                return;
            }

            const btn = document.getElementById('submitOrderBtn');
            btn.disabled = true;
            btn.innerHTML = '<span class="loading loading-spinner"></span> Memproses...';

            const formData = new FormData(e.target);
            const data = {
                nama: formData.get('nama'),
                telepon: formData.get('telepon'),
                alamat1: formData.get('alamat1'),
                alamat2: formData.get('alamat2'),
                alamat3: formData.get('alamat3'),
                cart: cart,
                _token: '{{ csrf_token() }}'
            };

            try {
                const res = await fetch('{{ route("dashboard.pelanggan.checkout.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                if (result.success) {
                    window.pelangganClearCart();
                    alert('Pesanan berhasil dibuat! No Resi: ' + result.no_resi);
                    window.location.reload();
                } else {
                    alert('Terjadi kesalahan: ' + (result.message || 'Unknown error'));
                }
            } catch (err) {
                console.error(err);
                alert('Gagal membuat pesanan. Silakan coba lagi.');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Buat Pesanan Sekarang';
            }
        };

        window.addEventListener('DOMContentLoaded', window.pelangganRenderCart);
    </script>
@endsection
