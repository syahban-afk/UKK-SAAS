@extends('layouts.auth', ['title' => 'Register'])
@section('content')
<div class="min-h-screen flex items-center justify-center bg-base-200">
    <div class="w-full max-w-2xl bg-base-100 rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-8 md:p-12 flex flex-col justify-center">
            <div class="mb-6">
                <h1 class="text-3xl font-bold">Sign Up</h1>
                <p class="text-base-content/60 mt-1">
                    Buat akun baru untuk mulai 🚀
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-4">
                @csrf

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Nama Lengkap</span>
                    </label>
                    <input type="text" name="nama_pelanggan"
                        value="{{ old('nama_pelanggan') }}"
                        class="input input-bordered w-full"
                        placeholder="Nama lengkap" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email"
                        value="{{ old('email') }}"
                        class="input input-bordered w-full"
                        placeholder="nama@email.com" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" name="password"
                        class="input input-bordered w-full"
                        placeholder="••••••••" required>
                    <label class="label">
                        <span class="label-text-alt text-base-content/60">
                            Minimal 8 karakter
                        </span>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Tanggal Lahir</span>
                        </label>
                        <input type="date" name="tgl_lahir"
                            value="{{ old('tgl_lahir') }}"
                            class="input input-bordered w-full" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Telepon</span>
                        </label>
                        <input type="text" name="telepon"
                            value="{{ old('telepon') }}"
                            class="input input-bordered w-full"
                            placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <button class="btn btn-primary w-full mt-4">
                    Sign Up
                </button>
            </form>

            <p class="text-sm text-center mt-6 text-base-content/70">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="link link-primary font-semibold">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
