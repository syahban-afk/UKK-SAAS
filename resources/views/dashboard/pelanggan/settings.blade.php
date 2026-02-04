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

        {{-- PROFILE SETTINGS --}}

        <div class="flex-1">
            <div class="card bg-base-100 border border-base-300 shadow-lg">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="rounded-lg bg-primary/10 p-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="card-title">Profile Settings</h2>
                            <p class="text-sm text-base-content/70">Kelola informasi profil Anda</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('dashboard.pelanggan.settings.profile') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                            <div class="flex flex-col gap-2">
                                <label class="label"><span class="label-text font-medium">Nama Lengkap</span></label>
                                <input type="text" name="nama_pelanggan" value="{{ $pl->nama_pelanggan }}"
                                    class="input input-bordered" required>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label class="label"><span class="label-text font-medium">Email</span></label>
                                <input type="text" name="email" value="{{ $pl->email }}"
                                    class="input input-bordered" required>
                            </div>

                            <div class="flex flex-col gap-2">
                                <label class="label"><span class="label-text font-medium">Telepon</span></label>
                                <input type="text" name="telepon" value="{{ $pl->telepon }}"
                                    class="input input-bordered" required>
                            </div>

                            <div class="form-control md:col-span-3">
                                <label class="label"><span class="label-text font-semibold">Alamat Utama</span></label>
                                <textarea name="alamat1" class="textarea textarea-bordered w-full h-24 resize-none">{{ $pl->alamat1 }}</textarea>
                            </div>

                            <div class="md:col-span-3">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold">Alamat 2 (Opsional)</span>
                                        </label>
                                        <textarea name="alamat2" class="textarea textarea-bordered w-full h-24 resize-none">{{ $pl->alamat2 }}</textarea>
                                    </div>

                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-semibold">Alamat 3 (Opsional)</span>
                                        </label>
                                        <textarea name="alamat3" class="textarea textarea-bordered w-full h-24 resize-none">{{ $pl->alamat3 }}</textarea>
                                    </div>

                                </div>
                            </div>

                            <div class="hidden md:block"></div>

                        </div>
                        <div class="card-actions justify-end mt-6">
                            <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white w-full text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- SECURITY + ORDER (SIDE BY SIDE) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- SECURITY SETTINGS --}}
            <div class="card bg-base-100 border border-base-300 shadow-lg">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="card-title">Security Settings</h2>
                            <p class="text-sm text-base-content/70">Kelola keamanan akun Anda</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('dashboard.pelanggan.settings.password') }}" class="space-y-4">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex flex-col gap-2">
                                <label class="label">
                                    <span class="label-text font-medium">Password Baru</span>
                                </label>
                                <input type="password" name="password" class="input input-bordered" required minlength="6"
                                    placeholder="Minimal 6 karakter">
                            </div>
                            <div class="flex flex-col gap-2">
                                <label class="label">
                                    <span class="label-text font-medium">Konfirmasi Password</span>
                                </label>
                                <input type="password" name="password_confirmation" class="input input-bordered" required
                                    minlength="6" placeholder="Ulangi password baru">
                            </div>
                        </div>
                        <div class="card-actions justify-end mt-6">
                            <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white text-white w-full">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                </svg>
                                Ganti Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- ORDER PREFERENCES --}}
            <div class="card bg-base-100 border border-base-300 shadow-lg">
                <div class="card-body">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-home">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M5 12l-2 0l9 -9l9 9l-2 0" />
                                <path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" />
                                <path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="card-title">Order Preferences</h2>
                            <p class="text-sm text-base-content/70">Kelola preferensi alamat Anda</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('dashboard.pelanggan.settings.default-address') }}"
                        class="space-y-4">
                        @csrf
                        <div class="w-full">
                            <label class="label">
                                <span class="label-text font-medium">Default Delivery Address</span>
                            </label>
                            <select name="default_address" class="select select-bordered w-full mt-2">
                                <option value="alamat1">Alamat 1 (Utama)</option>
                                @if ($pl->alamat2)
                                    <option value="alamat2">Alamat 2</option>
                                @endif
                                @if ($pl->alamat3)
                                    <option value="alamat3">Alamat 3</option>
                                @endif
                            </select>
                        </div>
                        <div class="card-actions justify-end mt-6">
                            <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white text-white w-full">
                                Simpan Preferensi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- ACCOUNT MANAGEMENT --}}
        <div class="card bg-base-100 border border-base-300 shadow-lg">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-6">
                    <div class="rounded-lg bg-error/10 p-3 text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="card-title">Account Management</h2>
                        <p class="text-sm text-base-content/70">Informasi dan pengaturan akun</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                            <span class="font-medium">Nama</span>
                            <span class="text-base-content/70">{{ $pl->nama_pelanggan }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                            <span class="font-medium">Email</span>
                            <span class="text-base-content/70">{{ $pl->email }}</span>
                        </div>
                    </div>

                    <div class="grid items-center justify-center">
                        <div class="flex-1">
                            <form method="POST" action="{{ route('dashboard.pelanggan.settings.deactivate') }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                <button type="submit" class="btn btn-error w-full text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Deactivate Account
                                </button>
                            </form>
                        </div>
                        <div class="flex-2">
                            <form method="POST" action="{{ route('dashboard.pelanggan.settings.logout-others') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout dari Semua Perangkat
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
