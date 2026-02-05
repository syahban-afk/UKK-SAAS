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
            <button class="btn bg-orange-600 hover:bg-orange-700 text-white btn-sm" onclick="create_menu_modal.showModal()">+
                Tambah Menu</button>
        </div>

        @php
            $totalMenu = \App\Models\pakets_model::count();
            $totalPrasmanan = \App\Models\pakets_model::where('jenis', 'Prasmanan')->count();
            $totalBox = \App\Models\pakets_model::where('jenis', 'Box')->count();
            $avgHarga = (int) \App\Models\pakets_model::avg('harga_paket');
            $pakets = \App\Models\pakets_model::orderBy('nama_paket')->paginate(12);
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
            <div class="card bg-base-100 border border-base-300">
                <div class="card-body">
                    <div class="grid grid-cols-[auto_1fr] items-center gap-4">

                        <div class="rounded-lg bg-warning/10 p-3 text-warning">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="icon icon-tabler icons-tabler-outline icon-tabler-tools-kitchen-2">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M19 3v12h-5c-.023 -3.681 .184 -7.406 5 -12m0 12v6h-1v-3m-10 -14v17m-3 -17v3a3 3 0 1 0 6 0v-3" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm text-base-content/70">Total Menu</p>
                            <h2 class="text-3xl font-bold">{{ $totalMenu }}</h2>
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
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-bowl">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path
                                    d="M4 8h16a1 1 0 0 1 1 1v.5c0 1.5 -2.517 5.573 -4 6.5v1a1 1 0 0 1 -1 1h-8a1 1 0 0 1 -1 -1v-1c-1.687 -1.054 -4 -5 -4 -6.5v-.5a1 1 0 0 1 1 -1" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm text-base-content/70">Total Prasmanan</p>
                            <h2 class="text-3xl font-bold">{{ $totalPrasmanan }}</h2>
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
                                stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-box">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" />
                                <path d="M12 12l8 -4.5" />
                                <path d="M12 12l0 9" />
                                <path d="M12 12l-8 -4.5" />
                            </svg>
                        </div>

                        <div>
                            <p class="text-sm text-base-content/70">Total Box</p>
                            <h2 class="text-3xl font-bold">{{ $totalBox }}</h2>
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
                                            <button class="btn btn-warning btn-xs"
                                                onclick="edit_{{ $p->id }}.showModal()">Edit</button>
                                            <form method="POST"
                                                action="{{ route('dashboard.admin.menu.destroy', $p->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-error btn-xs">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>

                                <dialog id="edit_{{ $p->id }}" class="modal">
                                    <div class="modal-box max-w-3xl">
                                        <h3 class="text-xl font-bold">Edit Menu</h3>
                                        <form method="POST" action="{{ route('dashboard.admin.menu.update', $p->id) }}" enctype="multipart/form-data" class="space-y-6">
                                            @csrf
                                            @method('PUT')
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                <div class="space-y-4">
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
                                                            @foreach (['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat'] as $kat)
                                                                <option value="{{ $kat }}" {{ $p->kategori === $kat ? 'selected' : '' }}>{{ $kat }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="grid grid-cols-2 gap-4">
                                                        <div class="form-control">
                                                            <label class="label"><span class="label-text">Jumlah Pax</span></label>
                                                            <input type="number" name="jumlah_pax" class="input input-bordered" value="{{ $p->jumlah_pax }}" min="0" required>
                                                        </div>
                                                        <div class="form-control">
                                                            <label class="label"><span class="label-text">Harga</span></label>
                                                            <input type="number" name="harga_paket" class="input input-bordered" value="{{ $p->harga_paket }}" min="0" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-control">
                                                        <label class="label"><span class="label-text">Deskripsi</span></label>
                                                        <textarea name="deskripsi" class="textarea textarea-bordered" required>{{ $p->deskripsi }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="space-y-3">
                                                    <div class="text-sm text-base-content/70">Foto Produk</div>
                                                    @foreach ([1, 2, 3] as $i)
                                                        <div class="form-control">
                                                            <img src="{{ $p->{'foto' . $i} }}" class="rounded-xl mb-2 border border-gray-200 object-cover h-32 w-full">
                                                            <input type="file" name="foto{{ $i }}" accept="image/*" class="file-input file-input-bordered file-input-sm" onchange="previewImage(event, 'edit_preview{{ $p->id }}_{{ $i }}')">
                                                            <img id="edit_preview{{ $p->id }}_{{ $i }}" class="mt-2 rounded-xl border border-gray-200 hidden">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="modal-action">
                                                <button type="button" class="btn" onclick="edit_{{ $p->id }}.close()">Batal</button>
                                                <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </dialog>

                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-base-content/70">Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">{{ $pakets->withQueryString()->links() }}</div>
            </div>
        </div>
    </div>

    <dialog id="create_menu_modal" class="modal">
        <div class="modal-box max-w-3xl">
            <h3 class="text-xl font-bold">Tambah Menu</h3>
            <form method="POST" action="{{ route('dashboard.admin.menu.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
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
                                @foreach (['Pernikahan', 'Selamatan', 'Ulang Tahun', 'Studi Tour', 'Rapat'] as $kat)
                                    <option value="{{ $kat }}">{{ $kat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label"><span class="label-text">Jumlah Pax</span></label>
                                <input type="number" name="jumlah_pax" class="input input-bordered" min="0" required>
                            </div>
                            <div class="form-control">
                                <label class="label"><span class="label-text">Harga</span></label>
                                <input type="number" name="harga_paket" class="input input-bordered" min="0" required>
                            </div>
                        </div>
                        <div class="form-control">
                            <label class="label"><span class="label-text">Deskripsi</span></label>
                            <textarea name="deskripsi" class="textarea textarea-bordered" required></textarea>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="text-sm text-base-content/70">Foto Produk</div>
                        @foreach ([1, 2, 3] as $i)
                            <div class="form-control">
                                <input type="file" name="foto{{ $i }}" accept="image/*" class="file-input file-input-bordered file-input-sm" onchange="previewImage(event, 'preview{{ $i }}')" required>
                                <img id="preview{{ $i }}" class="mt-2 rounded-xl border border-gray-200 hidden" />
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn" onclick="create_menu_modal.close()">Batal</button>
                    <button type="submit" class="btn bg-orange-600 hover:bg-orange-700 text-white">Simpan</button>
                </div>
            </form>
        </div>
    </dialog>

@endsection

<script>
    function previewImage(event, id) {
        const img = document.getElementById(id);
        img.src = URL.createObjectURL(event.target.files[0]);
        img.classList.remove('hidden');
    }
</script>
