@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="barang-header">
        <h1>Daftar Barang</h1>
        <a href="{{ route('barang.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Tambah Barang
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'"></button>
        </div>
    @endif
    
    <div class="barang-card">
        @if($barang->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>Belum ada barang yang tersedia</p>
                <a href="{{ route('barang.create') }}" class="btn-add-empty">Tambah Barang Pertama</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="barang-table">
                    <thead>
                        <tr>
                            <th width="60">No</th>
                            <th width="100">Foto</th>
                            <th>Nama Barang</th>
                            <th width="100">Jumlah</th>
                            <th width="150">Kategori</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barang as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">
                                @if($item->foto)
                                    <img src="{{ asset('storage/' . $item->foto) }}" 
                                         alt="Foto {{ $item->nama }}" 
                                         class="barang-image">
                                @else
                                    <div class="no-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="barang-name">{{ $item->nama }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge-jumlah">{{ $item->jumlah_barang }}</span>
                            </td>
                            <td>
                                <span class="badge-kategori">
                                    <i class="fas fa-tag"></i> {{ $item->kategori->nama ?? '-' }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('barang.edit', $item->id) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus barang ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete">
                                        <i class="fas fa-trash"></i> Hapus
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Gaya dasar */
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header */
    .barang-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .barang-header h1 {
        font-size: 24px;
        color: #1e293b;
        margin: 0;
        font-weight: 600;
    }

    .btn-add {
        background-color: #3b82f6;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-add i {
        margin-right: 8px;
    }

    .btn-add:hover {
        background-color: #2563eb;
        color: white;
    }

    /* Alert */
    .alert-success {
        background-color: #dcfce7;
        border-left: 4px solid #10b981;
        color: #065f46;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .alert-success i {
        margin-right: 12px;
        font-size: 18px;
    }

    .btn-close {
        background: none;
        border: none;
        position: absolute;
        right: 16px;
        top: 16px;
        cursor: pointer;
        color: #065f46;
        font-size: 16px;
    }

    .btn-close:hover {
        color: #064e3b;
    }

    /* Card */
    .barang-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    /* Empty state */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-state i {
        font-size: 48px;
        color: #94a3b8;
        margin-bottom: 16px;
    }

    .empty-state p {
        color: #64748b;
        margin-bottom: 20px;
    }

    .btn-add-empty {
        background-color: #3b82f6;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s;
    }

    .btn-add-empty:hover {
        background-color: #2563eb;
        color: white;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
    }

    .barang-table {
        width: 100%;
        border-collapse: collapse;
    }

    .barang-table th {
        background-color: #1e293b;
        color: white;
        padding: 14px 16px;
        text-align: left;
        font-weight: 500;
    }

    .barang-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: middle;
    }

    .barang-table tr:hover {
        background-color: #f8fafc;
    }

    .text-center {
        text-align: center;
    }

    /* Barang image */
    .barang-image {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        border: 2px solid #f1f5f9;
    }

    .no-image {
        width: 60px;
        height: 60px;
        background-color: #f1f5f9;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8;
        font-size: 20px;
    }

    /* Barang name */
    .barang-name {
        font-weight: 500;
        color: #1e293b;
        max-width: 250px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Badges */
    .badge-jumlah {
        background-color: #3b82f6;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .badge-kategori {
        background-color: #f1f5f9;
        color: #475569;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
    }

    .badge-kategori i {
        margin-right: 6px;
        color: #64748b;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-edit {
        background-color: #f59e0b;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: background-color 0.2s;
    }

    .btn-edit i {
        margin-right: 6px;
    }

    .btn-edit:hover {
        background-color: #d97706;
        color: white;
    }

    .btn-delete {
        background-color: #ef4444;
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 13px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: background-color 0.2s;
    }

    .btn-delete i {
        margin-right: 6px;
    }

    .btn-delete:hover {
        background-color: #dc2626;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .barang-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 8px;
        }
        
        .btn-edit, .btn-delete {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection
