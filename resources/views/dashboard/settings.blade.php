@extends('layouts.dashboard', ['title' => 'Settings'])

@section('menu')
    @if(auth('pelanggan')->check())
        @include('menus.pelanggan')
    @else
        @include('menus.' . auth()->user()->level)
    @endif
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        <!-- Alert Messages -->
        @if(session('status'))
            <div class="alert alert-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    @foreach($errors->all() as $error)
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="card-title">Profile Settings</h2>
                                <p class="text-sm text-base-content/70">Kelola informasi profil Anda</p>
                            </div>
                        </div>
                        
                        @if(auth('pelanggan')->check())
                            @php $pl = auth('pelanggan')->user(); @endphp
                            <form method="POST" action="{{ route('dashboard.pelanggan.settings.profile') }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Nama Lengkap</span>
                                        </label>
                                        <input type="text" name="nama_pelanggan" value="{{ $pl->nama_pelanggan }}" 
                                               class="input input-bordered" required>
                                    </div>
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Email</span>
                                        </label>
                                        <input type="email" name="email" value="{{ $pl->email }}" 
                                               class="input input-bordered" required>
                                    </div>
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Telepon</span>
                                        </label>
                                        <input type="text" name="telepon" value="{{ $pl->telepon }}" 
                                               class="input input-bordered" required>
                                    </div>
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Foto Profil</span>
                                        </label>
                                        <input type="url" name="foto" value="{{ $pl->foto ?? '' }}" 
                                               class="input input-bordered" placeholder="https://example.com/photo.jpg">
                                    </div>
                                    <div class="form-control md:col-span-2">
                                        <label class="label">
                                            <span class="label-text font-medium">Alamat Utama</span>
                                        </label>
                                        <textarea name="alamat1" class="textarea textarea-bordered h-20" 
                                                  placeholder="Alamat lengkap" required>{{ $pl->alamat1 }}</textarea>
                                    </div>
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Alamat 2 (Opsional)</span>
                                        </label>
                                        <input type="text" name="alamat2" value="{{ $pl->alamat2 }}" 
                                               class="input input-bordered" placeholder="Detail alamat tambahan">
                                    </div>
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Alamat 3 (Opsional)</span>
                                        </label>
                                        <input type="text" name="alamat3" value="{{ $pl->alamat3 }}" 
                                               class="input input-bordered" placeholder="Detail alamat tambahan">
                                    </div>
                                </div>
                                <div class="card-actions justify-end mt-6">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        @else
                            <form method="POST" action="{{ route('dashboard.' . auth()->user()->level . '.settings.profile') }}" class="space-y-4">
                                @csrf
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Nama Lengkap</span>
                                        </label>
                                        <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                               class="input input-bordered" required maxlength="30">
                                    </div>
                                    <div class="form-control">
                                        <label class="label">
                                            <span class="label-text font-medium">Email</span>
                                        </label>
                                        <input type="email" name="email" value="{{ auth()->user()->email }}" 
                                               class="input input-bordered" required>
                                    </div>
                                    <div class="form-control md:col-span-2">
                                        <label class="label">
                                            <span class="label-text font-medium">Telepon</span>
                                        </label>
                                        <input type="tel" name="telepon" value="{{ auth()->user()->telepon ?? '' }}" 
                                               class="input input-bordered" placeholder="Nomor telepon">
                                    </div>
                                </div>
                                <div class="card-actions justify-end mt-6">
                                    <button type="submit" class="btn btn-primary">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="flex-1">
                <div class="card bg-base-100 border border-base-300 shadow-lg">
                    <div class="card-body">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="rounded-lg bg-warning/10 p-3 text-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="card-title">Security Settings</h2>
                                <p class="text-sm text-base-content/70">Kelola keamanan akun Anda</p>
                            </div>
                        </div>
                        
                        @if(auth('pelanggan')->check())
                            <form method="POST" action="{{ route('dashboard.pelanggan.settings.password') }}" class="space-y-4">
                                @csrf
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Password Baru</span>
                                    </label>
                                    <input type="password" name="password" class="input input-bordered" 
                                           required minlength="6" placeholder="Minimal 6 karakter">
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Konfirmasi Password</span>
                                    </label>
                                    <input type="password" name="password_confirmation" class="input input-bordered" 
                                           required minlength="6" placeholder="Ulangi password baru">
                                </div>
                                <div class="card-actions">
                                    <button type="submit" class="btn btn-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Ganti Password
                                    </button>
                                </div>
                            </form>
                            <div class="divider">OR</div>
                            <form method="POST" action="{{ route('dashboard.pelanggan.settings.logout-others') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout dari Semua Perangkat
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('dashboard.' . auth()->user()->level . '.settings.password') }}" class="space-y-4">
                                @csrf
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Password Baru</span>
                                    </label>
                                    <input type="password" name="password" class="input input-bordered" 
                                           required minlength="6" placeholder="Minimal 6 karakter">
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-medium">Konfirmasi Password</span>
                                    </label>
                                    <input type="password" name="password_confirmation" class="input input-bordered" 
                                           required minlength="6" placeholder="Ulangi password baru">
                                </div>
                                <div class="card-actions">
                                    <button type="submit" class="btn btn-warning">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                                        </svg>
                                        Ganti Password
                                    </button>
                                </div>
                            </form>
                            <div class="divider">OR</div>
                            <form method="POST" action="{{ route('dashboard.' . auth()->user()->level . '.settings.logout-others') }}">
                                @csrf
                                <button type="submit" class="btn btn-outline btn-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout dari Semua Perangkat
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Management -->
        <div class="card bg-base-100 border border-base-300 shadow-lg">
            <div class="card-body">
                <div class="flex items-center gap-3 mb-6">
                    <div class="rounded-lg bg-error/10 p-3 text-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="card-title">Account Management</h2>
                        <p class="text-sm text-base-content/70">Informasi dan pengaturan akun</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3">
                        @if(auth('pelanggan')->check())
                            @php $pl = auth('pelanggan')->user(); @endphp
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <span class="font-medium">Nama</span>
                                <span class="text-base-content/70">{{ $pl->nama_pelanggan }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <span class="font-medium">Email</span>
                                <span class="text-base-content/70">{{ $pl->email }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <span class="font-medium">Telepon</span>
                                <span class="text-base-content/70">{{ $pl->telepon }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                                <span class="font-medium">Role</span>
                                <span class="badge badge-primary">Pelanggan</span>
                            </div>
                        @else
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
                                <span class="badge badge-{{ auth()->user()->level == 'owner' ? 'error' : (auth()->user()->level == 'admin' ? 'warning' : 'info') }}">
                                    {{ ucfirst(auth()->user()->level) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-center">
                        @if(auth('pelanggan')->check())
                            <form method="POST" action="{{ route('dashboard.pelanggan.settings.deactivate') }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                <button type="submit" class="btn btn-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Deactivate Account
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('dashboard.' . auth()->user()->level . '.settings.deactivate') }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menonaktifkan akun? Tindakan ini tidak dapat dibatalkan.')">
                                @csrf
                                <button type="submit" class="btn btn-error">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Deactivate Account
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
