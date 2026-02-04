@extends('layouts.public')

@section('content')
    <main class="max-w-7xl mx-auto p-4 pt-20">
        <div class="space-y-6">

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100">

                {{-- Header --}}
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-gray-900">Daftar Menu</h2>
                    <p class="text-sm text-gray-500">Pilih paket sesuai kebutuhan kamu</p>
                </div>

                {{-- Layout --}}
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

                    {{-- SIDEBAR --}}
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm
                                lg:sticky lg:top-24 h-fit">

                            <h3 class="font-bold text-gray-900 mb-4">Cari & Filter</h3>

                            <form method="GET" class="space-y-4">

                                {{-- Search --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Pencarian</label>
                                    <input type="text" name="search" value="{{ $search }}"
                                        placeholder="Cari paket / jenis..." class="input input-bordered w-full mt-1" />
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label class="text-sm font-medium text-gray-600">Kategori</label>
                                    <select name="kategori" class="select select-bordered w-full mt-1">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($kategoris as $kat)
                                            <option value="{{ $kat }}"
                                                {{ $kategoriNow === $kat ? 'selected' : '' }}>
                                                {{ $kat }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Button --}}
                                <button type="submit" class="btn text-white bg-orange-600 hover:bg-orange-700 w-full">
                                    Terapkan Filter
                                </button>

                                @if (request()->hasAny(['search', 'kategori']))
                                    <a href="{{ route('menu.public') }}" class="btn btn-outline btn-sm w-full">
                                        Reset Filter
                                    </a>
                                @endif

                            </form>
                        </div>
                    </div>

                    {{-- CONTENT --}}
                    <div class="lg:col-span-3">

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @forelse ($pakets as $p)
                                <div
                                    class="group bg-white rounded-2xl overflow-hidden border border-gray-100
                                       hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                                    {{-- Image --}}
                                    <div class="relative h-44 overflow-hidden">
                                        <img src="{{ $p->foto1 }}" alt="{{ $p->nama_paket }}"
                                            class="w-full h-full object-cover group-hover:scale-110 transition duration-500" />

                                        <div class="absolute inset-0 bg-linear-to-t from-black/40 to-transparent"></div>

                                        <div
                                            class="absolute top-3 right-3 bg-orange-500 text-white
                                                px-3 py-1 rounded-full text-xs font-bold shadow">
                                            Rp {{ number_format($p->harga_paket, 0, ',', '.') }}
                                        </div>
                                    </div>

                                    {{-- Content --}}
                                    <div class="p-4 space-y-2">
                                        <h3 class="font-bold text-gray-900 truncate">
                                            {{ $p->nama_paket }}
                                        </h3>

                                        <p class="text-xs text-gray-500">
                                            {{ $p->jenis }} • {{ $p->kategori }}
                                        </p>

                                        <div class="grid grid-cols-2 gap-2 pt-2">
                                            <button type="button" class="btn btn-outline btn-xs"
                                                onclick="detail_{{ $p->id }}.showModal()">
                                                Detail
                                            </button>

                                            <button type="button"
                                                class="btn bg-orange-600 hover:bg-orange-700 text-white btn-xs hover:scale-[1.03] transition"
                                                data-id="{{ $p->id }}" data-nama="{{ $p->nama_paket }}"
                                                data-harga="{{ $p->harga_paket }}" data-img="{{ $p->foto1 }}"
                                                onclick="window.pelangganAddToCart(this)">
                                                + Keranjang
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                {{-- MODAL DETAIL --}}
                                <dialog id="detail_{{ $p->id }}" class="modal modal-bottom sm:modal-middle">
                                    <div class="modal-box max-w-2xl rounded-3xl p-0 overflow-hidden bg-white">
                                        <div class="relative h-64">
                                            <img src="{{ $p->foto1 }}" class="w-full h-full object-cover"
                                                alt="">
                                            <button
                                                class="absolute top-4 right-4 btn btn-sm btn-circle btn-ghost bg-black/30 text-white"
                                                onclick="detail_{{ $p->id }}.close()">✕</button>
                                        </div>

                                        <div class="p-8">
                                            <div class="flex justify-between items-start mb-4">
                                                <div>
                                                    <h3 class="text-2xl font-bold text-gray-900">
                                                        {{ $p->nama_paket }}
                                                    </h3>
                                                    <div class="flex gap-2 mt-2">
                                                        <span class="badge bg-orange-100 text-orange-600 border-none">
                                                            {{ $p->kategori }}
                                                        </span>
                                                        <span class="badge bg-gray-100 text-gray-600 border-none">
                                                            {{ $p->jenis }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <div class="text-2xl font-bold text-orange-600">
                                                    Rp {{ number_format($p->harga_paket, 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-3 gap-4 mb-6">
                                                <img src="{{ $p->foto1 }}" class="h-24 w-full object-cover rounded-xl">
                                                <img src="{{ $p->foto2 }}" class="h-24 w-full object-cover rounded-xl">
                                                <img src="{{ $p->foto3 }}" class="h-24 w-full object-cover rounded-xl">
                                            </div>

                                            <div class="text-gray-600 text-sm space-y-2 mb-6">
                                                <p>{{ $p->deskripsi }}</p>
                                                <p class="font-semibold text-gray-900">
                                                    Porsi: {{ $p->jumlah_pax }} Pax
                                                </p>
                                            </div>

                                            <button class="btn bg-orange-600 hover:bg-orange-700 text-white w-full" data-id="{{ $p->id }}"
                                                data-nama="{{ $p->nama_paket }}" data-harga="{{ $p->harga_paket }}" data-img="{{ $p->foto1 }}"
                                                onclick="window.pelangganAddToCart(this); detail_{{ $p->id }}.close()">
                                                Tambah ke Keranjang
                                            </button>
                                        </div>
                                    </div>
                                </dialog>

                            @empty
                                <div class="col-span-full text-center py-16 text-gray-500">
                                    Tidak ada menu ditemukan
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $pakets->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

{{-- CART SCRIPT --}}
<script>
    const getCart = () => {
        try {
            return JSON.parse(localStorage.getItem('pelanggan_cart') || '[]');
        } catch {
            return [];
        }
    };

    const setCart = (arr) => {
        localStorage.setItem('pelanggan_cart', JSON.stringify(arr));
    };

    window.pelangganAddToCart = (btn) => {
        const id = parseInt(btn.dataset.id);
        const nama = btn.dataset.nama;
        const harga = parseInt(btn.dataset.harga);
        const img = btn.dataset.img || '';

        const cart = getCart();
        const idx = cart.findIndex(i => i.id === id);

        if (idx >= 0) {
            cart[idx].qty += 1;
            if (!cart[idx].img && img) cart[idx].img = img;
        } else {
            cart.push({
                id,
                nama,
                harga,
                img,
                qty: 1
            });
        }

        setCart(cart);

        const toast = document.createElement('div');
        toast.className =
            'fixed bottom-4 right-4 bg-black/80 backdrop-blur text-white px-6 py-3 rounded-xl shadow-lg z-[100]';
        toast.textContent = 'Ditambahkan ke keranjang!';
        document.body.appendChild(toast);

        setTimeout(() => toast.remove(), 2000);
    };
</script>
