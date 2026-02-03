@extends('layouts.dashboard', ['title' => 'Checkout'])

@section('menu')
    @include('menus.pelanggan')
@endsection

@section('content')
    <div class="space-y-6">
        @php $pl = auth('pelanggan')->user(); @endphp
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <h2 class="card-title">Checkout</h2>
                <form onsubmit="window.pelangganSubmitCheckout(event)" class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label"><span class="label-text">Nama</span></label>
                        <input type="text" id="co_nama" class="input input-bordered" value="{{ $pl->nama_pelanggan }}" required>
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text">Telepon</span></label>
                        <input type="text" id="co_telepon" class="input input-bordered" value="{{ $pl->telepon }}" required>
                    </div>
                    <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label"><span class="label-text">Alamat 1</span></label>
                            <input type="text" id="co_alamat1" class="input input-bordered" value="{{ $pl->alamat1 }}" required>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Alamat 2</span></label>
                            <input type="text" id="co_alamat2" class="input input-bordered" value="{{ $pl->alamat2 }}">
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Alamat 3</span></label>
                            <input type="text" id="co_alamat3" class="input input-bordered" value="{{ $pl->alamat3 }}">
                        </div>
                    </div>
                    <div class="sm:col-span-2 flex items-center justify-end pt-2">
                        <button type="submit" class="btn btn-primary">Buat Pesanan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        const getCart = () => {
            try { return JSON.parse(localStorage.getItem('pelanggan_cart') || '[]'); } catch { return []; }
        };
        window.pelangganSubmitCheckout = async (e) => {
            e.preventDefault();
            const cart = getCart();
            if (!cart.length) {
                alert('Keranjang kosong');
                return;
            }
            const payload = {
                nama: document.getElementById('co_nama').value,
                telepon: document.getElementById('co_telepon').value,
                alamat1: document.getElementById('co_alamat1').value,
                alamat2: document.getElementById('co_alamat2').value,
                alamat3: document.getElementById('co_alamat3').value,
                cart: cart.map(i => ({ id: i.id, qty: i.qty })),
            };
            const res = await fetch('{{ route('dashboard.pelanggan.checkout.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(payload),
            });
            if (!res.ok) {
                const t = await res.text();
                alert('Gagal membuat pesanan: ' + t);
                return;
            }
            const data = await res.json();
            localStorage.removeItem('pelanggan_cart');
            alert(data.message + ' No. Resi: ' + data.no_resi);
            window.location.href = '{{ route('dashboard.pelanggan.status') }}';
        };
    </script>
@endsection
