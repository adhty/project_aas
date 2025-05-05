@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Kategori</h1>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('kategori.store') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="nama">Nama Kategori</label>
                    <input type="text" name="nama" id="nama" class="form-control" placeholder="Masukkan nama kategori" required>
                </div>

                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="{{ route('kategori.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
