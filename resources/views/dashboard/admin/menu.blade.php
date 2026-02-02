@extends('layouts.dashboard', ['title' => 'Manajemen Menu'])

@section('menu')
    @include('menus.admin')
@endsection

@section('menu-bottom')
    @include('menus.bottom')
@endsection

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Manajemen Menu</h1>
            <button class="btn btn-primary btn-sm" onclick="create_menu_modal.showModal()">+ Tambah Menu</button>
        </div>

        @php
            $totalMenu = \App\Models\pakets_model::count();
            $totalPrasmanan = \App\Models\pakets_model::where('jenis', 'Prasmanan')->count();
            $totalBox = \App\Models\pakets_model::where('jenis', 'Box')->count();
            $avgHarga = (int) \App\Models\pakets_model::avg('harga_paket');
            $pakets = \App\Models\pakets_model::orderBy('nama_paket')->paginate(12);
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
            <div class="card bg-base-100 border border-base-300"><div class="card-body">
                <p class="text-sm text-base-content/70">Total Menu</p>
                <h2 class="text-3xl font-bold">{{ $totalMenu }}</h2>
            </div></div>
            <div class="card bg-base-100 border border-base-300"><div class="card-body">
                <p class="text-sm text-base-content/70">Prasmanan</p>
                <h2 class="text-3xl font-bold">{{ $totalPrasmanan }}</h2>
            </div></div>
            <div class="card bg-base-100 border border-base-300"><div class="card-body">
                <p class="text-sm text-base-content/70">Box</p>
                <h2 class="text-3xl font-bold">{{ $totalBox }}</h2>
            </div></div>
            <div class="card bg-base-100 border border-base-300"><div class="card-body">
                <p class="text-sm text-base-content/70">Rata-rata Harga</p>
                <h2 class="text-3xl font-bold">Rp {{ number_format($avgHarga, 0, ',', '.') }}</h2>
            </div></div>
        </div>

        <div class="card bg-base-100 border border-base-300">
            <div class="card-body">
                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Paket</th>
                                <th>Jenis</th>
                                <th>Kategori</th>
                                <th>Pax</th>
                                <th>Harga</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pakets as $p)
                                <tr>
                                    <td class="font-medium">{{ $p->nama_paket }}</td>
                                    <td>{{ $p->jenis }}</td>
                                    <td>{{ $p->kategori }}</td>
                                    <td>{{ $p->jumlah_pax }}</td>
                                    <td>Rp {{ number_format($p->harga_paket, 0, ',', '.') }}</td>
                                    <td class="text-right">
                                        <div class="flex justify-end gap-2">
                                            <button class="btn btn-outline btn-xs" onclick="edit_{{ $p->id }}.showModal()">Edit</button>
                                            <form method="POST" action="{{ route('dashboard.admin.menu.destroy', $p->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-xs">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <dialog id="edit_{{ $p->id }}" class="modal">
                                    <div class="modal-box">
                                        <h3 class="font-bold text-lg mb-4">Edit Menu</h3>
                                        <form method="POST" action="{{ route('dashboard.admin.menu.update', $p->id) }}" class="space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <div class="form-control">
                                                <label class="label"><span class="label-text">Nama Paket</span></label>
                                                <input type="text" name="nama_paket" class="input input-bordered" value="{{ $p->nama_paket }}" required maxlength="50">
                                            </div>
                                            <div class="form-control">
                                                <label class="label"><span class="label-text">Jenis</span></label>
                                                <select name="jenis" class="select select-bordered" required>
                                                    <option value="Prasmanan" {{ $p->jenis === 'Prasmanan' ? 'selected' : '' }}>Prasmanan</option>
                                                    <option value="Box" {{ $p->jenis === 'Box' ? 'selected' : '' }}>Box</option>
                                                </select>
                                            </div>
                                            <div class="form-control">
                                                <label class="label"><span class="label-text">Kategori</span></label>
                                                <select name="kategori" class="select select-bordered" required>
                                                    @foreach (['Pernikahan','Selamatan','Ulang Tahun','Studi Tour','Rapat'] as $kat)
                                                        <option value="{{ $kat }}" {{ $p->kategori === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-control grid grid-cols-2 gap-2">
                                                <div>
                                                    <label class="label"><span class="label-text">Jumlah Pax</span></label>
                                                    <input type="number" name="jumlah_pax" class="input input-bordered" value="{{ $p->jumlah_pax }}" min="0" required>
                                                </div>
                                                <div>
                                                    <label class="label"><span class="label-text">Harga</span></label>
                                                    <input type="number" name="harga_paket" class="input input-bordered" value="{{ $p->harga_paket }}" min="0" required>
                                                </div>
                                            </div>
                                            <div class="form-control">
                                                <label class="label"><span class="label-text">Deskripsi</span></label>
                                                <textarea name="deskripsi" class="textarea textarea-bordered" required>{{ $p->deskripsi }}</textarea>
                                            </div>
                                            <div class="modal-action">
                                                <button type="button" class="btn" onclick="edit_{{ $p->id }}.close()">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </dialog>
                            @empty
                                <tr><td colspan="6" class="text-center text-base-content/70">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $pakets->links() }}</div>
            </div>
        </div>
    </div>

    <dialog id="create_menu_modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Tambah Menu</h3>
            <form method="POST" action="{{ route('dashboard.admin.menu.store') }}" class="space-y-3">
                @csrf
                <div class="form-control">
                    <label class="label"><span class="label-text">Nama Paket</span></label>
                    <input type="text" name="nama_paket" class="input input-bordered" required maxlength="50">
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Jenis</span></label>
                    <select name="jenis" class="select select-bordered" required>
                        <option value="Prasmanan">Prasmanan</option>
                        <option value="Box">Box</option>
                    </select>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Kategori</span></label>
                    <select name="kategori" class="select select-bordered" required>
                        @foreach (['Pernikahan','Selamatan','Ulang Tahun','Studi Tour','Rapat'] as $kat)
                            <option value="{{ $kat }}">{{ $kat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-control grid grid-cols-2 gap-2">
                    <div>
                        <label class="label"><span class="label-text">Jumlah Pax</span></label>
                        <input type="number" name="jumlah_pax" class="input input-bordered" min="0" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Harga</span></label>
                        <input type="number" name="harga_paket" class="input input-bordered" min="0" required>
                    </div>
                </div>
                <div class="form-control">
                    <label class="label"><span class="label-text">Deskripsi</span></label>
                    <textarea name="deskripsi" class="textarea textarea-bordered" required></textarea>
                </div>
                <div class="form-control grid grid-cols-3 gap-2">
                    <div>
                        <label class="label"><span class="label-text">Foto 1</span></label>
                        <input type="text" name="foto1" class="input input-bordered" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Foto 2</span></label>
                        <input type="text" name="foto2" class="input input-bordered" required>
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Foto 3</span></label>
                        <input type="text" name="foto3" class="input input-bordered" required>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="create_menu_modal.close()">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>
@endsection
