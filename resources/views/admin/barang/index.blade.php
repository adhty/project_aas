@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">📦 Daftar Barang</h4>
            <a href="{{ route('barang.create') }}" class="btn btn-light btn-sm">
                ➕ Tambah Barang
            </a>
        </div>
        <div class="card-body">
            @if($barang->isEmpty())
                <div class="alert alert-info text-center">
                    Belum ada barang yang tersedia.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-dark">
                            <tr class="text-center">
                                <th style="width: 50px;">No</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Barang</th>
                                <th>Kategori</th>
                                <th style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $index => $barang)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $barang->nama }}</td>
                                <td>{{ $barang->kategori->nama ?? '-' }}</td>
                                <td class="text-center">
                                    <a href="{{ route('barang.edit', $barang->id) }}" class="btn btn-sm btn-warning me-1">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('barang.destroy', $barang->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            🗑️ Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
