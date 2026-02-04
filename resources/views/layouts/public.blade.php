<!DOCTYPE html>
<html lang="id" data-theme="bumblebee">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .hero-gradient {
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1555244162-803834f70033?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>

<body class="bg-[#FDFDFC] text-[#1b1b18]">
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="shrink-0 flex items-center">
                    <span class="text-2xl font-bold text-orange-600">CateringPro</span>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <a href="{{ url('/') }}"
                            class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                        <a href="{{ url('/') }}#about"
                            class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">About</a>
                        <a href="{{ url('/menu') }}"
                            class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Menu</a>
                        @if (Route::has('login'))
                            @auth('pelanggan')
                                <a href="{{ route('dashboard.pelanggan.index') }}"
                                    class="bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-orange-700 transition">Dashboard</a>
                            @elseauth
                                <a href="{{ url('/dashboard') }}"
                                    class="bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-orange-700 transition">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}"
                                    class="text-gray-700 hover:text-orange-600 px-3 py-2 rounded-md text-sm font-medium">Log
                                    in</a>
                                <a href="{{ route('register') }}"
                                    class="bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-orange-700 transition">Register</a>
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-8">CateringPro</h2>
            <div class="flex justify-center space-x-6 mb-8">
                <a href="#" class="hover:text-orange-500 transition">Instagram</a>
                <a href="#" class="hover:text-orange-500 transition">Facebook</a>
                <a href="#" class="hover:text-orange-500 transition">WhatsApp</a>
            </div>
            <p class="text-gray-400">© 2026 CateringPro. All rights reserved.</p>
        </div>
    </footer>
</body>

</html>
