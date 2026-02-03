@extends('layouts.auth', ['title' => 'Login'])
@section('content')
<div class="min-h-screen flex items-center justify-center bg-base-200">
    <div class="w-full max-w-2xl bg-base-100 rounded-3xl shadow-2xl overflow-hidden">
        <div class="p-8 md:p-12 flex flex-col justify-center">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900">Sign In</h1>
                <p class="text-gray-600 mt-1">
                    Masuk ke akun Anda untuk melanjutkan 🚀
                </p>
            </div>

            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

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
                </div>

                <button class="btn w-full mt-4 shadow-lg shadow-orange-200 bg-orange-600 hover:bg-orange-700 text-white border-0">
                    Sign In
                </button>
            </form>

            <p class="text-sm text-center mt-6 text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="link link-primary font-semibold">
                    Sign Up
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
