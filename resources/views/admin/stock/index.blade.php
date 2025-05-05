@extends('layouts.app')

@section('title', 'Data Stock')

@section('content')
<div class="container mt-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Data Stock Barang</span>
            <a href="{{ route('stock.create') }}" class="btn btn-primary">+ Tambah Stock</a>
        </div>
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Barang</th>
                        <th>Jumlah</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $stock->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $stock->jumlah }}</td>
                            <td>
                                <a href="{{ route('stock.edit', $stock->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('stock.destroy', $stock->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus stock ini?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4">Tidak ada data stock.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
