<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Menu Catering</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-base-200">
    <nav class="navbar w-full bg-base-100 border border-base-300">
        <div class="navbar-start">
            <a href="{{ url('/') }}" class="btn btn-ghost text-xl">CateringPro</a>
        </div>
        <div class="navbar-end">
            @auth('pelanggan')
                <a href="{{ route('dashboard.pelanggan.index') }}" class="btn btn-primary">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn">Login</a>
            @endauth
        </div>
    </nav>

    <main class="max-w-7xl mx-auto p-4">
        <div class="space-y-6">
            @php
                $kategoriNow = request('kategori');
                $pakets = \App\Models\pakets_model::when($kategoriNow, fn($q) => $q->where('kategori', $kategoriNow))
                    ->orderBy('nama_paket')
                    ->paginate(12);
                $kategoris = ['Pernikahan','Selamatan','Ulang Tahun','Studi Tour','Rapat'];
            @endphp

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-900">Daftar Menu</h2>
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
                                        Tambah ke Keranjang
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
        </div>
    </main>

    <footer class="footer footer-center bg-base-100 border border-base-300 text-base-content p-4">
        <aside>
            <p>© {{ date('Y') }} CateringPro</p>
        </aside>
    </footer>

    <script>
        const getCart = () => {
            try { return JSON.parse(localStorage.getItem('pelanggan_cart') || '[]'); } catch { return []; }
        };
        const setCart = (arr) => {
            localStorage.setItem('pelanggan_cart', JSON.stringify(arr));
        };
        window.pelangganAddToCart = (btn) => {
            const id = parseInt(btn.dataset.id, 10);
            const nama = btn.dataset.nama;
            const harga = parseInt(btn.dataset.harga, 10);
            const cart = getCart();
            const idx = cart.findIndex(i => i.id === id);
            if (idx >= 0) { cart[idx].qty += 1; } else { cart.push({ id, nama, harga, qty: 1 }); }
            setCart(cart);
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 bg-green-600 text-white px-6 py-3 rounded-xl shadow-lg z-[100]';
            toast.textContent = 'Ditambahkan ke keranjang!';
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        };
    </script>
</body>
</html>
