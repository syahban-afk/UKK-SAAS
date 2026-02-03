@extends('layouts.auth', ['title' => 'Register'])
@section('content')
<div class="min-h-screen flex items-center justify-center bg-base-200">
    <div class="w-full max-w-2xl bg-base-100 rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-8 md:p-12 flex flex-col justify-center">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Sign Up</h1>
                <p class="text-gray-600 mt-1">
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
                        class="input input-bordered w-full focus:ring-2 focus:ring-orange-600"
                        placeholder="Nama lengkap" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Email</span>
                    </label>
                    <input type="email" name="email"
                        value="{{ old('email') }}"
                        class="input input-bordered w-full focus:ring-2 focus:ring-orange-600"
                        placeholder="nama@email.com" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Password</span>
                    </label>
                    <input type="password" name="password"
                        class="input input-bordered w-full focus:ring-2 focus:ring-orange-600"
                        placeholder="••••••••" required>
                    <label class="label">
                        <span class="label-text-alt text-gray-600">
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
                            class="input input-bordered w-full focus:ring-2 focus:ring-orange-600" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Telepon</span>
                        </label>
                        <input type="text" name="telepon"
                            value="{{ old('telepon') }}"
                            class="input input-bordered w-full focus:ring-2 focus:ring-orange-600"
                            placeholder="08xxxxxxxxxx" required>
                    </div>
                </div>

                <button class="btn w-full mt-4 shadow-lg shadow-orange-200 bg-orange-600 hover:bg-orange-700 text-white border-0">
                    Sign Up
                </button>
            </form>

            <p class="text-sm text-center mt-6 text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="link link-primary font-semibold">
                    Sign in
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
