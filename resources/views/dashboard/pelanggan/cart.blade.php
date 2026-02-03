@extends('layouts.dashboard', ['title' => 'Keranjang'])

@section('menu')
    @include('menus.pelanggan')
@endsection

@section('content')
    <div class="space-y-6">
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <h2 class="card-title">Keranjang</h2>
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Menu</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th class="text-right">Subtotal</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody"></tbody>
                    </table>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <div class="text-lg font-bold">Total: <span id="cartGrandTotal">Rp 0</span></div>
                    <div class="flex gap-2">
                        <button type="button" class="btn btn-outline btn-sm" onclick="window.pelangganClearCart()">Kosongkan</button>
                        <a href="{{ route('dashboard.pelanggan.checkout') }}" class="btn btn-primary btn-sm">Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        const fmt = (n) => 'Rp ' + (n || 0).toLocaleString('id-ID');
        const getCart = () => {
            try { return JSON.parse(localStorage.getItem('pelanggan_cart') || '[]'); } catch { return []; }
        };
        const setCart = (arr) => {
            localStorage.setItem('pelanggan_cart', JSON.stringify(arr));
            window.pelangganRenderCart();
        };
        window.pelangganRemoveItem = (id) => {
            setCart(getCart().filter(i => i.id !== id));
        };
        window.pelangganInc = (id) => {
            const cart = getCart().map(i => i.id === id ? { ...i, qty: i.qty + 1 } : i);
            setCart(cart);
        };
        window.pelangganDec = (id) => {
            const cart = getCart().map(i => i.id === id ? { ...i, qty: Math.max(1, i.qty - 1) } : i);
            setCart(cart);
        };
        window.pelangganClearCart = () => {
            setCart([]);
        };
        window.pelangganRenderCart = () => {
            const tbody = document.getElementById('cartTableBody');
            const cart = getCart();
            tbody.innerHTML = cart.map(i => `
                <tr>
                    <td>${i.nama}</td>
                    <td>${fmt(i.harga)}</td>
                    <td>
                        <div class="join join-horizontal">
                            <button class="btn btn-xs join-item" onclick="window.pelangganDec(${i.id})">-</button>
                            <span class="px-2">${i.qty}</span>
                            <button class="btn btn-xs join-item" onclick="window.pelangganInc(${i.id})">+</button>
                        </div>
                    </td>
                    <td class="text-right">${fmt(i.harga * i.qty)}</td>
                    <td class="text-right">
                        <button class="btn btn-outline btn-xs" onclick="window.pelangganRemoveItem(${i.id})">Hapus</button>
                    </td>
                </tr>
            `).join('');
            const total = cart.reduce((s, i) => s + i.harga * i.qty, 0);
            document.getElementById('cartGrandTotal').textContent = fmt(total);
        };
        window.addEventListener('DOMContentLoaded', window.pelangganRenderCart);
    </script>
@endsection
