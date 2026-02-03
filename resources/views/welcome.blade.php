@extends('layouts.public')

@section('content')
    <section id="home" class="hero-gradient h-screen flex items-center justify-center text-center text-white px-4">
        <div class="max-w-3xl">
            <h1 class="text-5xl md:text-7xl font-bold mb-6">Exquisite Catering for Your Special Moments</h1>
            <p class="text-xl md:text-2xl mb-8 opacity-90">From intimate gatherings to grand celebrations, we bring culinary perfection to your doorstep.</p>
            <a href="#menu" class="bg-orange-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-orange-700 transition transform hover:scale-105 inline-block">Explore Our Menu</a>
        </div>
    </section>

    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-6">Serving Quality Since 2010</h2>
                    <p class="text-gray-600 text-lg mb-6">We specialize in providing premium catering services for weddings, corporate events, and family celebrations. Our team of expert chefs uses only the freshest ingredients to create unforgettable dining experiences.</p>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="border-l-4 border-orange-600 pl-4">
                            <h4 class="text-2xl font-bold text-gray-900">500+</h4>
                            <p class="text-gray-500">Events Served</p>
                        </div>
                        <div class="border-l-4 border-orange-600 pl-4">
                            <h4 class="text-2xl font-bold text-gray-900">50+</h4>
                            <p class="text-gray-500">Expert Chefs</p>
                        </div>
                    </div>
                </div>
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1556910103-1c02745aae4d?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80" alt="Chef at work" class="rounded-2xl shadow-2xl">
                    <div class="absolute -bottom-6 -left-6 bg-orange-600 text-white p-6 rounded-2xl hidden md:block">
                        <p class="text-xl font-bold italic">"Food is an art, and we are the artists."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-20 bg-orange-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Current Promotions</h2>
            <p class="text-gray-600 mb-12 max-w-2xl mx-auto">Don't miss out on our limited-time offers for your next event.</p>
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-orange-100 flex items-center gap-6">
                    <div class="bg-orange-100 p-4 rounded-full text-orange-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-2xl font-bold mb-2">Wedding Early Bird</h3>
                        <p class="text-gray-600">Get 15% off for bookings 6 months in advance.</p>
                    </div>
                </div>
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-orange-100 flex items-center gap-6">
                    <div class="bg-orange-100 p-4 rounded-full text-orange-600">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="text-left">
                        <h3 class="text-2xl font-bold mb-2">Corporate Special</h3>
                        <p class="text-gray-600">Free snacks for meetings of more than 50 pax.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="menu" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-end mb-12">
                <div>
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Catering Menu</h2>
                    <p class="text-gray-600">A glimpse of our most popular packages.</p>
                </div>
                <a href="{{ url('/menu') }}" class="text-orange-600 font-semibold hover:underline">View All Menu →</a>
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
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            {{ $menu->jumlah_pax }} Pax
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
