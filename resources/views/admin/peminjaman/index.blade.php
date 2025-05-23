        @extends('layouts.app')

        @section('title', 'Daftar Peminjaman')

        @section('content')
        <div class="container py-4">
            <div class="peminjaman-header">
                <h1>📋 Daftar Peminjaman</h1>
                <!-- Removed the header-actions div with the Tambah Peminjaman button -->
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

            <div class="peminjaman-card">
                @if($peminjamans->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Belum ada data peminjaman</p>
                        <!-- Removed "Tambah Peminjaman Pertama" button -->
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="peminjaman-table">
                            <thead>
                                <tr>
                                    <th width="60">ID</th>
                                    <th>Nama Akun</th>
                                    <th>Barang</th>
                                    <th width="80">Jumlah</th>
                                    <th>Alasan</th>
                                    <th width="120">Tanggal Pinjam</th>
                                    <th width="120">Tanggal Kembali</th>
                                    <th width="100">Status</th>
                                    <th width="180">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamans as $peminjaman)
                                    <tr>
                                        <td class="text-center">{{ $peminjaman->id }}</td>
                                        <td>
                                            <div class="user-info">
                                                <i class="fas fa-user-circle"></i>
                                                <span>{{ $peminjaman->user->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $peminjaman->barang->nama ?? '-' }}</td>
                                        <td class="text-center">
                                            <span class="badge-jumlah">{{ $peminjaman->jumlah }}</span>
                                        </td>
                                        <td>
                                            <div class="alasan-text">{{ $peminjaman->alasan_pinjam }}</div>
                                        </td>
                                        <td>{{ $peminjaman->tanggal_pinjam }}</td>
                                        <td>{{ $peminjaman->tanggal_kembali }}</td>
                                        <td>
                                            @if($peminjaman->status == 'menunggu')
                                                <span class="badge-status pending">
                                                    <i class="fas fa-clock"></i> Menunggu
                                                </span>
                                            @elseif($peminjaman->status == 'disetujui')
                                                <span class="badge-status approved">
                                                    <i class="fas fa-check"></i> Disetujui
                                                </span>
                                            @elseif($peminjaman->status == 'ditolak')
                                                <span class="badge-status rejected">
                                                    <i class="fas fa-times"></i> Ditolak
                                                </span>
                                            @else
                                                <span class="badge-status returned">
                                                    <i class="fas fa-undo-alt"></i> {{ ucfirst($peminjaman->status) }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($peminjaman->status === 'menunggu')
                                                <div class="action-buttons">
                                                    <form action="{{ route('peminjaman.approve', $peminjaman->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn-approve" onclick="return confirm('Setujui peminjaman ini?')">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('peminjaman.reject', $peminjaman->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button class="btn-reject" onclick="return confirm('Tolak peminjaman ini?')">
                                                            <i class="fas fa-times"></i> Reject
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
                                        <td colspan="9" class="text-center text-muted">Belum ada data peminjaman.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
        @endsection

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
                color: #000000; /* Changed from #f8fafc to #000000 (black) */
                margin: 0;
                font-weight: 600;
                display: flex;
                align-items: center;
            }

            .header-actions {
                display: flex;
                gap: 10px;
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
            .peminjaman-card {
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

            .peminjaman-table {
                width: 100%;
                border-collapse: collapse;
            }

            .peminjaman-table th {
                background-color: #f8fafc;
                color: #1e293b;
                padding: 14px 16px;
                text-align: left;
                font-weight: 600;
                white-space: nowrap;
            }

            .peminjaman-table td {
                padding: 14px 16px;
                border-bottom: 1px solid #334155;
                color: #f8fafc;
                vertical-align: middle;
            }

            .peminjaman-table tr:hover {
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

            /* Alasan text */
            .alasan-text {
                max-width: 200px;
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
                display: inline-block;
            }

            .badge-status {
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

            .badge-status.returned {
                background-color: rgba(99, 102, 241, 0.2);
                color: #6366f1;
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
                .peminjaman-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 16px;
                }
                
                .alasan-text {
                    max-width: 150px;
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
                
                .alasan-text {
                    max-width: 100px;
                }
            }
        </style>
