@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Laporan Pengembalian</h1>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Peminjam</th>
                <th>Nama Barang</th>
                <th>Jumlah</th>
                <th>Tanggal Peminjaman</th>
                <th>Tanggal Pengembalian</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengembalians as $index => $data)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->user->name ?? '-' }}</td>
                    <td>{{ $data->barang->nama_barang ?? '-' }}</td>
                    <td>{{ $data->jumlah }}</td>
                    <td>{{ $data->tanggal_peminjaman }}</td>
                    <td>{{ $data->tanggal_pengembalian ?? '-' }}</td>
                    <td>{{ ucfirst($data->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
