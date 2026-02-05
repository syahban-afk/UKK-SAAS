@extends('layouts.public')

@section('content')
    <section id="home" class="hero-gradient h-screen flex items-center justify-center text-center text-white px-4">
        <div class="max-w-3xl">
            <h1 class="text-5xl md:text-7xl font-bold mb-6">Katering Eksklusif untuk Momen Spesial Anda</h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">Dari pertemuan hingga perayaan megah, kami membawa kesempurnaan kuliner ke pintu Anda.</p>
            <a href="#menu" class="bg-orange-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-orange-700 transition transform hover:scale-105 inline-block">Jelajahi Menu Kami</a>
        </div>
    </section>

    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Melayani Kualitas Sejak 2010</h2>
                    <p class="text-gray-600 text-lg mb-6">Kami berspesialisasi dalam menyediakan layanan katering premium untuk pernikahan, acara perusahaan, dan perayaan keluarga. Tim koki ahli kami hanya menggunakan bahan-bahan tersegar untuk menciptakan pengalaman makan yang tak terlupakan.</p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="border-l-4 border-orange-600 pl-4">
                            <h4 class="text-2xl font-bold text-gray-900">500+</h4>
                            <p class="text-gray-500">Acara Dilayani</p>
                        </div>
                        <div class="border-l-4 border-orange-600 pl-4">
                            <h4 class="text-2xl font-bold text-gray-900">50+</h4>
                            <p class="text-gray-500">Koki Ahli</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Koki sedang bekerja" class="rounded-2xl shadow-2xl">
                    <div class="absolute -bottom-6 -left-6 bg-orange-600 text-white p-6 rounded-2xl hidden md:block">
                        <p class="text-xl font-bold italic">"Makanan adalah seni, dan kami adalah senimannya."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Promosi Saat Ini</h2>
            <p class="text-gray-600 mb-12 max-w-2xl mx-auto">Jangan lewatkan penawaran waktu terbatas kami untuk acara Anda selanjutnya.</p>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-orange-100 flex items-center gap-6">
                    <div class="bg-orange-100 p-4 rounded-full text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline w-12 h-12" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M9 15l6 -6" />
                            <path d="M9 9v6h6" />
                            <path d="M3 6a4 4 0 0 1 4 -4h10a4 4 0 0 1 4 4v10a4 4 0 0 1 -4 4h-10a4 4 0 0 1 -4 -4z" />
                            <path d="M8 3v4" />
                            <path d="M16 3v4" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-2xl font-bold mb-2">Early Bird Pernikahan</h3>
                        <p class="text-gray-600">Dapatkan diskon 15% untuk pemesanan 6 bulan sebelumnya.</p>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-orange-100 flex items-center gap-6">
                    <div class="bg-orange-100 p-4 rounded-full text-orange-600">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline w-12 h-12" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M3 21v-13l9 -4l9 4v13" />
                            <path d="M13 13h4v8h-10v-6h6" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-2xl font-bold mb-2">Spesial Korporat</h3>
                        <p class="text-gray-600">Cemilan gratis untuk rapat lebih dari 50 pax.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="menu" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Menu Katering Kami</h2>
                    <p class="text-gray-600">Sekilas paket paling populer kami.</p>
                </div>
                <a href="{{ url('/menu') }}" class="text-orange-600 font-semibold hover:underline">Lihat Semua Menu →</a>
            </div>
            <div class="grid md:grid-cols-3 gap-8">
                @foreach($featuredMenus as $menu)
                <div class="bg-white rounded-2xl overflow-hidden shadow-lg border border-gray-100 hover:shadow-xl transition group">
                    <div class="relative h-64 overflow-hidden">
                        <img src="{{ $menu->foto1 ?: 'https://images.unsplash.com/photo-1547573854-74d2a71d0826?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80' }}" alt="{{ $menu->nama_paket }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur px-3 py-1 rounded-full text-orange-600 font-bold">
                            Rp {{ number_format($menu->harga_paket, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-gray-900">{{ $menu->nama_paket }}</h3>
                            <span class="bg-orange-100 text-orange-600 text-xs px-2 py-1 rounded-md uppercase font-bold">{{ $menu->jenis }}</span>
                        </div>
                        <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $menu->deskripsi }}</p>
                        <div class="flex items-center text-gray-400 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline w-4 h-4 mr-1" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                <path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                <path d="M17 11l2 2l4 -4" />
                                <path d="M12 19l-4 -2l-4 2v-11a4 4 0 0 1 8 0v11" />
                            </svg>
                            {{ $menu->jumlah_pax }} Pax
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
