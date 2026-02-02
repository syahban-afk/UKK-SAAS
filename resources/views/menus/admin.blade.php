<li>
    <a href="{{ route('dashboard.admin.index') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.admin.index') ? 'active' : '' }}"
       data-tip="Dashboard">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-home">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
            <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
            <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
        </svg>
        <span class="is-drawer-close:hidden">Dashboard</span>
    </a>
</li>
<li>
    <a href="{{ route('dashboard.admin.menu') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.admin.menu') ? 'active' : '' }}"
       data-tip="Manajemen Menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-list">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M9 6h11" /><path d="M9 12h11" /><path d="M9 18h11" />
            <path d="M5 6l0 .01" /><path d="M5 12l0 .01" /><path d="M5 18l0 .01" />
        </svg>
        <span class="is-drawer-close:hidden">Manajemen Menu</span>
    </a>
</li>
<li>
    <a href="{{ route('dashboard.admin.orders') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.admin.orders') ? 'active' : '' }}"
       data-tip="Manajemen Pesanan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-bag">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M6 6h12l-1 12a2 2 0 0 1 -2 2h-6a2 2 0 0 1 -2 -2z" />
            <path d="M9 6a3 3 0 0 1 6 0" />
        </svg>
        <span class="is-drawer-close:hidden">Manajemen Pesanan</span>
    </a>
</li>
<li>
    <a href="{{ route('dashboard.admin.users') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.admin.users') ? 'active' : '' }}"
       data-tip="Manajemen Kurir">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-truck">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M7 17a2 2 0 1 0 0 4a2 2 0 0 0 0 -4" />
            <path d="M17 17a2 2 0 1 0 0 4a2 2 0 0 0 0 -4" />
            <path d="M5 17h8v-10h-8z" />
            <path d="M13 7h4l3 5v5h-7z" />
        </svg>
        <span class="is-drawer-close:hidden">Manajemen Kurir</span>
    </a>
</li>
<li>
    <a href="{{ route('dashboard.admin.report') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.admin.report') ? 'active' : '' }}"
       data-tip="Laporan">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-file-invoice">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M14 3v4a1 1 0 0 0 1 1h4" />
            <path d="M5 21h14a2 2 0 0 0 2 -2v-11l-5 -5h-9a2 2 0 0 0 -2 2v14" />
            <path d="M9 7l1 0" /><path d="M9 13l6 0" /><path d="M13 17l2 0" />
        </svg>
        <span class="is-drawer-close:hidden">Laporan</span>
    </a>
</li>
