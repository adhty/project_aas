@extends('layouts.app')

@section('title', 'Laporan Peminjaman')

@section('content')
<div class="container py-4">
    <div class="peminjaman-header">
        <h1><i class="fas fa-clipboard-list"></i> Laporan Peminjaman</h1>
        <div class="header-actions">
            <button class="btn-action" onclick="printReport()">
                <i class="fas fa-print"></i> Cetak Laporan
            </button>
            <a href="{{ route('laporan.peminjaman', array_merge(request()->query(), ['export' => 'excel'])) }}" class="btn-action">
                <i class="fas fa-file-excel"></i> Export Excel
            </a>
        </div>
    </div>

    <div class="filter-card">
        <form action="{{ route('laporan.peminjaman') }}" method="GET" class="filter-form">
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
                    <label for="status">Status:</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="disetujui" {{ request('status') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                        <option value="dikembalikan" {{ request('status') == 'dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                    </select>
                </div>
                <div class="filter-group">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-search"></i> Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="peminjaman-card">
        <div class="table-responsive">
            <table class="peminjaman-table">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Nama Peminjam</th>
                        <th>Nama Barang</th>
                        <th>Alasan Pinjam</th>
                        <th width="80">Jumlah</th>
                        <th width="120">Tanggal Pinjam</th>
                        <th width="120">Tanggal Kembali</th>
                        <th width="100">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($peminjamans as $index => $pinjam)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="user-info">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ $pinjam->user->name ?? '-' }}</span>
                                </div>
                            </td>
                            <td>{{ $pinjam->barang->nama ?? '-' }}</td>
                            
                            <td>{{ $pinjam->alasan_pinjam ?? '-' }}</td>
                            <td class="text-center">
                                <span class="badge-jumlah">{{ $pinjam->jumlah }}</span>
                            </td>
                            <td>{{ $pinjam->tanggal_pinjam }}</td>
                            <td>{{ $pinjam->tanggal_kembali }}</td>
                            <td>
                                @if($pinjam->status == 'menunggu')
                                    <span class="badge-status pending">
                                        <i class="fas fa-clock"></i> Menunggu
                                    </span>
                                @elseif($pinjam->status == 'disetujui')
                                    <span class="badge-status approved">
                                        <i class="fas fa-check"></i> Disetujui
                                    </span>
                                @elseif($pinjam->status == 'ditolak')
                                    <span class="badge-status rejected">
                                        <i class="fas fa-times"></i> Ditolak
                                    </span>
                                @elseif($pinjam->status == 'dikembalikan')
                                    <span class="badge-status returned">
                                        <i class="fas fa-undo-alt"></i> Dikembalikan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty-data">
                                <div class="empty-state">
                                    <i class="fas fa-search"></i>
                                    <p>Tidak ada data peminjaman yang ditemukan.</p>
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
    .peminjaman-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .peminjaman-header h1 {
        font-size: 24px;
        color:rgb(0, 0, 0);
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .peminjaman-header h1 i {
        margin-right: 10px;
        color: #60a5fa;
        font-size: 22px;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }

    .btn-action i {
        margin-right: 8px;
    }

    .btn-action:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }

    /* Filter card */
    .filter-card {
        background-color: #1e293b;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
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
        color: #cbd5e1;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        padding: 10px;
        border: 1px solid #334155;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.2s;
        background-color: #0f172a;
        color: #f8fafc;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn-filter {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.3s ease;
        height: 40px;
        margin-top: 24px;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }

    .btn-filter i {
        margin-right: 8px;
    }

    .btn-filter:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4);
    }

    /* Card */
    .peminjaman-card {
        background-color: #0f172a;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
    }

    .peminjaman-table {
        width: 100%;
        border-collapse: collapse;
    }

    .peminjaman-table th {
        background-color: #1e293b;
        color: #f8fafc;
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
        border-bottom: 2px solid #3b82f6;
    }
    
    .peminjaman-table th:first-child {
        border-top-left-radius: 8px;
    }
    
    .peminjaman-table th:last-child {
        border-top-right-radius: 8px;
    }

    .peminjaman-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #334155;
        color: #f8fafc;
        vertical-align: middle;
    }

    .peminjaman-table tr:hover td {
        background-color: #1e293b;
    }
    
    .peminjaman-table tr:last-child td {
        border-bottom: none;
    }
    
    .peminjaman-table tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }
    
    .peminjaman-table tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
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
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
    }

    .badge-status {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .badge-status.pending {
        background-color: #854d0e;
        color: #fef9c3;
    }

    .badge-status.approved {
        background-color: #065f46;
        color: #d1fae5;
    }

    .badge-status.rejected {
        background-color: #991b1b;
        color: #fee2e2;
    }

    .badge-status.returned {
        background-color: #1e40af;
        color: #dbeafe;
    }

    /* Empty state */
    .empty-data {
        padding: 0 !important;
    }

    .empty-state {
        padding: 40px 20px;
        text-align: center;
        background: linear-gradient(135deg, #0f172a, #1e293b);
        border-radius: 10px;
    }

    .empty-state i {
        font-size: 40px;
        color: #60a5fa;
        margin-bottom: 16px;
    }

    .empty-state p {
        color: #cbd5e1;
        margin-bottom: 0;
        font-size: 16px;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .peminjaman-header {
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
        
        .peminjaman-header h1 {
            font-size: 18px;
            text-align: center;
            width: 100%;
            justify-content: center;
            margin-bottom: 20px;
            color: black;
        }
        
        .peminjaman-card {
            box-shadow: none;
            background-color: white;
        }
        
        .peminjaman-table th {
            background-color: #f1f5f9;
            color: black;
            border-bottom: 1px solid #cbd5e1;
        }
        
        .peminjaman-table td {
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
        
        .badge-status.returned {
            background-color: #eff6ff;
            color: #1e40af;
            border-color: #bfdbfe;
        }
        
        .user-info i {
            color: #6b7280;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    function printReport() {
        window.print();
    }
</script>
@endsection
