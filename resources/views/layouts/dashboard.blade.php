<!DOCTYPE html>
<html lang="id" data-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
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
                    <button class="btn btn-ghost btn-circle">
                        <div class="indicator">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="badge badge-xs badge-primary indicator-item"></span>
                        </div>
                    </button>
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
