@extends('layouts.app')

@section('content')
<div class="container my-4">
    <div class="card shadow-sm rounded-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top-4">
            <h4 class="mb-0">📦 Daftar Barang</h4>
            <a href="{{ route('barang.create') }}" class="btn btn-light btn-sm shadow-sm">
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
                    <table class="table table-bordered table-hover align-middle text-center">
                        <thead class="table-dark">
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Foto</th>
                                <th style="max-width: 250px;">Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Kategori</th>
                                <th style="width: 180px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barang as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td> 

                                <td>
                                    @if($item->foto)
                                        <img src="{{ asset('storage/' . $item->foto) }}" 
                                             alt="Foto {{ $item->foto }}" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td style="max-width: 250px;">
                                    <div class="text-truncate" style="max-width: 250px;">
                                        {{ $item->nama }}
                                    </div>
                                </td>
                                <td>{{ $item->jumlah_barang }}</td>
                                <td>{{ $item->kategori->nama ?? '-' }}</td>
                                <td>
                                    <a href="{{ route('barang.edit', $item->id) }}" class="btn btn-sm btn-warning me-2">
                                        ✏️ Edit
                                    </a>
                                    <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
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
