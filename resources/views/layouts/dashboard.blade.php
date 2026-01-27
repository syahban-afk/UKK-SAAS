<!DOCTYPE html>
<html lang="id" data-theme="emerald">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="drawer lg:drawer-open">
        <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex min-h-screen flex-col">
            <!-- Navbar -->
            <nav class="navbar w-full bg-base-100 shadow-sm">
                <div class="flex-1">
                    <label for="my-drawer-4" aria-label="open sidebar" class="btn btn-square btn-ghost">
                        <!-- Sidebar toggle icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round"
                            stroke-linecap="round" stroke-width="2" fill="none" stroke="currentColor"
                            class="my-1.5 inline-block size-4">
                            <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z">
                            </path>
                            <path d="M9 4v16"></path>
                            <path d="M14 10l2 2l-2 2"></path>
                        </svg>
                    </label>
                    <a class="btn btn-ghost text-xl">daisyUI</a>
                </div>
                <div class="flex gap-2">
                    <input type="text" placeholder="Search" class="input input-bordered w-24 md:w-auto" />
                    <div class="dropdown dropdown-end">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                            <div class="w-10 rounded-full">
                                <img alt="Tailwind CSS Navbar component"
                                    src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                            </div>
                        </div>
                        <ul tabindex="-1"
                            class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                            <li>
                                <a class="justify-between">
                                    Profile
                                    <span class="badge">New</span>
                                </a>
                            </li>
                            <li><a>Settings</a></li>
                            <li><a>Logout</a></li>
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
                class="footer sm:footer-horizontal footer-center bg-base-100 shadow-[0_-4px_10px_rgba(0,0,0,0.08)] text-base-content p-4">
                <aside>
                    <p>Copyright © {{ date('Y') }} - All right reserved</p>
                </aside>
            </footer>

        </div>

        <div class="drawer-side is-drawer-close:overflow-visible shadow-sm bg-base-100">
            <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
            <div class="flex min-h-full flex-col items-start is-drawer-close:w-14 is-drawer-open:w-64">
                <!-- Sidebar content here -->
                <ul class="menu w-full grow">
                    @yield('menu')
                </ul>
            </div>
        </div>
    </div>

</body>

</html>
