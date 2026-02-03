@extends('layouts.dashboard', ['title' => 'Dashboard Kurir'])

@section('menu')
    @include('menus.kurir')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
<div class="space-y-6">
    @php
        $user = \Illuminate\Support\Facades\Auth::user();
        $assignments = \App\Models\pengirimans_model::with(['pemesanan.pelanggan','pemesanan.detailPemesanans.paket'])
            ->where('id_user', $user->id)
            ->orderByDesc('tgl_kirim')
            ->paginate(12);
        $totalAssigned = \App\Models\pengirimans_model::where('id_user',$user->id)->count();
        $onTransit = \App\Models\pengirimans_model::where('id_user',$user->id)->where('status_kirim','Sedang Dikirim')->count();
        $delivered = \App\Models\pengirimans_model::where('id_user',$user->id)->where('status_kirim','Tiba Ditujuan')->count();
    @endphp

    <div class="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Halo, {{ $user->name }}!</h1>
            <p class="text-gray-500">Siap untuk mengantar kebahagiaan hari ini?</p>
        </div>
        <div class="flex gap-4">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-gray-900">{{ $user->level }}</p>
                <p class="text-xs text-gray-500">{{ $user->email }}</p>
            </div>
            <button onclick="profile_modal.showModal()" class="btn btn-ghost btn-circle avatar">
                <div class="w-12 rounded-full ring ring-orange-600 ring-offset-base-100 ring-offset-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" />
                </div>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="bg-orange-100 p-3 rounded-xl text-orange-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4a2 2 0 012-2m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Total Tugas</p>
                <h2 class="text-2xl font-bold text-gray-900">{{ $totalAssigned }}</h2>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Sedang Dikirim</p>
                <h2 class="text-2xl font-bold text-gray-900">{{ $onTransit }}</h2>
            </div>
        </div>
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-xl text-green-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="text-sm text-gray-500">Selesai</p>
                <h2 class="text-2xl font-bold text-gray-900">{{ $delivered }}</h2>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
            <h2 class="text-xl font-bold text-gray-900">Daftar Pengiriman</h2>
            <form method="GET" class="flex gap-2 w-full sm:w-auto">
                @php $stNow = request('status'); @endphp
                <select name="status" class="select select-bordered select-sm flex-1 sm:flex-none">
                    <option value="">Semua Status</option>
                    <option value="Sedang Dikirim" {{ $stNow === 'Sedang Dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
                    <option value="Tiba Ditujuan" {{ $stNow === 'Tiba Ditujuan' ? 'selected' : '' }}>Tiba Ditujuan</option>
                </select>
                <button class="btn btn-sm btn-primary" type="submit">Filter</button>
            </form>
        </div>

        @if (session('status'))
            <div class="alert alert-success mb-6 shadow-sm border-none bg-green-50 text-green-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="table w-full">
                <thead>
                    <tr class="text-gray-400 text-xs uppercase">
                        <th>No Resi</th>
                        <th>Customer</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse ($assignments as $as)
                        @php
                            $pem = $as->pemesanan;
                            $cust = $pem->pelanggan;
                            $addr = trim(($cust->alamat1 ?? '') . ' ' . ($cust->alamat2 ?? '') . ' ' . ($cust->alamat3 ?? ''));
                        @endphp
                        <tr class="hover:bg-gray-50 transition">
                            <td class="font-bold text-orange-600">{{ $pem->no_resi }}</td>
                            <td>
                                <div class="font-bold">{{ $cust->nama_pelanggan ?? '-' }}</div>
                                <div class="text-xs text-gray-500">{{ $cust->telepon ?? '-' }}</div>
                            </td>
                            <td class="max-w-xs truncate" title="{{ $addr }}">{{ $addr }}</td>
                            <td>
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $as->status_kirim === 'Tiba Ditujuan' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700' }}">
                                    {{ $as->status_kirim }}
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex justify-end gap-2">
                                    <button type="button" class="btn btn-ghost btn-xs text-blue-600" onclick="detail_{{ $as->id }}.showModal()">Detail</button>
                                    @if ($as->status_kirim !== 'Sedang Dikirim' && $as->status_kirim !== 'Tiba Ditujuan')
                                        <form method="POST" action="{{ route('dashboard.kurir.pick') }}">
                                            @csrf
                                            <input type="hidden" name="id_pesan" value="{{ $pem->id }}">
                                            <button type="submit" class="btn btn-primary btn-xs">Ambil</button>
                                        </form>
                                    @endif
                                    @if ($as->status_kirim === 'Sedang Dikirim')
                                        <form method="POST" action="{{ route('dashboard.kurir.finish') }}">
                                            @csrf
                                            <input type="hidden" name="id_pesan" value="{{ $pem->id }}">
                                            <button type="submit" class="btn btn-success btn-xs text-white">Selesai</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        <dialog id="detail_{{ $as->id }}" class="modal">
                            <div class="modal-box rounded-2xl max-w-lg">
                                <h3 class="font-bold text-lg mb-6">Detail Pengiriman</h3>
                                <div class="space-y-4">
                                    <div class="flex justify-between border-b border-gray-50 pb-2">
                                        <span class="text-gray-500">No Resi</span>
                                        <span class="font-bold text-orange-600">{{ $pem->no_resi }}</span>
                                    </div>
                                    <div class="flex justify-between border-b border-gray-50 pb-2">
                                        <span class="text-gray-500">Status</span>
                                        <span class="badge badge-info badge-sm">{{ $as->status_kirim }}</span>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-gray-500 text-xs uppercase font-bold">Penerima</span>
                                        <p class="font-bold text-gray-900">{{ $cust->nama_pelanggan ?? '-' }}</p>
                                        <p class="text-sm text-gray-600">{{ $cust->telepon ?? '-' }}</p>
                                    </div>
                                    <div class="space-y-1">
                                        <span class="text-gray-500 text-xs uppercase font-bold">Alamat Pengiriman</span>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $addr }}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4 pt-4 text-xs">
                                        <div>
                                            <p class="text-gray-400">Tgl Kirim</p>
                                            <p class="font-bold">{{ $as->tgl_kirim ? \Carbon\Carbon::parse($as->tgl_kirim)->format('d M Y H:i') : '-' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-gray-400">Tgl Tiba</p>
                                            <p class="font-bold">{{ $as->tgl_tiba ? \Carbon\Carbon::parse($as->tgl_tiba)->format('d M Y H:i') : '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-action">
                                    <button type="button" class="btn btn-ghost" onclick="detail_{{ $as->id }}.close()">Tutup</button>
                                </div>
                            </div>
                        </dialog>
                    @empty
                        <tr><td colspan="5" class="text-center py-12 text-gray-500">Tidak ada pengiriman ditugaskan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $assignments->withQueryString()->links() }}</div>
    </div>
</div>

<dialog id="profile_modal" class="modal">
    <div class="modal-box rounded-2xl max-w-md">
        <h3 class="font-bold text-lg mb-6">Profile Settings</h3>
        <div class="flex justify-center mb-6">
            <div class="avatar">
                <div class="w-24 rounded-full ring ring-orange-600 ring-offset-base-100 ring-offset-2">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&color=7F9CF5&background=EBF4FF" />
                </div>
            </div>
        </div>
        <div class="space-y-4">
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Nama Lengkap</span></label>
                <input type="text" value="{{ $user->name }}" class="input input-bordered" readonly>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Email</span></label>
                <input type="email" value="{{ $user->email }}" class="input input-bordered" readonly>
            </div>
            <div class="form-control">
                <label class="label"><span class="label-text font-semibold">Role</span></label>
                <input type="text" value="{{ $user->level }}" class="input input-bordered uppercase" readonly>
            </div>
        </div>
        <div class="modal-action">
            <button type="button" class="btn btn-primary" onclick="profile_modal.close()">Tutup</button>
        </div>
    </div>
</dialog>
@endsection
