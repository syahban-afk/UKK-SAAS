<li>
    <a href="{{ route('dashboard.pelanggan.index') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.pelanggan.index') ? 'active' : '' }}"
       data-tip="Landing">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round"
            stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block h-4 w-4">
            <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"></path>
            <path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
        </svg>
        <span class="is-drawer-close:hidden">Landing</span>
    </a>
</li>
<li>
    <a href="{{ route('dashboard.pelanggan.cart') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.pelanggan.cart') ? 'active' : '' }}"
       data-tip="Keranjang">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round"
            stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block h-4 w-4">
            <path d="M6 6h15l-1.5 9h-13.5z"></path>
            <circle cx="9" cy="19" r="2"></circle>
            <circle cx="17" cy="19" r="2"></circle>
        </svg>
        <span class="is-drawer-close:hidden">Keranjang</span>
    </a>
</li>
<li>
    <a href="{{ route('dashboard.pelanggan.checkout') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.pelanggan.checkout') ? 'active' : '' }}"
       data-tip="Checkout">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round"
            stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block h-4 w-4">
            <path d="M12 5v14"></path>
            <path d="M5 12h14"></path>
        </svg>
        <span class="is-drawer-close:hidden">Checkout</span>
    </a>
</li>
<li>
    <a href="{{ route('dashboard.pelanggan.status') }}"
       class="is-drawer-close:tooltip is-drawer-close:tooltip-right {{ request()->routeIs('dashboard.pelanggan.status') ? 'active' : '' }}"
       data-tip="Status Pesanan">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-linejoin="round" stroke-linecap="round"
            stroke-width="2" fill="none" stroke="currentColor" class="my-1.5 inline-block h-4 w-4">
            <path d="M5 12l5 5l10 -10"></path>
        </svg>
        <span class="is-drawer-close:hidden">Status</span>
    </a>
</li>
