x@extends('layouts.app')

@section('title', 'Edit Stok')

@section('content')
<div class="container mt-5">
    <div class="card">
        <div class="card-header">Edit Stok</div>
        <div class="card-body">
            <form action="{{ route('stok.update', $stok->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select name="barang_id" id="barang_id" class="form-select" required>
                        @foreach($barangs as $barang)
                            <option value="{{ $barang->id }}" {{ $stok->barang_id == $barang->id ? 'selected' : '' }}>
                                {{ $barang->nama_barang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="jumlah" class="form-label">Jumlah Stok</label>
                    <input type="number" class="form-control" name="jumlah" id="jumlah" value="{{ $stok->jumlah }}" required>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.stok.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection