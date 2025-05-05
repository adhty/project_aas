@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Barang</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('barang.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="nama">Nama Barang</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama barang" required>
                </div>

                <div class="form-group mb-3">
                    <label for="jumlah_barang">Jumlah Barang</label>
                    <input type="number" name="jumlah_barang" id="jumlah_barang" class="form-control" min="0" required>x
                    <label for="kategori_id">Kategori</label>
                    <select name="id_kategori" id="id_kategori" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoris as $kategori)
                        <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                        @endforeach
                    </select>
                </div>  -+

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('barang.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
