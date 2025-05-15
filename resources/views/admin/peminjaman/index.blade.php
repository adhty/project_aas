@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
<div class="container">
    <h1 class="mb-5">Daftar Peminjaman</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- @if($peminjamans->isEmpty())
        <p>Tidak ada data peminjaman.</p>
    @else --}}
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Akun</th>
                    {{-- <th>Nama Peminjam</th> --}}
                    <th>Barang</th>
                    <th>Jumlah</th>
                    <th>Alasan</th>
                    <th>Tanggal Pinjam</th>
                    <th>Tanggal Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($peminjamans as $peminjaman)
                <tr>
                    <td>{{ $peminjaman->id }}</td>
                    <td>{{ $peminjaman->user->name ?? '-' }}</td>
                    {{-- <td>{{ $peminjaman->nama_peminjam}} </td> --}}
                    <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $peminjaman->jumlah }}</td>
                    <td>{{ $peminjaman->alasan_pinjam }}</td>
                    <td>{{ $peminjaman->tgl_pinjam }}</td>
                    <td>{{ $peminjaman->tgl_kembali }}</td>
                    <td>{{ ucfirst($peminjaman->status) }}</td>
                    <td>
                        @if($peminjaman->status === 'pending')
                            <form action="{{ route('peminjaman.approve', $peminjaman->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm" onclick="return confirm('Setujui peminjaman ini?')">Approve</button>
                            </form>
                            <form action="{{ route('peminjaman.reject', $peminjaman->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Tolak peminjaman ini?')">Reject</button>
                            </form>
                            {{-- <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Yakin hapus?')" class="btn btn-danger btn-sm">Hapus</button>
                            </form> --}}

                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    {{-- @endif --}}
</div>
@endsection