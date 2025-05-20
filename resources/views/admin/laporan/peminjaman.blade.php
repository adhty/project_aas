@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Peminjaman</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Peminjaman</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjamans as $index => $pinjam)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $pinjam->user->name ?? '-' }}</td>
                    <td>{{ $pinjam->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $pinjam->jumlah }}</td>
                    <td>{{ $pinjam->tanggal_peminjaman }}</td>
                    <td>{{ ucfirst($pinjam->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
