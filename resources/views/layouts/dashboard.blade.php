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
</head>

<body class="bg-base-200">
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex min-h-screen flex-col">
            <!-- Navbar -->
            <nav class="navbar w-full bg-base-100 border border-base-300">
                <div class="navbar-start">
                    <label for="my-drawer-4" class="btn btn-square btn-ghost">

                        <!-- ICON: SIDEBAR EXPAND -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round"
                            class="icon icon-tabler icons-tabler-outline icon-tabler-layout-sidebar">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path
                                d="M4 6a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2l0 -12" />
                            <path d="M9 4l0 16" />
                        </svg>

                    </label>

                </div>

                <div class="navbar-center">
                    <a class="btn btn-ghost text-xl">CateringPro</a>
                </div>

                <div class="flex gap-2 navbar-end">
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full">
                                @if (auth('pelanggan')->check())
                                    <img alt="Tailwind CSS Navbar component"
                                        src="{{ auth('pelanggan')->user()->foto ? asset('storage/' . auth('pelanggan')->user()->foto) : 'https://ui-avatars.com/api/?name=' . urlencode(auth('pelanggan')->user()->nama_pelanggan) . '&color=7F9CF5&background=EBF4FF' }}" />
                                @else
                                    <img alt="Tailwind CSS Navbar component"
                                        src="{{ auth('web')->user()->foto ?: 'https://ui-avatars.com/api/?name=' . urlencode(auth('web')->user()->name) . '&color=7F9CF5&background=EBF4FF' }}" />
                                @endif
                            </div>
                        </div>
                        <ul tabindex="-1"
                            class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                            <li>
                                @if (auth('pelanggan')->check())
                                    <a href="{{ route('dashboard.pelanggan.settings') }}">Settings</a>
                                @else
                                    <a
                                        href="{{ route('dashboard.' . auth()->user()->level . '.settings') }}">Settings</a>
                                @endif
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" data-tip="Logout" type="submit">
                                    @csrf
                                    <button type="submit">
                                        <a>Logout</a>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>

            </nav>

            <!-- Page content here -->
            <main class="page-main flex-1 p-4">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer
                class="footer sm:footer-horizontal footer-center bg-base-100 border border-base-300 text-base-content p-4">
                <aside>
                    <p>Copyright © {{ date('Y') }} - All right reserved</p>
                </aside>
            </footer>

        </div>

        <div class="drawer-side is-drawer-close:overflow-visible bg-base-100 border border-base-300">
            <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
            <div class="flex min-h-screen flex-col items-start is-drawer-close:w-14 is-drawer-open:w-64">
                <!-- Sidebar content here -->
                <div class="flex min-h-screen flex-col w-full">
                    <!-- MENU ATAS -->
                    <ul class="menu w-full">
                        @yield('menu')
                    </ul>

                    <!-- MENU BAWAH -->
                    <ul class="menu w-full mt-auto border-t border-base-300 pt-2">
                        @yield('menu-bottom')
                    </ul>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
