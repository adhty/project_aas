        @extends('layouts.app')

        @section('title', 'Daftar Peminjaman')

        @section('content')
        <div class="container py-4">
            <div class="peminjaman-header">
                <h1><i class="fas fa-clipboard-list"></i> Daftar Peminjaman</h1>
            </div>

            @if(session('success'))
                <div class="alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @elseif(session('error'))
                <div class="alert-danger">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Search and Filter Section -->
            <div class="search-filter-container">
                <div class="search-box">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Cari peminjaman..." class="search-input">
                    <button id="clearSearch" class="clear-search" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                
                <div class="filter-box">
                    <select id="statusFilter" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="menunggu">Menunggu</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                        <option value="dikembalikan">Dikembalikan</option>
                    </select>
                    
                    <div class="date-filter">
                        <input type="date" id="dateFilter" class="date-input" placeholder="Filter tanggal">
                        <button id="clearDateFilter" class="clear-date" style="display: none;">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="peminjaman-card">
                @if($peminjamans->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Belum ada data peminjaman</p>
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
                            <tbody id="peminjamanTableBody">
                                @forelse($peminjamans as $peminjaman)
                                    <tr class="peminjaman-row" 
                                        data-id="{{ $peminjaman->id }}"
                                        data-user="{{ strtolower($peminjaman->user->name ?? '') }}"
                                        data-barang="{{ strtolower($peminjaman->barang->nama ?? '') }}"
                                        data-alasan="{{ strtolower($peminjaman->alasan_pinjam) }}"
                                        data-tanggal-pinjam="{{ $peminjaman->tanggal_pinjam }}"
                                        data-status="{{ $peminjaman->status }}">
                                        <td class="text-center">{{ $peminjaman->id }}</td>
                                        <td>
                                            <div class="user-info">
                                                <i class="fas fa-user-circle"></i>
                                                <span>{{ $peminjaman->user->name ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="barang-info">
                                                <i class="fas fa-box"></i>
                                                <span>{{ $peminjaman->barang->nama ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge-jumlah">{{ $peminjaman->jumlah }}</span>
                                        </td>
                                        <td>
                                            <div class="alasan-text" title="{{ $peminjaman->alasan_pinjam }}">
                                                {{ $peminjaman->alasan_pinjam }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <i class="fas fa-calendar-alt"></i>
                                                {{ $peminjaman->tanggal_pinjam }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-info">
                                                <i class="fas fa-calendar-check"></i>
                                                {{ $peminjaman->tanggal_kembali }}
                                            </div>
                                        </td>
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
            }

            /* Alert */
            .alert-success,
            .alert-danger {
                padding: 16px;
                border-radius: 8px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                position: relative;
                animation: fadeInUp 0.5s ease;
            }

            .alert-success {
                background: linear-gradient(135deg, #065f46, #047857);
                border-left: 4px solid #10b981;
                color: #ecfdf5;
            }

            .alert-danger {
                background: linear-gradient(135deg, #991b1b, #b91c1c);
                border-left: 4px solid #ef4444;
                color: #fef2f2;
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
                display: flex;
                align-items: center;
                justify-content: center;
                width: 24px;
                height: 24px;
                border-radius: 50%;
                transition: background-color 0.2s;
            }

            .btn-close:hover {
                opacity: 0.8;
                background-color: rgba(255, 255, 255, 0.1);
            }

            /* Card */
            .peminjaman-card {
                background-color: #0f172a;
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
                overflow: hidden;
                transition: all 0.3s ease;
            }
            
            .peminjaman-card:hover {
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
            }

            /* Empty state */
            .empty-state {
                padding: 60px 20px;
                text-align: center;
                background: linear-gradient(135deg, #0f172a, #1e293b);
                border-radius: 10px;
            }

            .empty-state i {
                font-size: 48px;
                color: #60a5fa;
                margin-bottom: 16px;
                animation: pulse 2s infinite;
            }

            .empty-state p {
                color: #cbd5e1;
                margin-bottom: 10px;
                font-size: 16px;
            }
            
            .empty-state-info {
                color: #94a3b8;
                font-size: 14px;
            }

            /* Search and Filter Styles */
            .search-filter-container {
                display: flex;
                gap: 15px;
                margin-bottom: 20px;
                flex-wrap: wrap;
            }
            
            .search-box {
                flex: 1;
                position: relative;
                min-width: 250px;
            }
            
            .search-input {
                width: 100%;
                padding: 12px 40px 12px 40px;
                border: 1px solid #334155;
                border-radius: 8px;
                font-size: 14px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                background-color: #1e293b;
                color: #f8fafc;
            }
            
            .search-input::placeholder {
                color: #94a3b8;
            }
            
            .search-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
                outline: none;
            }
            
            .search-icon {
                position: absolute;
                left: 14px;
                top: 50%;
                transform: translateY(-50%);
                color: #94a3b8;
                font-size: 16px;
            }
            
            .clear-search {
                position: absolute;
                right: 14px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #94a3b8;
                cursor: pointer;
                font-size: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                transition: all 0.2s ease;
            }
            
            .clear-search:hover {
                background-color: #334155;
                color: #f8fafc;
            }
            
            .filter-box {
                display: flex;
                gap: 10px;
                flex-wrap: wrap;
            }
            
            .filter-select {
                padding: 10px 14px;
                border: 1px solid #334155;
                border-radius: 8px;
                font-size: 14px;
                background-color: #1e293b;
                color: #f8fafc;
                min-width: 150px;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%2394a3b8' width='18px' height='18px'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 10px center;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }
            
            .filter-select:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
                outline: none;
            }
            
            .date-filter {
                position: relative;
            }
            
            .date-input {
                padding: 10px 14px;
                border: 1px solid #334155;
                border-radius: 8px;
                font-size: 14px;
                background-color: #1e293b;
                color: #f8fafc;
                min-width: 180px;
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }
            
            .date-input:focus {
                border-color: #3b82f6;
                box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
                outline: none;
            }
            
            .clear-date {
                position: absolute;
                right: 10px;
                top: 50%;
                transform: translateY(-50%);
                background: none;
                border: none;
                color: #94a3b8;
                cursor: pointer;
                font-size: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                transition: all 0.2s ease;
            }
            
            .clear-date:hover {
                background-color: #334155;
                color: #f8fafc;
            }

            /* No Results Styles */
            .no-results {
                padding: 40px 20px;
                text-align: center;
                background: linear-gradient(135deg, #0f172a, #1e293b);
                border-radius: 10px;
                margin-top: 20px;
            }
            
            .no-results i {
                font-size: 40px;
                color: #60a5fa;
                margin-bottom: 16px;
            }
            
            .no-results p {
                color: #cbd5e1;
                margin-bottom: 20px;
                font-size: 16px;
            }
            
            .btn-reset {
                background: linear-gradient(135deg, #3b82f6, #2563eb);
                color: white;
                border: none;
                padding: 10px 16px;
                border-radius: 8px;
                font-size: 14px;
                display: inline-flex;
                align-items: center;
                cursor: pointer;
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
            }
            
            .btn-reset i {
                margin-right: 8px;
                font-size: 14px;
                color: white;
            }
            
            .btn-reset:hover {
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                transform: translateY(-2px);
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

            .text-muted {
                color: #94a3b8;
                font-style: italic;
            }

            /* User info */
            .user-info {
                display: flex;
                align-items: center;
                gap: 8px;
            }

            .user-info i {
                color: #3b82f6;
                font-size: 16px;
            }
            
            .user-info span {
                font-weight: 500;
            }
            
            /* Barang info */
            .barang-info {
                display: flex;
                align-items: center;
                gap: 8px;
            }
            
            .barang-info i {
                color: #f59e0b;
                font-size: 14px;
            }
            
            .barang-info span {
                font-weight: 500;
            }

            /* Date info */
            .date-info {
                display: flex;
                align-items: center;
                gap: 6px;
                font-size: 13px;
                color: #475569;
            }
            
            .date-info i {
                color: #64748b;
                font-size: 12px;
            }

            /* Alasan text */
            .alasan-text {
                max-width: 200px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
                padding: 6px 10px;
                background-color: #f8fafc;
                border-radius: 6px;
                font-size: 13px;
                color: #475569;
                border-left: 3px solid #cbd5e1;
                transition: all 0.3s ease;
            }
            
            .alasan-text:hover {
                background-color: #f1f5f9;
                border-left-color: #3b82f6;
                transform: translateX(2px);
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
                transition: all 0.3s ease;
            }
            
            .badge-jumlah:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4);
            }

            .badge-status {
                padding: 6px 10px;
                border-radius: 6px;
                font-size: 12px;
                display: inline-flex;
                align-items: center;
                gap: 6px;
                font-weight: 500;
                transition: all 0.3s ease;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            }

            .badge-status.pending {
                background-color: #854d0e;
                color: #fef9c3;
                border: 1px solid #ca8a04;
            }
            
            .badge-status.pending:hover {
                background-color: #a16207;
                transform: translateY(-2px);
            }

            .badge-status.approved {
                background-color: #065f46;
                color: #d1fae5;
                border: 1px solid #10b981;
            }
            
            .badge-status.approved:hover {
                background-color: #047857;
                transform: translateY(-2px);
            }

            .badge-status.rejected {
                background-color: #991b1b;
                color: #fee2e2;
                border: 1px solid #ef4444;
            }
            
            .badge-status.rejected:hover {
                background-color: #b91c1c;
                transform: translateY(-2px);
            }

            .badge-status.returned {
                background-color: #4338ca;
                color: #e0e7ff;
                border: 1px solid #6366f1;
            }
            
            .badge-status.returned:hover {
                background-color: #4f46e5;
                transform: translateY(-2px);
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
                transition: all 0.3s ease;
                white-space: nowrap;
            }

            .btn-approve {
                background-color: #065f46;
                color: #d1fae5;
                box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
            }

            .btn-reject {
                background-color: #991b1b;
                color: #fee2e2;
                box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
            }

            .btn-approve i, .btn-reject i {
                margin-right: 6px;
            }

            .btn-approve:hover {
                background-color: #047857;
                color: white;
                transform: translateY(-2px);
            }

            .btn-reject:hover {
                background-color: #b91c1c;
                color: white;
                transform: translateY(-2px);
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

            /* Animations */
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes pulse {
                0% {
                    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
                }
                70% {
                    box-shadow: 0 0 0 6px rgba(59, 130, 246, 0);
                }
                100% {
                    box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
                }
            }
        </style>

        @section('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Elements
                const searchInput = document.getElementById('searchInput');
                const clearSearchBtn = document.getElementById('clearSearch');
                const statusFilter = document.getElementById('statusFilter');
                const dateFilter = document.getElementById('dateFilter');
                const clearDateFilterBtn = document.getElementById('clearDateFilter');
                const peminjamanRows = document.querySelectorAll('.peminjaman-row');
                const peminjamanTableBody = document.getElementById('peminjamanTableBody');
                
                // Function to filter table rows
                function filterTable() {
                    const searchTerm = searchInput.value.toLowerCase().trim();
                    const statusValue = statusFilter.value.toLowerCase();
                    const dateValue = dateFilter.value;
                    
                    let visibleCount = 0;
                    
                    peminjamanRows.forEach(row => {
                        const id = row.getAttribute('data-id');
                        const user = row.getAttribute('data-user');
                        const barang = row.getAttribute('data-barang');
                        const alasan = row.getAttribute('data-alasan');
                        const tanggalPinjam = row.getAttribute('data-tanggal-pinjam');
                        const status = row.getAttribute('data-status');
                        
                        // Check if row matches all filters
                        const matchesSearch = 
                            id.includes(searchTerm) || 
                            user.includes(searchTerm) || 
                            barang.includes(searchTerm) || 
                            alasan.includes(searchTerm);
                            
                        const matchesStatus = statusValue === '' || status === statusValue;
                        const matchesDate = dateValue === '' || tanggalPinjam === dateValue;
                        
                        const isVisible = matchesSearch && matchesStatus && matchesDate;
                        
                        // Show or hide row
                        row.style.display = isVisible ? '' : 'none';
                        
                        if (isVisible) {
                            visibleCount++;
                        }
                    });
                    
                    // Show or hide clear buttons
                    clearSearchBtn.style.display = searchTerm ? 'flex' : 'none';
                    clearDateFilterBtn.style.display = dateValue ? 'flex' : 'none';
                    
                    // Show no results message if needed
                    if (visibleCount === 0 && peminjamanRows.length > 0) {
                        let noResultsRow = document.getElementById('noResultsRow');
                        
                        if (!noResultsRow) {
                            noResultsRow = document.createElement('tr');
                            noResultsRow.id = 'noResultsRow';
                            noResultsRow.innerHTML = `
                                <td colspan="9" class="empty-data">
                                    <div class="empty-state">
                                        <i class="fas fa-search"></i>
                                        <p>Tidak ada peminjaman yang sesuai dengan pencarian Anda</p>
                                        <button id="resetFilters" class="btn-reset">
                                            <i class="fas fa-redo"></i> Reset Pencarian
                                        </button>
                                    </div>
                                </td>
                            `;
                            peminjamanTableBody.appendChild(noResultsRow);
                            
                            // Add event listener to reset button
                            document.getElementById('resetFilters').addEventListener('click', resetFilters);
                        }
                    } else {
                        const noResultsRow = document.getElementById('noResultsRow');
                        if (noResultsRow) {
                            noResultsRow.remove();
                        }
                    }
                }
                
                // Function to reset all filters
                function resetFilters() {
                    searchInput.value = '';
                    statusFilter.value = '';
                    dateFilter.value = '';
                    clearSearchBtn.style.display = 'none';
                    clearDateFilterBtn.style.display = 'none';
                    filterTable();
                }
                
                // Event listeners
                searchInput.addEventListener('input', filterTable);
                statusFilter.addEventListener('change', filterTable);
                dateFilter.addEventListener('change', filterTable);
                
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    clearSearchBtn.style.display = 'none';
                    filterTable();
                });
                
                clearDateFilterBtn.addEventListener('click', function() {
                    dateFilter.value = '';
                    clearDateFilterBtn.style.display = 'none';
                    filterTable();
                });
            });
        </script>
        @endsection
