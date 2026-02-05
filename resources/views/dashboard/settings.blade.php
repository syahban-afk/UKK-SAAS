@extends('layouts.dashboard', ['title' => 'Settings'])

@section('menu')
    @include('menus.' . auth()->user()->level)
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Alert Messages -->
        @if (session('status'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 3a9 9 0 1 0 9 9a9 9 0 0 0 -9 -9" />
                    <path d="M9 12l2 2l4 -4" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                    <path d="M12 3a9 9 0 1 0 9 9a9 9 0 0 0 -9 -9" />
                    <path d="M12 9v4" />
                    <path d="M12 16h.01" />
                </svg>
                <div>
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            </div>
        @endif

        <div class="flex w-full flex-col lg:flex-row gap-4">
            <!-- Profile Settings -->
            <div class="flex-1">
                <div class="card bg-base-100 border border-base-300 shadow-lg">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="rounded-lg bg-primary/10 p-3 text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 12a5 5 0 1 0 -5 -5a5 5 0 0 0 5 5z" />
                                    <path d="M7 21h10a4 4 0 0 0 -4 -4h-2a4 4 0 0 0 -4 4" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="card-title">Profile Settings</h2>
                                <p class="text-sm text-base-content/70">Kelola informasi profil Anda</p>
                            </div>
                        </div>

                        <form method="POST"
                            action="{{ route('dashboard.' . auth()->user()->level . '.settings.profile') }}"
                            class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Nama Lengkap</span>
                                    </label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}"
                                        class="input input-bordered" required maxlength="30">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Email</span>
                                    </label>
                                    <input type="text" name="email" value="{{ auth()->user()->email }}"
                                        class="input input-bordered" required maxlength="30">
                                </div>
                            </div>
                            <div class="card-actions justify-end mt-6">
                                <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-4 w-4 mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M5 12l5 5l10 -10" />
                                    </svg>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="flex-1">
                <div class="card bg-base-100 border border-base-300 shadow-lg">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="rounded-lg bg-warning/10 p-3 text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 5m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" />
                                    <path d="M4 21v-2a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v2" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="card-title">Security Settings</h2>
                                <p class="text-sm text-base-content/70">Kelola keamanan akun Anda</p>
                            </div>
                        </div>
                        <form method="POST"
                            action="{{ route('dashboard.' . auth()->user()->level . '.settings.password') }}"
                            class="space-y-4">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex flex-col gap-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Password Baru</span>
                                    </label>
                                    <input type="password" name="password" class="input input-bordered" required
                                        minlength="6" placeholder="Minimal 6 karakter">
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="label">
                                        <span class="label-text font-medium">Konfirmasi Password</span>
                                    </label>
                                    <input type="password" name="password_confirmation" class="input input-bordered"
                                        required minlength="6" placeholder="Ulangi password baru">
                                </div>
                            </div>
                            <div class="card-actions justify-end mt-6">
                                <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white w-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-4 w-4 mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M6 10l4 4l8 -8" />
                                        <path d="M7 21h10a4 4 0 0 0 -4 -4h-2a4 4 0 0 0 -4 4" />
                                    </svg>
                                    Ganti Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Management -->
        <div class="card bg-base-100 border border-base-300 shadow-lg">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-6">
                    <div class="rounded-lg bg-error/10 p-3 text-error">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-6 w-6" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 9v4" />
                                    <path d="M10 17h4" />
                                    <path d="M5 19h14l-7 -15z" />
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
                            <span class="text-base-content/70">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                            <span class="font-medium">Email</span>
                            <span class="text-base-content/70">{{ auth()->user()->email }}</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                            <span class="font-medium">Role</span>
                            <span
                                class="badge badge-{{ auth()->user()->level == 'owner' ? 'error' : (auth()->user()->level == 'admin' ? 'warning' : 'info') }}">
                                {{ ucfirst(auth()->user()->level) }}
                            </span>
                        </div>
                    </div>

                    <div class="grid items-center justify-center">
                        <div class="flex-1">
                            <form method="POST"
                                action="{{ route('dashboard.' . auth()->user()->level . '.settings.deactivate') }}"
                                onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                <button type="submit" class="btn btn-error w-full text-white">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-4 w-4 mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M3 6l1 14a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2l1 -14" />
                                        <path d="M15 10v10" />
                                        <path d="M9 10v10" />
                                        <path d="M10 6h4l1 1h-6l1 -1" />
                                    </svg>
                                    Deactivate Account
                                </button>
                            </form>
                        </div>
                        <div class="flex-2">
                            <form method="POST"
                                action="{{ route('dashboard.' . auth()->user()->level . '.settings.logout-others') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icons-tabler-outline h-4 w-4 mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M14 8v-4h-8a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h8v-4" />
                                        <path d="M19 12h-13" />
                                        <path d="M16 15l3 -3l-3 -3" />
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
