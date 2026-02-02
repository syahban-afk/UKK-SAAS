@extends('layouts.dashboard', ['title' => 'Dashboard Kurir'])

@section('menu')
    @include('menus.kurir')
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

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <div class="card bg-base-100 border border-base-300"><div class="card-body">
      <p class="text-sm text-base-content/70">Pesanan Ditugaskan</p>
      <h2 class="text-3xl font-bold">{{ $totalAssigned }}</h2>
    </div></div>
    <div class="card bg-base-100 border border-base-300"><div class="card-body">
      <p class="text-sm text-base-content/70">Sedang Dikirim</p>
      <h2 class="text-3xl font-bold">{{ $onTransit }}</h2>
    </div></div>
    <div class="card bg-base-100 border border-base-300"><div class="card-body">
      <p class="text-sm text-base-content/70">Tiba Ditujuan</p>
      <h2 class="text-3xl font-bold">{{ $delivered }}</h2>
    </div></div>
    <div class="card bg-base-100 border border-base-300"><div class="card-body">
      <p class="text-sm text-base-content/70">Nama Kurir</p>
      <h2 class="text-3xl font-bold">{{ $user->name }}</h2>
    </div></div>
  </div>

  <div class="card bg-base-100 border border-base-300">
    <div class="card-body">
      @if ($errors->any())
        <div class="alert alert-error mb-4">
          <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if (session('status'))
        <div class="alert alert-success mb-4 text-sm">
          {{ session('status') }}
        </div>
      @endif
      <div class="flex items-center justify-between">
        <h2 class="card-title">Pesanan yang Ditugaskan</h2>
        <form method="GET" class="flex gap-2">
          @php $stNow = request('status'); @endphp
          <select name="status" class="select select-bordered select-sm">
            <option value="">Semua</option>
            <option value="Sedang Dikirim" {{ $stNow === 'Sedang Dikirim' ? 'selected' : '' }}>Sedang Dikirim</option>
            <option value="Tiba Ditujuan" {{ $stNow === 'Tiba Ditujuan' ? 'selected' : '' }}>Tiba Ditujuan</option>
          </select>
          <button class="btn btn-sm btn-primary" type="submit">Filter</button>
        </form>
      </div>
      @php
        if ($stNow) {
          $assignments = \App\Models\pengirimans_model::with(['pemesanan.pelanggan','pemesanan.detailPemesanans.paket'])
            ->where('id_user', $user->id)->where('status_kirim',$stNow)->orderByDesc('tgl_kirim')->paginate(12);
        }
      @endphp
      <div class="overflow-x-auto mt-3">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>No Resi</th>
              <th>Customer</th>
              <th>Alamat</th>
              <th>Paket</th>
              <th>Status Kirim</th>
              <th class="text-right">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($assignments as $as)
              @php
                $pem = $as->pemesanan;
                $cust = $pem->pelanggan;
                $firstPkg = $pem->detailPemesanans->first()?->paket?->nama_paket ?? '-';
                $addr = trim(($cust->alamat1 ?? '') . ', ' . ($cust->alamat2 ?? '') . ', ' . ($cust->alamat3 ?? ''));
              @endphp
              <tr>
                <td class="font-medium">{{ $pem->no_resi }}</td>
                <td>{{ $cust->nama_pelanggan ?? '-' }}</td>
                <td class="max-w-72">{{ $addr }}</td>
                <td>{{ $firstPkg }}</td>
                <td>
                  <span class="badge {{ $as->status_kirim === 'Tiba Ditujuan' ? 'badge-success' : 'badge-info' }} badge-sm">
                    {{ $as->status_kirim }}
                  </span>
                </td>
                <td class="text-right">
                  <div class="flex justify-end gap-2">
                    <button type="button" class="btn btn-outline btn-xs" onclick="detail_{{ $as->id }}.showModal()">Detail</button>
                    @if ($as->status_kirim !== 'Sedang Dikirim')
                      <form method="POST" action="{{ route('dashboard.kurir.pick') }}">
                        @csrf
                        <input type="hidden" name="id_pesan" value="{{ $pem->id }}">
                        <button type="submit" class="btn btn-outline btn-xs">Ambil</button>
                      </form>
                    @endif
                    @if ($as->status_kirim !== 'Tiba Ditujuan')
                      <form method="POST" action="{{ route('dashboard.kurir.finish') }}">
                        @csrf
                        <input type="hidden" name="id_pesan" value="{{ $pem->id }}">
                        <button type="submit" class="btn btn-success btn-xs">Tiba</button>
                      </form>
                    @endif
                  </div>
                </td>
              </tr>
              <dialog id="detail_{{ $as->id }}" class="modal">
                <div class="modal-box max-w-xl">
                  <div class="flex items-center justify-between mb-4">
                    <div class="text-lg font-bold">Detail Pengiriman</div>
                    <button type="button" class="btn btn-sm btn-circle btn-ghost" onclick="detail_{{ $as->id }}.close()">✕</button>
                  </div>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                      <div class="text-xs text-base-content/70">No Resi</div>
                      <div class="font-medium">{{ $pem->no_resi }}</div>
                    </div>
                    <div>
                      <div class="text-xs text-base-content/70">Status Kirim</div>
                      <div>
                        <span class="badge {{ $as->status_kirim === 'Tiba Ditujuan' ? 'badge-success' : 'badge-info' }}">{{ $as->status_kirim }}</span>
                      </div>
                    </div>
                    <div>
                      <div class="text-xs text-base-content/70">Nama Pelanggan</div>
                      <div class="font-medium">{{ $cust->nama_pelanggan ?? '-' }}</div>
                    </div>
                    <div>
                      <div class="text-xs text-base-content/70">Telepon</div>
                      <div class="font-medium">{{ $cust->telepon ?? '-' }}</div>
                    </div>
                    <div class="sm:col-span-2">
                      <div class="text-xs text-base-content/70">Alamat</div>
                      <div class="font-medium">{{ $cust->alamat1 ?? '-' }}</div>
                      <div class="font-medium">{{ $cust->alamat2 ?? '-' }}</div>
                      <div class="font-medium">{{ $cust->alamat3 ?? '-' }}</div>
                    </div>
                    <div>
                      <div class="text-xs text-base-content/70">Paket</div>
                      <div class="font-medium">{{ $firstPkg }}</div>
                    </div>
                    <div>
                      <div class="text-xs text-base-content/70">Tanggal Kirim</div>
                      <div class="font-medium">{{ $as->tgl_kirim ? \Carbon\Carbon::parse($as->tgl_kirim)->format('Y-m-d H:i') : '-' }}</div>
                    </div>
                    <div>
                      <div class="text-xs text-base-content/70">Tanggal Tiba</div>
                      <div class="font-medium">{{ $as->tgl_tiba ? \Carbon\Carbon::parse($as->tgl_tiba)->format('Y-m-d H:i') : '-' }}</div>
                    </div>
                  </div>
                  <div class="modal-action">
                    <button type="button" class="btn" onclick="detail_{{ $as->id }}.close()">Tutup</button>
                  </div>
                </div>
              </dialog>
            @empty
              <tr><td colspan="6" class="text-center text-base-content/70">Tidak ada data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
      <div class="mt-4">{{ $assignments->withQueryString()->links() }}</div>
    </div>
  </div>
</div>
@endsection
