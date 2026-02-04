@extends('layouts.dashboard', ['title' => 'Manajemen User'])

@section('menu')
    @include('menus.owner')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-primary/10 p-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M10 13a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M8 21v-1a2 2 0 0 1 2 -2h4a2 2 0 0 1 2 2v1" />
                                <path d="M5 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M3 13v-1a2 2 0 0 1 2 -2h2" />
                                <path d="M15 5a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                <path d="M17 10h2a2 2 0 0 1 2 2v1" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Users</p>
                            <h2 class="text-3xl font-bold">{{ $totalUsers }}</h2>
                            <p class="text-xs text-base-content/70">Total Users</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-success/10 p-3 text-success">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                                <path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Admin</p>
                            <h2 class="text-3xl font-bold">{{ $totalAdmin }}</h2>
                            <p class="text-xs text-base-content/70">Total Admin</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-motorbike">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M2 16a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M16 16a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" />
                                <path d="M7.5 14h5l4 -4h-10.5m1.5 4l4 -4" />
                                <path d="M13 6h2l1.5 3l2 4" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Kurir</p>
                            <h2 class="text-3xl font-bold">{{ $totalKurir }}</h2>
                            <p class="text-xs text-base-content/70">Total Kurir</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="flex items-center gap-3">
                        <div class="rounded-lg bg-info/10 p-3 text-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-mobiledata">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M16 12v-8" />
                                <path d="M8 20v-8" />
                                <path d="M13 7l3 -3l3 3" />
                                <path d="M5 17l3 3l3 -3" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-base-content/70">Online</p>
                            <h2 class="text-3xl font-bold">{{ $totalOnline }}</h2>
                            <p class="text-xs text-base-content/70">Total Online</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Manajemen User</h1>

            <button class="btn bg-orange-600 hover:bg-orange-700 text-white btn-sm text-lg font-medium text-base-content text-white"
                onclick="create_user_modal.showModal()">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                    <path d="M16 19h6" />
                    <path d="M19 16v6" />
                    <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                </svg>
                Tambah User
            </button>
        </div>

        <!-- LIST USER -->
        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Level</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span
                                            class="badge badge-soft badge-{{ $user->level == 'admin' ? 'success' : ($user->level == 'kurir' ? 'info' : 'primary') }}">
                                            {{ ucfirst($user->level) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if (in_array($user->id, $onlineIds))
                                            <span class="badge badge-soft badge-success">Online</span>
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

    <!-- MODAL CREATE USER -->
    <dialog id="create_user_modal" class="modal">
        <div class="modal-box max-w-lg">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div class="text-lg font-bold flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                        <path d="M6 21v-2a4 4 0 0 1 4 -4h4" />
                    </svg>
                    Tambah User
                </div>
                <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="create_user_modal.close()">
                    ✕
                </button>
            </div>

            <!-- Alert Error -->
            @if ($errors->any())
                <div class="alert alert-error mb-4">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Alert Success -->
            @if (session('status'))
                <div class="alert alert-success mb-4 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('dashboard.owner.users.store') }}" class="space-y-4">
                @csrf

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Nama Lengkap</span>
                    </label>
                    <input type="text" name="name" placeholder="Contoh: Budi Santoso"
                        class="input input-bordered w-full" required maxlength="30">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Email</span>
                    </label>
                    <input type="email" name="email" placeholder="email@example.com"
                        class="input input-bordered w-full" required>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Password</span>
                    </label>
                    <input type="password" name="password" placeholder="Minimal 6 karakter"
                        class="input input-bordered w-full" required minlength="6">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-medium">Role / Level</span>
                    </label>
                    <select name="level" class="select select-bordered w-full" required>
                        <option disabled selected>Pilih role</option>
                        @foreach ($levels as $level)
                            <option value="{{ $level }}">{{ ucfirst($level) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Action -->
                <div class="modal-action pt-4">
                    <button type="button" class="btn btn-outline" onclick="create_user_modal.close()">
                        Batal
                    </button>
                    <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white text-white">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </dialog>
@endsection
