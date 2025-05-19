@extends('layouts.app')

@section('title', 'Daftar Pengembalian')

@section('content')
<div class="container py-4">
    <h1 class="mb-4 text-light">📦 Daftar Pengembalian</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-dark table-striped table-hover table-bordered align-middle">
            <thead class="table-light text-dark">
                <tr>
                    <th>ID</th>
                    <th>Nama Akun</th>
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pengembalians as $pengembalian)
                    <tr>
                        <td>{{ $pengembalian->id }}</td>
                        <td>{{ $pengembalian->user->name ?? '-' }}</td>
                        <td>{{ $pengembalian->barang->nama_barang ?? '-' }}</td>
                        <td>{{ $pengembalian->jumlah }}</td>
                        <td>{{ $pengembalian->tanggal_kembali }}</td>
                        <td>
                            @if($pengembalian->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($pengembalian->status == 'diterima')
                                <span class="badge bg-success">Diterima</span>
                            @elseif($pengembalian->status == 'ditolak')
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($pengembalian->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($pengembalian->status === 'pending')
                                <div class="btn-group">
                                    <form action="{{ route('pengembalian.approve', $pengembalian->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success" onclick="return confirm('Terima pengembalian ini?')">Terima</button>
                                    </form>
                                    <form action="{{ route('pengembalian.reject', $pengembalian->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak pengembalian ini?')">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data pengembalian.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
