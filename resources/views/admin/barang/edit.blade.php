@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Barang</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label for="nama">Nama Barang</label>
                    <input type="text" name="nama" id="nama" class="form-control"
                        value="{{ old('nama', $barang->nama) }}" required>
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah_barang">Jumlah Barang</label>
                    <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control"
                        value="{{ old('jumlah_barang', $barang->jumlah_barang) }}" min="0" required>
                </div>


                <div class="form-group mb-3">
                    <label for="kategori_id">Kategori</label>
                    <select name="id_kategori" id="kategori_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}" {{ (old('id_kategori', $barang->id_kategori) == $kategori->id) ? 'selected' : '' }}>
                            {{ $kategori->nama }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tampilkan foto lama jika ada --}}
                @if($barang->foto)
                <div class="form-group mb-3">
                    <label>Foto Saat Ini</label><br>
                    <img src="{{ asset('storage/' . $barang->foto) }}" alt="Foto Barang" width="150" style="border-radius:8px;">
                </div>
                @endif

                <div class="form-group mb-3">
                    <label for="foto">Ganti Foto Barang (opsional)</label>
                    <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</div>
@endsection
