@extends('layouts.app')

@section('title', 'Daftar Pengembalian')

@section('content')
<div class="container py-4">
    <div class="pengembalian-header">
        <h1>🔄 Daftar Pengembalian</h1>
    </div>

    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'"></button>
        </div>
    @elseif(session('error'))
        <div class="alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'"></button>
        </div>
    @endif

    <div class="pengembalian-card">
        @if($pengembalians->isEmpty())
            <div class="empty-state">
                <i class="fas fa-undo-alt"></i>
                <p>Belum ada data pengembalian</p>
                <p class="empty-state-info">Pengembalian akan muncul ketika peminjam mengembalikan barang</p>
            </div>
        @else
            <div class="table-responsive">
                <table class="pengembalian-table">
                    <thead>
                        <tr>
                            <th width="60">ID</th>
                            <th>Nama Akun</th>
                            <th>Barang</th>
                            <th>Alasan</th>
                            <th width="80">Jumlah</th>
                            <th width="120">Tanggal Kembali</th>
                            <th width="120">Kondisi Barang</th>
                            <th width="120">Biaya Denda</th>
                            <th width="100">Status</th>
                            <th width="180">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pengembalians as $pengembalian)
                            <tr>
                                <td class="text-center">{{ $pengembalian->id }}</td>
                                <td>
                                    <div class="user-info">
                                        <i class="fas fa-user-circle"></i>
                                        <span>{{ $pengembalian->peminjaman->user->name ?? '-' }}</span>
                                    </div>
                                </td>
                                <td>{{ $pengembalian->peminjaman->barang->nama ?? '-' }}</td>
                                 <td>{{ $pengembalian->peminjaman-> alasan_pinjam ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="badge-jumlah">{{ $pengembalian->peminjaman->jumlah ?? '-' }}</span>
                                </td>
                                <td>{{ $pengembalian->tanggal_pengembalian }}</td>
                                <td>
                                    @if($pengembalian->kondisi_barang == 'baik')
                                        <span class="badge-kondisi good">
                                            <i class="fas fa-check-circle"></i> Baik
                                        </span>
                                    @elseif($pengembalian->kondisi_barang == 'rusak')
                                        <span class="badge-kondisi damaged">
                                            <i class="fas fa-tools"></i> Rusak
                                        </span>
                                    @elseif($pengembalian->kondisi_barang == 'hilang')
                                        <span class="badge-kondisi lost">
                                            <i class="fas fa-search"></i> Hilang
                                        </span>
                                    @else
                                        <span class="badge-kondisi">{{ ucfirst($pengembalian->kondisi_barang) }}</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    @if($pengembalian->biaya_denda > 0)
                                        <span class="badge-denda">
                                            Rp {{ number_format($pengembalian->biaya_denda, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($pengembalian->status == 'pending' || $pengembalian->status == 'menunggu')
                                        <span class="badge-status pending">
                                            <i class="fas fa-clock"></i> Menunggu
                                        </span>
                                    @elseif($pengembalian->status == 'diterima')
                                        <span class="badge-status approved">
                                            <i class="fas fa-check"></i> Diterima
                                        </span>
                                    @elseif($pengembalian->status == 'ditolak')
                                        <span class="badge-status rejected">
                                            <i class="fas fa-times"></i> Ditolak
                                        </span>
                                    @else
                                        <span class="badge-status">
                                            <i class="fas fa-info-circle"></i> {{ ucfirst($pengembalian->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($pengembalian->status === 'pending' || $pengembalian->status === 'menunggu')
                                        <div class="action-buttons">
                                            <form action="{{ route('pengembalian.approve', $pengembalian->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn-approve" onclick="return confirm('Terima pengembalian ini?')">
                                                    <i class="fas fa-check"></i> Terima
                                                </button>
                                            </form>
                                            <form action="{{ route('pengembalian.reject', $pengembalian->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn-reject" onclick="return confirm('Tolak pengembalian ini?')">
                                                    <i class="fas fa-times"></i> Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada data pengembalian.</td>
                            </tr>
                        @endforelse
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

    /* Alert */
    .alert-success, .alert-danger {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        position: relative;
    }

    .alert-success {
        background-color: #dcfce7;
        border-left: 4px solid #10b981;
        color: #065f46;
    }

    .alert-danger {
        background-color: #fee2e2;
        border-left: 4px solid #ef4444;
        color: #b91c1c;
    }

    .alert-success i, .alert-danger i {
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
        color: inherit;
        font-size: 16px;
    }

    .btn-close:hover {
        opacity: 0.8;
    }

    /* Card */
    .pengembalian-card {
        background-color: #1e293b;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    /* Empty state */
    .empty-state {
        padding: 60px 20px;
        text-align: center;
        color: #f8fafc;
    }

    .empty-state i {
        font-size: 48px;
        color: #64748b;
        margin-bottom: 16px;
    }

    .empty-state p {
        color: #94a3b8;
        margin-bottom: 10px;
    }
    
    .empty-state-info {
        font-size: 14px;
        color: #64748b;
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

    .text-muted {
        color: #64748b;
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

    .badge-status, .badge-kondisi {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .badge-status.pending {
        background-color: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }

    .badge-status.approved {
        background-color: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .badge-status.rejected {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    .badge-kondisi.good {
        background-color: rgba(16, 185, 129, 0.2);
        color: #10b981;
    }

    .badge-kondisi.damaged {
        background-color: rgba(245, 158, 11, 0.2);
        color: #f59e0b;
    }

    .badge-kondisi.lost {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-approve, .btn-reject {
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .btn-approve {
        background-color: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid #10b981;
    }

    .btn-reject {
        background-color: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid #ef4444;
    }

    .btn-approve i, .btn-reject i {
        margin-right: 6px;
    }

    .btn-approve:hover {
        background-color: #10b981;
        color: white;
    }

    .btn-reject:hover {
        background-color: #ef4444;
        color: white;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .pengembalian-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
    }

    @media (max-width: 768px) {
        .action-buttons {
            flex-direction: column;
            gap: 8px;
        }
        
        .btn-approve, .btn-reject {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endsection
