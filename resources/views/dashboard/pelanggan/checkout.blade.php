@extends('layouts.dashboard', ['title' => 'Checkout'])

@section('menu')
    @include('menus.pelanggan')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Alert Container -->
        <div id="alertContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <div class="grid grid-cols-4 gap-4 text-center">
                <div id="step_cart" class="p-3 rounded-xl bg-orange-50 text-orange-700 font-semibold cursor-pointer">Keranjang</div>
                <div id="step_address" class="p-3 rounded-xl bg-gray-50 text-gray-600 cursor-pointer">Alamat</div>
                <div id="step_payment" class="p-3 rounded-xl bg-gray-50 text-gray-600 cursor-pointer">Pembayaran</div>
                <div id="step_confirm" class="p-3 rounded-xl bg-gray-50 text-gray-600 cursor-pointer">Konfirmasi</div>
            </div>
        </div>

        <div id="view_cart" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Belanjaan Saya</h2>
                <div id="cartList" class="space-y-4"></div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Ringkasan</h2>
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between"><span>Subtotal</span><span id="sumSubtotal">Rp 0</span></div>
                    <div class="flex justify-between"><span>Ongkir</span><span>Gratis</span></div>
                </div>
                <div class="flex justify-between items-center font-bold text-lg mt-4">
                    <span>Total</span><span id="sumTotal">Rp 0</span>
                </div>
                <button id="btnToAddress" class="btn bg-orange-600 hover:bg-orange-700 text-white w-full mt-4">Pilih Alamat</button>
            </div>
        </div>

        <div id="view_address" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Pilih Alamat</h2>
                <div class="space-y-3">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200">
                        <input type="radio" name="use_address" value="alamat1" {{ $pl->alamat1 ? 'checked' : 'disabled' }}>
                        <div>
                            <div class="font-semibold">Alamat 1</div>
                            <div class="text-sm text-gray-600">{{ $pl->alamat1 ?: 'Tidak tersedia' }}</div>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200">
                        <input type="radio" name="use_address" value="alamat2" {{ $pl->alamat2 ? '' : 'disabled' }}>
                        <div>
                            <div class="font-semibold">Alamat 2</div>
                            <div class="text-sm text-gray-600">{{ $pl->alamat2 ?: 'Tidak tersedia' }}</div>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200">
                        <input type="radio" name="use_address" value="alamat3" {{ $pl->alamat3 ? '' : 'disabled' }}>
                        <div>
                            <div class="font-semibold">Alamat 3</div>
                            <div class="text-sm text-gray-600">{{ $pl->alamat3 ?: 'Tidak tersedia' }}</div>
                        </div>
                    </label>
                </div>
                <div class="text-sm text-gray-500 mt-4">Edit alamat hanya di halaman Settings.</div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between"><span>Subtotal</span><span id="sumSubtotal2">Rp 0</span></div>
                    <div class="flex justify-between"><span>Ongkir</span><span>Gratis</span></div>
                </div>
                <div class="flex justify-between items-center font-bold text-lg mt-4">
                    <span>Total</span><span id="sumTotal2">Rp 0</span>
                </div>
                <button id="btnToPayment" class="btn bg-orange-600 hover:bg-orange-700 text-white w-full mt-4">Pilih Pembayaran</button>
            </div>
        </div>

        <div id="view_payment" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Metode Pembayaran</h2>
                <div class="space-y-3">
                    @foreach ($paymentMethods as $m)
                        <label class="flex items-start gap-3 p-3 rounded-xl border border-gray-200">
                            <input type="radio" name="payment_id" value="{{ $m->id }}" {{ $loop->first ? 'checked' : '' }}>
                            <div class="flex-1">
                                <div class="font-semibold">{{ $m->metode_pembayaran }}</div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-2">
                                    @foreach ($m->detailJenisPembayarans as $d)
                                        <div class="flex items-center gap-2 bg-gray-50 rounded-lg p-2">
                                            <img src="{{ $d->logo }}" class="h-6 w-6 object-contain">
                                            <div class="text-xs text-gray-600">{{ $d->tempat_bayar }}</div>
                                            <div class="ml-auto text-xs font-mono text-gray-500">{{ $d->no_rek }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between"><span>Subtotal</span><span id="sumSubtotal3">Rp 0</span></div>
                    <div class="flex justify-between"><span>Ongkir</span><span>Gratis</span></div>
                </div>
                <div class="flex justify-between items-center font-bold text-lg mt-4">
                    <span>Total</span><span id="sumTotal3">Rp 0</span>
                </div>
                <button id="btnToConfirm" class="btn bg-orange-600 hover:bg-orange-700 text-white w-full mt-4">Konfirmasi</button>
            </div>
        </div>

        <div id="view_confirm" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-4">Konfirmasi Pesanan</h2>
                <div id="confirmList" class="space-y-3"></div>
                <div class="mt-4 text-sm text-gray-600">
                    <div>Nama: {{ $pl->nama_pelanggan }}</div>
                    <div>Telepon: {{ $pl->telepon }}</div>
                    <div id="confirmAddress"></div>
                    <div id="confirmPayment"></div>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="space-y-2 text-sm text-gray-600">
                    <div class="flex justify-between"><span>Subtotal</span><span id="sumSubtotal4">Rp 0</span></div>
                    <div class="flex justify-between"><span>Ongkir</span><span>Gratis</span></div>
                </div>
                <div class="flex justify-between items-center font-bold text-lg mt-4">
                    <span>Total</span><span id="sumTotal4">Rp 0</span>
                </div>
                <button id="btnPlaceOrder" class="btn bg-orange-600 hover:bg-orange-700 text-white w-full mt-4">Buat Pesanan</button>
            </div>
        </div>
    </div>
    <script>
        // Alert System
        const showAlert = (message, type = 'info') => {
            const container = document.getElementById('alertContainer');
            const alertId = 'alert-' + Date.now();

            const alertTypes = {
                success: 'alert-success',
                error: 'alert-error',
                warning: 'alert-warning',
                info: 'alert-info'
            };

            const alertIcons = {
                success: `
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 3a9 9 0 1 0 9 9a9 9 0 0 0 -9 -9" />
                    <path d="M9 12l2 2l4 -4" />
                `,
                error: `
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 3a9 9 0 1 0 9 9a9 9 0 0 0 -9 -9" />
                    <path d="M10 10l4 4m0 -4l-4 4" />
                `,
                warning: `
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 9v4" />
                    <path d="M12 16h.01" />
                    <path d="M5 19h14l-7 -15z" />
                `,
                info: `
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 3a9 9 0 1 0 9 9a9 9 0 0 0 -9 -9" />
                    <path d="M12 12v-3" />
                    <path d="M12 16h.01" />
                `
            };

            const alertHtml = `
                <div id="${alertId}" class="alert ${alertTypes[type]} shadow-lg animate-pulse">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline shrink-0 h-6 w-6" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        ${alertIcons[type]}
                    </svg>
                    <span>${message}</span>
                    <button onclick="document.getElementById('${alertId}').remove()" class="btn btn-sm btn-ghost btn-circle">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-4 w-4" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                            <path d="M18 6l-12 12" />
                            <path d="M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', alertHtml);

            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = document.getElementById(alertId);
                if (alert) alert.remove();
            }, 5000);
        };

        const fmtIDR = (n) => 'Rp ' + (n || 0).toLocaleString('id-ID');
        const getCart = () => { try { return JSON.parse(localStorage.getItem('pelanggan_cart') || '[]'); } catch { return []; } };
        let currentStep = 'cart';
        const stepsOrder = ['cart','address','payment','confirm'];
        const isStepValid = (step) => {
            if (step === 'cart') return selectedItems().length > 0;
            if (step === 'address') {
                const el = document.querySelector('input[name="use_address"]:checked');
                return !!el && !el.disabled;
            }
            if (step === 'payment') {
                const el = document.querySelector('input[name="payment_id"]:checked');
                return !!el;
            }
            return true;
        };
        const setStepActive = (step) => {
            currentStep = step;
            stepsOrder.forEach((s, idx) => {
                const isCurrent = s === step;
                const isCompleted = stepsOrder.indexOf(step) > idx;
                const header = document.getElementById('step_'+s);
                const view = document.getElementById('view_'+s);
                header.className = 'p-3 rounded-xl ' +
                    (isCurrent ? 'bg-orange-50 text-orange-700 font-semibold' :
                     isCompleted ? 'bg-orange-100 text-orange-700' :
                     'bg-gray-50 text-gray-600') +
                    ' cursor-pointer';
                view.className = (isCurrent ? '' : 'hidden') + ' grid grid-cols-1 lg:grid-cols-3 gap-6';
            });
        };
        const goToStep = (step) => {
            const targetIdx = stepsOrder.indexOf(step);
            const currentIdx = stepsOrder.indexOf(currentStep);
            if (targetIdx > currentIdx && !isStepValid(currentStep)) {
                showAlert('Lengkapi data pada langkah saat ini terlebih dahulu', 'warning');
                return;
            }
            setStepActive(step);
        };
        const renderCart = () => {
            const list = document.getElementById('cartList');
            const cart = getCart();
            if (!cart.length) { list.innerHTML = '<div class="text-center text-gray-500 py-12">Keranjang kosong</div>'; }
            else {
                list.innerHTML = cart.map(i => `
                    <label class="flex items-center gap-4 p-3 rounded-xl border border-gray-200">
                        <input type="checkbox" class="checkout_item" data-id="${i.id}" checked>
                        <img src="${i.img || 'https://via.placeholder.com/48?text=Menu'}" class="h-12 w-12 rounded-lg object-cover">
                        <div class="flex-1">
                            <div class="font-semibold">${i.nama}</div>
                            <div class="text-xs text-gray-600">${fmtIDR(i.harga)} x ${i.qty}</div>
                        </div>
                        <div class="font-bold">${fmtIDR(i.harga * i.qty)}</div>
                    </label>
                `).join('');
            }
            updateSummary();
        };
        const selectedItems = () => {
            const cart = getCart();
            const ids = Array.from(document.querySelectorAll('.checkout_item')).filter(el => el.checked).map(el => parseInt(el.dataset.id,10));
            return cart.filter(i => ids.includes(i.id));
        };
        const updateSummary = () => {
            const items = selectedItems();
            const total = items.reduce((a,b)=>a+(b.harga*b.qty),0);
            ['sumSubtotal','sumTotal','sumSubtotal2','sumTotal2','sumSubtotal3','sumTotal3','sumSubtotal4','sumTotal4'].forEach(id => {
                document.getElementById(id).textContent = fmtIDR(total);
            });
        };
        document.getElementById('btnToAddress').onclick = () => {
            if (!selectedItems().length) {
                showAlert('Pilih minimal satu item untuk melanjutkan', 'error');
                return;
            }
            setStepActive('address');
        };
        document.getElementById('btnToPayment').onclick = () => {
            const el = document.querySelector('input[name="use_address"]:checked');
            if (!el || el.disabled) {
                showAlert('Pilih alamat yang tersedia untuk melanjutkan', 'error');
                return;
            }
            setStepActive('payment');
        };
        document.getElementById('btnToConfirm').onclick = () => {
            const items = selectedItems();
            document.getElementById('confirmList').innerHTML = items.map(i => `
                <div class="flex justify-between p-2 rounded-lg border border-gray-200">
                    <div>${i.nama}</div>
                    <div>${fmtIDR(i.harga)} x ${i.qty}</div>
                </div>
            `).join('');
            const addrKey = document.querySelector('input[name="use_address"]:checked')?.value || 'alamat1';
            const addrVal = addrKey==='alamat1' ? '{{ $pl->alamat1 }}' : (addrKey==='alamat2' ? '{{ $pl->alamat2 }}' : '{{ $pl->alamat3 }}');
            document.getElementById('confirmAddress').textContent = 'Alamat: ' + addrVal;
            const payEl = document.querySelector('input[name="payment_id"]:checked');
            document.getElementById('confirmPayment').textContent = 'Pembayaran: ' + (payEl ? payEl.parentElement.querySelector('.font-semibold').textContent : '');
            setStepActive('confirm');
        };
        document.getElementById('btnPlaceOrder').onclick = async () => {
            const items = selectedItems();
            if (!items.length) {
                showAlert('Tidak ada item yang dipilih', 'error');
                return;
            }

            // Show loading
            const btn = document.getElementById('btnPlaceOrder');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="loading loading-spinner"></span> Memproses...';
            btn.disabled = true;

            try {
                const addrKey = document.querySelector('input[name="use_address"]:checked')?.value || 'alamat1';
                const payId = parseInt(document.querySelector('input[name="payment_id"]:checked')?.value || '1', 10);
                const payload = {
                    use_address: addrKey,
                    payment_id: payId,
                    cart: items.map(i => ({ id: i.id, qty: i.qty })),
                };

                const res = await fetch('{{ route('dashboard.pelanggan.checkout.store') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify(payload),
                });

                if (!res.ok) {
                    const t = await res.text();
                    showAlert('Gagal membuat pesanan: ' + t, 'error');
                    return;
                }

                const data = await res.json();
                localStorage.removeItem('pelanggan_cart');
                showAlert(data.message + ' No. Resi: ' + data.no_resi, 'success');

                // Redirect after success
                setTimeout(() => {
                    window.location.href = '{{ route('dashboard.pelanggan.status') }}';
                }, 2000);

            } catch (error) {
                showAlert('Terjadi kesalahan. Silakan coba lagi.', 'error');
                console.error('Checkout error:', error);
            } finally {
                // Restore button
                btn.innerHTML = originalText;
                btn.disabled = false;
            }
        };
        renderCart();
        setStepActive('cart');
        document.getElementById('step_cart').onclick = () => goToStep('cart');
        document.getElementById('step_address').onclick = () => goToStep('address');
        document.getElementById('step_payment').onclick = () => goToStep('payment');
        document.getElementById('step_confirm').onclick = () => goToStep('confirm');
        document.addEventListener('change', (e) => {
            if (e.target.classList.contains('checkout_item')) updateSummary();
        });
    </script>
@endsection
