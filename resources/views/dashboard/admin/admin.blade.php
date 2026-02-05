@extends('layouts.dashboard', ['title' => 'Dashboard Admin'])

@section('menu')
    @include('menus.admin')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
<div class="space-y-6">
  @php
    $statusAgg = \Illuminate\Support\Facades\DB::table('pemesanans')
        ->select('status_pesan', \Illuminate\Support\Facades\DB::raw('COUNT(*) as total'))
        ->groupBy('status_pesan')
        ->pluck('total', 'status_pesan')
        ->toArray();
    $doneAgg = \Illuminate\Support\Facades\DB::table('pengirimans')
        ->where('status_kirim', 'Tiba Ditujuan')
        ->count();
    $totalAgg = array_sum($statusAgg);
    $omzetTotal = \Illuminate\Support\Facades\DB::table('pemesanans')->sum('total_bayar');
    $recentOrders = \App\Models\pemesanans_model::with(['pelanggan','detailPemesanans.paket'])
        ->orderByDesc('tgl_pesan')->limit(8)->get();
  @endphp

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
    <div class="card bg-base-100 border border-base-300">
      <div class="card-body">
        <div class="grid grid-cols-[auto_1fr] items-center gap-4">
          <div class="rounded-lg bg-primary/10 p-3 text-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
              fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
              stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-shopping-cart">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M6 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
              <path d="M17 19m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
              <path d="M17 17h-11v-14h-2" />
              <path d="M6 5l14 1l-1 7h-13" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-base-content/70">Total Pesanan</p>
            <h2 class="text-3xl font-bold">{{ $totalAgg }}</h2>
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
              stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-clock">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
              <path d="M12 7v5l3 3" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-base-content/70">Menunggu Konfirmasi</p>
            <h2 class="text-3xl font-bold">{{ (int) ($statusAgg['Menunggu Konfirmasi'] ?? 0) }}</h2>
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
              stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-tools-kitchen-2">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12m0 12v6h-1v-3m-10 -14v17m-3 -17v3a3 3 0 1 0 6 0v-3" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-base-content/70">Sedang Diproses</p>
            <h2 class="text-3xl font-bold">{{ (int) ($statusAgg['Sedang Diproses'] ?? 0) }}</h2>
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
              stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-check">
              <path stroke="none" d="M0 0h24v24H0z" fill="none" />
              <path d="M5 12l5 5l10 -10" />
            </svg>
          </div>
          <div>
            <p class="text-sm text-base-content/70">Selesai</p>
            <h2 class="text-3xl font-bold">{{ (int) $doneAgg }}</h2>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="card bg-base-100 border border-base-300">
    <div class="card-body">
      <div class="flex items-center justify-between">
        <h2 class="card-title">Pesanan Terbaru</h2>
        <a href="{{ route('dashboard.admin.orders') }}" class="btn btn-sm btn-outline">Kelola Pesanan</a>
      </div>
      <div class="overflow-x-auto mt-3">
        <table class="table table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Pelanggan</th>
              <th>Paket</th>
              <th>Status</th>
              <th>Tgl Pesan</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($recentOrders as $order)
            @php
              $firstPackage = $order->detailPemesanans->first()?->paket?->nama_paket ?? '-';
              $badgeClass = match ($order->status_pesan) {
                'Menunggu Konfirmasi' => 'badge-warning',
                'Sedang Diproses' => 'badge-info',
                'Menunggu Kurir' => 'badge-secondary',
                'Selesai' => 'badge-success',
                default => 'badge-default',
              };
            @endphp
            <tr>
              <td>{{ $order->no_resi }}</td>
              <td>{{ $order->pelanggan->nama_pelanggan ?? '-' }}</td>
              <td>{{ $firstPackage }}</td>
              <td><span class="badge {{ $badgeClass }} badge-sm">{{ $order->status_pesan }}</span></td>
              <td>{{ \Carbon\Carbon::parse($order->tgl_pesan)->format('d M Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center text-base-content/70">Tidak ada data</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
