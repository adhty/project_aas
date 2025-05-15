@extends('layouts.app')

@section('content')
    <h2>Dashboard</h2>

    <div class="card-container">
        <div class="card" style="border-left: 5px solid #e74c3c;">
            <h3>Data Barang</h3>
            <span>0</span>
        </div>
        <div class="card" style="border-left: 5px solid #2c3e50;">
            <h3>Kategori barang</h3>
            <span>0</span>
        </div>
        <div class="card" style="border-left: 5px solid #be4c7f;">
            <h3>Peminjaman</h3>
            <span>0</span>
        </div>
        <div class="card" style="border-left: 5px solid #be4c7f;">
            <h3>Pengembalian</h3>
            <span>0</span>
        </div>
    </div>
@endsection
