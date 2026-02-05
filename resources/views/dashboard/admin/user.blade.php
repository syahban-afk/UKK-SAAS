@extends('layouts.dashboard', ['title' => 'Manajemen Kurir'])

@section('menu')
    @include('menus.admin')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Manajemen Kurir</h1>
            <button class="btn bg-orange-600 hover:bg-orange-700 text-white btn-sm"
                onclick="create_courier_modal.showModal()">+ Tambah Kurir</button>
        </div>

        @php
            $windowSeconds = 300;
            $threshold = now()->timestamp - $windowSeconds;
            $onlineIds = \Illuminate\Support\Facades\DB::table('sessions')
                ->where('last_activity', '>=', $threshold)
                ->pluck('user_id')
                ->filter()
                ->unique()
                ->values()
                ->all();
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-primary/10 p-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-truck">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M7 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M17 17m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                <path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v12m-4 0h6m4 0h2v-6h-8m0 -5h5l3 5" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Total Kurir</p>
                            <h2 class="text-3xl font-bold">{{ $users->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-success/10 p-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-circle-dot">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" />
                                <path d="M12 12m-1 0a1 1 0 1 0 2 0a1 1 0 1 0 -2 0" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Online</p>
                            <h2 class="text-3xl font-bold">{{ $users->whereIn('id', $onlineIds)->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-user-shield">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h2a4 4 0 0 1 4 4v2" />
                                <path d="M8 11a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M22 16c0 1.66 -4 3 -10 3s-10 -1.34 -10 -3" />
                                <path d="M5 8v3a1 1 0 0 0 1 1h2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Admin</p>
                            <h2 class="text-3xl font-bold">{{ \App\Models\User::where('level', 'admin')->count() }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">
                        <div class="rounded-lg bg-info/10 p-3 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                                <path d="M12 7v5l3 3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Terakhir Login (5 mnt)</p>
                            <h2 class="text-3xl font-bold">{{ count($onlineIds) }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @if (in_array($user->id, $onlineIds))
                                            <span class="badge badge-success">Online</span>
                                        @else
                                            <span class="badge badge-ghost">Offline</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <dialog id="create_courier_modal" class="modal">
        <div class="modal-box max-w-md rounded-2xl">

            <div class="flex items-center gap-3 mb-6">
                <div class="bg-orange-100 text-orange-600 p-2 rounded-xl">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-gray-900">Tambah Kurir</h3>
                    <p class="text-sm text-gray-500">
                        Buat akun kurir baru untuk pengiriman
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('dashboard.admin.users.store') }}" class="space-y-4">
                @csrf

                <div class="flex items-center justify-between p-3 rounded-lg gap-4">
                    <span class="font-medium">Nama</span>
                    <input type="text" name="name" class="input input-bordered input-sm w-2/3" required
                        maxlength="30">
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg gap-4">
                    <span class="font-medium">Email</span>
                    <input type="email" name="email" class="input input-bordered input-sm w-2/3" required>
                </div>

                <div class="flex items-center justify-between p-3 rounded-lg gap-4">
                    <span class="font-medium">Password</span>
                    <input type="password" name="password" class="input input-bordered input-sm w-2/3" required
                        minlength="6">
                </div>

                <input type="hidden" name="level" value="kurir">

                <div class="modal-action pt-4">
                    <button type="button" class="btn btn-ghost" onclick="create_courier_modal.close()">
                        Batal
                    </button>
                    <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white">
                        Simpan Kurir
                    </button>
                </div>
            </form>

        </div>
    </dialog>
@endsection
