@extends('layouts.app')

@section('title', 'Laporan Pengembalian')

@section('content')
<div class="container py-4">
    <div class="pengembalian-header">
        <h1><i class="fas fa-clipboard-list"></i> Laporan Pengembalian</h1>
        <div class="header-actions">
            <button class="btn-action" onclick="printReport()">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
            <a href="{{ route('laporan.pengembalian', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn-action">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="filter-card">
        <form action="{{ route('laporan.pengembalian') }}" method="GET" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="tanggal_mulai">Dari Tanggal:</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" 
                        value="{{ request('tanggal_mulai') }}">
                </div>
                <div class="filter-group">
                    <label for="tanggal_akhir">Sampai Tanggal:</label>
                    <input type="date" id="tanggal_akhir" name="tanggal_akhir" class="form-control" 
                        value="{{ request('tanggal_akhir') }}">
                </div>
                <div class="filter-group">
                    <label for="kondisi">Kondisi Barang:</label>
                    <select id="kondisi" name="kondisi" class="form-control">
                        <option value="">Semua Kondisi</option>
                        <option value="baik" {{ request('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                        <option value="rusak" {{ request('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                        <option value="hilang" {{ request('kondisi') == 'hilang' ? 'selected' : '' }}>Hilang</option>
                    </select>
                </div>
                <div class="filter-item">
                    <label for="status_pengembalian">Status Pengembalian</label>
                    <select name="status_pengembalian" id="status_pengembalian" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="diterima" {{ request('status_pengembalian') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status_pengembalian') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="menunggu" {{ request('status_pengembalian') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    </select>
                </div>
                <button type="submit" class="btn-filter">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="pengembalian-card">
        <div class="table-responsive">
            <table class="pengembalian-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Peminjam</th>
                        <th>Nama Barang</th>
                        <th width="80">Jumlah</th>
                        <th width="120">Tanggal Pinjam</th>
                        <th width="120">Tanggal Kembali</th>
                        <th width="120">Kondisi Barang</th>
                        <th width="120">Biaya Denda</th>
                        <th width="100">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengembalians as $index => $data)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ $data->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td>{{ $data->barang->nama ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge-jumlah">{{ $data->jumlah }}</span>
                            </td>
                            <td>{{ $data->tanggal_peminjaman }}</td>
                            <td>{{ $data->tanggal_pengembalian }}</td>
                            <td>
                                @if($data->kondisi_barang == 'baik')
                                    <span class="badge-status approved">
                                        <i class="fas fa-check"></i> Baik
                                    </span>
                                @elseif($data->kondisi_barang == 'rusak')
                                    <span class="badge-status pending">
                                        <i class="fas fa-exclamation-triangle"></i> Rusak
                                    </span>
                                @elseif($data->kondisi_barang == 'hilang')
                                    <span class="badge-status rejected">
                                        <i class="fas fa-times"></i> Hilang
                                    </span>
                                @endif
                            </td>
                            <td>Rp {{ number_format($data->biaya_denda, 0, ',', '.') }}</td>
                            <td>
                                @if($data->status == 'diterima')
                                    <span class="badge-status approved">
                                        <i class="fas fa-check"></i> Diterima
                                    </span>
                                @elseif($data->status == 'ditolak')
                                    <span class="badge-status rejected">
                                        <i class="fas fa-times"></i> Ditolak
                                    </span>
                                @elseif($data->status == 'menunggu')
                                    <span class="badge-status pending">
                                        <i class="fas fa-clock"></i> Menunggu
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-data">
                                <div class="empty-state">
                                    <i class="fas fa-search"></i>
                                    <p>Tidak ada data pengembalian yang ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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
    .pengembalian-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .pengembalian-header h1 {
        font-size: 24px;
        color: #000000;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .pengembalian-header h1 i {
        margin-right: 10px;
        color: #3b82f6;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        background-color: #3b82f6;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }

    .btn-action i {
        margin-right: 8px;
    }

    .btn-action:hover {
        background-color: #2563eb;
        color: white;
    }

    /* Filter card */
    .filter-card {
        background-color: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        padding: 20px;
        margin-bottom: 20px;
    }

    .filter-form {
        display: flex;
        flex-wrap: wrap;
    }

    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        width: 100%;
    }

    .filter-group {
        flex: 1;
        min-width: 200px;
        margin-bottom: 10px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        color: #4b5563;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-filter {
        background-color: #3b82f6;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: background-color 0.2s;
        height: 40px;
        margin-top: 24px;
    }

    .btn-filter i {
        margin-right: 8px;
    }

    .btn-filter:hover {
        background-color: #2563eb;
    }

    /* Card */
    .pengembalian-card {
        background-color: #1e293b;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
    }

    .pengembalian-table {
        width: 100%;
        border-collapse: collapse;
    }

    .pengembalian-table th {
        background-color: #f8fafc;
        color: #1e293b;
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
    }

    .pengembalian-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #334155;
        color: #f8fafc;
        vertical-align: middle;
    }

    .pengembalian-table tr:hover {
        background-color: #334155;
    }

    .text-center {
        text-align: center;
    }

    /* User info */
    .user-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-info i {
        color: #94a3b8;
        font-size: 16px;
    }

    /* Badges */
    .badge-jumlah {
        background-color: #3b82f6;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
    }

    .badge-status {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-status.pending {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .badge-status.approved {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .badge-status.rejected {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    /* Empty state */
    .empty-data {
        text-align: center;
    }

    .empty-state {
        padding: 40px 20px;
        text-align: center;
    }

    .empty-state i {
        font-size: 48px;
        color: #64748b;
        margin-bottom: 16px;
    }

    .empty-state p {
        color: #94a3b8;
        margin: 0;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .pengembalian-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .header-actions {
            width: 100%;
        }
        
        .btn-action {
            flex: 1;
            justify-content: center;
        }
        
        .filter-group {
            width: 100%;
            flex: none;
        }
    }

    @media (max-width: 768px) {
        .filter-row {
            flex-direction: column;
            gap: 10px;
        }
        
        .btn-filter {
            width: 100%;
            margin-top: 10px;
            justify-content: center;
        }
    }

    /* Print styles */
    @media print {
        .header-actions, .filter-card, .sidebar {
            display: none !important;
        }
        
        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
        }
        
        .pengembalian-header h1 {
            font-size: 18px;
            text-align: center;
            width: 100%;
            justify-content: center;
            margin-bottom: 20px;
        }
        
        .pengembalian-card {
            box-shadow: none;
            background-color: white;
        }
        
        .pengembalian-table th {
            background-color: #f1f5f9;
            color: black;
            border-bottom: 1px solid #cbd5e1;
        }
        
        .pengembalian-table td {
            color: black;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .badge-jumlah {
            background-color: #dbeafe;
            color: #1e40af;
            border: 1px solid #bfdbfe;
        }
        
        .badge-status {
            border: 1px solid;
        }
        
        .badge-status.pending {
            background-color: #fff7ed;
            color: #c2410c;
            border-color: #fed7aa;
        }
        
        .badge-status.approved {
            background-color: #ecfdf5;
            color: #065f46;
            border-color: #a7f3d0;
        }
        
        .badge-status.rejected {
            background-color: #fef2f2;
            color: #b91c1c;
            border-color: #fecaca;
        }
        
        .user-info i {
            color: #6b7280;
        }
    }
</style>

<script>
    function printReport() {
        window.print();
    }
</script>
@endsection
