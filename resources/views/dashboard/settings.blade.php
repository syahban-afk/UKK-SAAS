@extends('layouts.dashboard', ['title' => 'Settings'])

@section('menu')
    @include('menus.' . auth()->user()->level)
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">

        <div class="flex w-full flex-col lg:flex-row gap-2 lg:gap-4">

            <div class="flex-1 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Profile Settings</h2>
                <form method="POST" action="{{ route('dashboard.' . auth()->user()->level . '.settings.profile') }}"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Full Name</span></label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}"
                            class="input input-bordered focus:ring-2 focus:ring-orange-600">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Email</span></label>
                        <input type="email" name="email" value="{{ auth()->user()->email }}"
                            class="input input-bordered focus:ring-2 focus:ring-orange-600">
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label"><span class="label-text font-semibold">Telepon</span></label>
                        <input type="text" value="" class="input input-bordered"
                            placeholder="Belum tersedia untuk user" disabled>
                    </div>
                    <div class="md:col-span-2">
                        <button type="submit"
                            class="btn bg-orange-600 hover:bg-orange-700 w-full shadow-lg shadow-orange-200 text-white">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>

            <div class="flex-1 bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Security Settings</h2>
                <form method="POST" action="{{ route('dashboard.' . auth()->user()->level . '.settings.password') }}"
                    class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Password Baru</span></label>
                        <input type="password" name="password"
                            class="input input-bordered focus:ring-2 focus:ring-orange-600" required minlength="6">
                    </div>
                    <div class="form-control">
                        <label class="label"><span class="label-text font-semibold">Konfirmasi Password</span></label>
                        <input type="password" name="password_confirmation"
                            class="input input-bordered focus:ring-2 focus:ring-orange-600" required minlength="6">
                    </div>

                    <div class="md:col-span-2">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <div class="flex-1">
                                <button type="submit"
                                    class="flex-1 w-full btn bg-orange-600 hover:bg-orange-700 text-white border-0 shadow-lg shadow-orange-200">Ganti
                                    Password</button>
                            </div>

                </form>
                <div class="flex-1">
                    <form method="POST"
                        action="{{ route('dashboard.' . auth()->user()->level . '.settings.logout-others') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline w-full">Logout dari semua perangkat</button>
                    </form>
                </div>
            </div>
        </div>

    </div>

    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Account Management</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="space-y-2">
                <p class="text-gray-600"><span class="font-semibold">Nama:</span> {{ auth()->user()->name }}</p>
                <p class="text-gray-600"><span class="font-semibold">Email:</span> {{ auth()->user()->email }}</p>
                <p class="text-gray-600"><span class="font-semibold">Role:</span> {{ auth()->user()->level }}</p>
            </div>
            <div class="flex md:justify-end">
                <form method="POST" action="{{ route('dashboard.' . auth()->user()->level . '.settings.deactivate') }}">
                    @csrf
                    <button type="submit" class="btn btn-error">Deactivate account</button>
                </form>
            </div>
        </div>
    </div>
    </div>
@endsection
