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
            <button class="btn bg-orange-600 hover:bg-orange-700 text-white btn-sm" onclick="create_courier_modal.showModal()">+ Tambah Kurir</button>
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
                    <p class="text-sm text-base-content/70">Total Kurir</p>
                    <h2 class="text-3xl font-bold">{{ $users->count() }}</h2>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <p class="text-sm text-base-content/70">Online</p>
                    <h2 class="text-3xl font-bold">{{ $users->whereIn('id', $onlineIds)->count() }}</h2>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <p class="text-sm text-base-content/70">Admin</p>
                    <h2 class="text-3xl font-bold">{{ \App\Models\User::where('level','admin')->count() }}</h2>
                </div>
            </div>
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <p class="text-sm text-base-content/70">Terakhir Login (5 mnt)</p>
                    <h2 class="text-3xl font-bold">{{ count($onlineIds) }}</h2>
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
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Kurir</h3>
            <form method="POST" action="{{ route('dashboard.admin.users.store') }}" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Nama</span></label>
                    <input type="text" name="name" class="input input-bordered" required maxlength="30">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Email</span></label>
                    <input type="email" name="email" class="input input-bordered" required>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Password</span></label>
                    <input type="password" name="password" class="input input-bordered" required minlength="6">
                </div>
                <input type="hidden" name="level" value="kurir">
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="create_courier_modal.close()">Batal</button>
                    <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>
@endsection
