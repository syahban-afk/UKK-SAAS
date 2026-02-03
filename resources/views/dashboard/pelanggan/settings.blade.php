@extends('layouts.dashboard', ['title' => 'Settings Pelanggan'])

@section('menu')
    @include('menus.pelanggan')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
@php $pl = auth('pelanggan')->user(); @endphp
<div class="space-y-6">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Profile Settings</h2>
        <form method="POST" action="{{ route('dashboard.pelanggan.settings.profile') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Full Name</span></label>
                <input type="text" name="nama_pelanggan" value="{{ $pl->nama_pelanggan }}" class="input input-bordered focus:ring-2 focus:ring-orange-600" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Email</span></label>
                <input type="email" name="email" value="{{ $pl->email }}" class="input input-bordered focus:ring-2 focus:ring-orange-600" required>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Phone Number</span></label>
                <input type="text" name="telepon" value="{{ $pl->telepon }}" class="input input-bordered focus:ring-2 focus:ring-orange-600" required>
            </div>
            <div class="form-control md:col-span-2">
                <label class="label"><span class="label-text font-semibold">Alamat Utama</span></label>
                <textarea name="alamat1" class="textarea textarea-bordered h-24 focus:ring-orange-600" placeholder="Alamat Utama" required>{{ $pl->alamat1 }}</textarea>
                <input type="text" name="alamat2" class="input input-bordered mt-2 focus:ring-2 focus:ring-orange-600" placeholder="Detail Alamat 2 (Opsional)" value="{{ $pl->alamat2 }}">
                <input type="text" name="alamat3" class="input input-bordered mt-2 focus:ring-2 focus:ring-orange-600" placeholder="Detail Alamat 3 (Opsional)" value="{{ $pl->alamat3 }}">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-primary w-full shadow-lg shadow-orange-200">Simpan Perubahan</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Security Settings</h2>
        <form method="POST" action="{{ route('dashboard.pelanggan.settings.password') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Password Baru</span></label>
                <input type="password" name="password" class="input input-bordered focus:ring-2 focus:ring-orange-600" required minlength="6">
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Konfirmasi Password</span></label>
                <input type="password" name="password_confirmation" class="input input-bordered focus:ring-2 focus:ring-orange-600" required minlength="6">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white border-0 shadow-lg shadow-orange-200">Ganti Password</button>
            </div>
        </form>
        <div class="mt-4">
            <form method="POST" action="{{ route('dashboard.pelanggan.settings.logout-others') }}">
                @csrf
                <button type="submit" class="btn btn-outline">Logout dari semua perangkat</button>
            </form>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Order Preferences</h2>
        <form method="POST" action="{{ route('dashboard.pelanggan.settings.default-address') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Default Delivery Address</span></label>
                <select name="default_address" class="select select-bordered">
                    <option value="alamat1" selected>Alamat 1 (Utama)</option>
                    @if ($pl->alamat2)
                        <option value="alamat2">Alamat 2</option>
                    @endif
                    @if ($pl->alamat3)
                        <option value="alamat3">Alamat 3</option>
                    @endif
                </select>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Contact Preferences</span></label>
                <div class="join join-vertical md:join-horizontal" id="contactPrefForm" data-user-id="{{ $pl->id }}">
                    <input class="join-item btn" type="radio" name="contact_pref" value="email" aria-label="Email">
                    <input class="join-item btn" type="radio" name="contact_pref" value="whatsapp" aria-label="WhatsApp">
                    <input class="join-item btn" type="radio" name="contact_pref" value="sms" aria-label="SMS">
                </div>
                <p class="text-xs text-gray-500 mt-1">Preferensi kontak disimpan di perangkat ini.</p>
            </div>
            <div class="md:col-span-2 flex gap-3">
                <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white border-0 shadow-lg shadow-orange-200">Simpan Default Address</button>
                <button type="button" class="btn btn-outline" id="saveContactPrefsBtn">Simpan Contact Preferences</button>
            </div>
        </form>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Account Management</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <p class="text-gray-600"><span class="font-semibold">Nama:</span> {{ $pl->nama_pelanggan }}</p>
                <p class="text-gray-600"><span class="font-semibold">Email:</span> {{ $pl->email }}</p>
                <p class="text-gray-600"><span class="font-semibold">Telepon:</span> {{ $pl->telepon }}</p>
            </div>
            <div class="flex md:justify-end">
                <form method="POST" action="{{ route('dashboard.pelanggan.settings.deactivate') }}">
                    @csrf
                    <button type="submit" class="btn btn-error">Deactivate account</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
