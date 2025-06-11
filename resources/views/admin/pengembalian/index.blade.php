@extends('layouts.app')

@section('title', 'Daftar Pengembalian')

@section('content')
<div class="container py-4">
    <div class="pengembalian-header">
        <h1><i class="fas fa-undo-alt"></i> Daftar Pengembalian</h1>
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
    <div class="filter-section">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" placeholder="Cari pengembalian...">
            <button id="clearSearch" class="clear-btn" style="display: none;">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="filter-options">
            <div class="filter-item">
                <label for="statusFilter">Status:</label>
                <select id="statusFilter">
                    <option value="">Semua Status</option>
                    <option value="menunggu">Menunggu</option>
                    <option value="diterima">Diterima</option>
                    <option value="ditolak">Ditolak</option>
                </select>
            </div>
            
            <div class="filter-item">
                <label for="kondisiFilter">Kondisi:</label>
                <select id="kondisiFilter">
                    <option value="">Semua Kondisi</option>
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>
            
            <div class="filter-item">
                <label for="dateFilter">Tanggal:</label>
                <div class="date-input-wrapper">
                    <input type="date" id="dateFilter">
                    <button id="clearDateFilter" class="clear-date-btn" style="display: none;">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

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
                <tbody id="pengembalianTableBody">
                    @forelse($pengembalians as $pengembalian)
                    <tr class="pengembalian-row" 
                        data-id="{{ $pengembalian->id }}"
                        data-user="{{ strtolower($pengembalian->peminjaman->user->name ?? '') }}"
                        data-barang="{{ strtolower($pengembalian->peminjaman->barang->nama ?? '') }}"
                        data-alasan="{{ strtolower($pengembalian->peminjaman->alasan_pinjam ?? '') }}"
                        data-tanggal="{{ $pengembalian->tanggal_pengembalian }}"
                        data-kondisi="{{ $pengembalian->kondisi_barang }}"
                        data-status="{{ $pengembalian->status }}">
                        <td class="text-center">{{ $pengembalian->id }}</td>
                        <td>
                            <div class="user-info">
                                <i class="fas fa-user-circle"></i>
                                <span>{{ $pengembalian->peminjaman->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="barang-info">
                                <i class="fas fa-box"></i>
                                <span>{{ $pengembalian->peminjaman->barang->nama ?? '-' }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="alasan-text" title="{{ $pengembalian->peminjaman->alasan_pinjam ?? '-' }}">
                                {{ $pengembalian->peminjaman->alasan_pinjam ?? '-' }}
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge-jumlah">{{ $pengembalian->peminjaman->jumlah ?? '-' }}</span>
                        </td>
                        <td>
                            <div class="date-info">
                                <i class="fas fa-calendar-check"></i>
                                {{ $pengembalian->tanggal_pengembalian }}
                            </div>
                        </td>
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
                            <span class="badge-kondisi">
                                <i class="fas fa-info-circle"></i> {{ ucfirst($pengembalian->kondisi_barang) }}
                            </span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge-denda {{ $pengembalian->biaya_denda > 0 ? 'has-denda' : '' }}">
                                {{ $pengembalian->biaya_denda > 0 ? 'Rp '.number_format($pengembalian->biaya_denda, 0, ',', '.') : '-' }}
                            </span>
                        </td>
                        <td>
                            @if($pengembalian->status == 'pending' || $pengembalian->status == 'menunggu')
                            <span class="badge-status pending">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                            @elseif($pengembalian->status == 'disetujui')
                            <span class="badge-status approved">
                                <i class="fas fa-check"></i> Disetujui
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
                                <form action="{{ route('admin.pengembalian.approve', $pengembalian->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn-approve" onclick="return confirm('Terima pengembalian ini?')">
                                        <i class="fas fa-check"></i> Terima
                                    </button>
                                </form>
                                <a href="{{ route('admin.pengembalian.reject', $pengembalian->id) }}" class="btn-reject" onclick="return confirm('Tolak pengembalian ini?')">
                                    <i class="fas fa-times"></i> Tolak
                                </a>
                            </div>
                            @else
                            <span class="text-muted">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted">Belum ada data pengembalian.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- No Results Message -->
        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <p>Tidak ada pengembalian yang sesuai dengan pencarian Anda</p>
            <button id="resetSearch" class="btn-reset">
                <i class="fas fa-redo"></i> Reset Pencarian
            </button>
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
        color: #1e293b;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .pengembalian-header h1 i {
        margin-right: 10px;
        color: #60a5fa;
        font-size: 22px;
    }

    /* Search and Filter Styles */
    .filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .search-box {
        position: relative;
        flex: 1;
        min-width: 250px;
        max-width: 400px;
    }
    
    .search-icon {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #64748b;
    }
    
    #searchInput {
        width: 100%;
        padding: 10px 40px 10px 35px;
        border: 1px solid #334155;
        border-radius: 8px;
        font-size: 14px;
        background-color: #1e293b;
        color: #f8fafc;
        transition: all 0.3s ease;
    }
    
    #searchInput:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .clear-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        transition: all 0.2s;
    }
    
    .clear-btn:hover {
        background-color: #334155;
        color: #f8fafc;
    }
    
    .filter-options {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .filter-item {
        min-width: 150px;
    }
    
    .filter-item label {
        display: block;
        margin-bottom: 5px;
        font-size: 13px;
        color: #94a3b8;
    }
    
    .filter-item select, 
    .filter-item input[type="date"] {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #334155;
        border-radius: 6px;
        background-color: #1e293b;
        color: #f8fafc;
        font-size: 14px;
    }
    
    .filter-item select:focus, 
    .filter-item input[type="date"]:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .date-input-wrapper {
        position: relative;
    }
    
    .clear-date-btn {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        transition: all 0.2s;
    }
    
    .clear-date-btn:hover {
        background-color: #334155;
        color: #f8fafc;
    }
    
    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-box {
            max-width: 100%;
        }
        
        .filter-options {
            width: 100%;
        }
        
        .filter-item {
            flex: 1;
            min-width: 120px;
        }
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

    .alert-success i,
    .alert-danger i {
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
    .pengembalian-card {
        background-color: #0f172a;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .pengembalian-card:hover {
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

    /* Table */
    .table-responsive {
        overflow-x: auto;
    }

    .pengembalian-table {
        width: 100%;
        border-collapse: collapse;
    }

    .pengembalian-table th {
        background-color: #1e293b;
        color: #f8fafc;
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
        border-bottom: 2px solid #3b82f6;
    }

    .pengembalian-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #334155;
        color: #f8fafc;
        vertical-align: middle;
    }

    .pengembalian-table tr:hover {
        background-color: #1e293b;
    }

    .text-center {
        text-align: center;
    }

    .text-muted {
        color: #94a3b8;
    }

    /* User info */
    .user-info, .barang-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .user-info i, .barang-info i {
        color: #60a5fa;
        font-size: 16px;
    }

    /* Date info */
    .date-info {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .date-info i {
        color: #60a5fa;
        font-size: 14px;
    }

    /* Alasan text */
    .alasan-text {
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
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

    .badge-status,
    .badge-kondisi {
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
    }

    .badge-status.pending {
        background-color: #854d0e;
        color: #fef9c3;
        border: 1px solid #ca8a04;
    }

    .badge-status.approved {
        background-color: #065f46;
        color: #d1fae5;
        border: 1px solid #10b981;
    }

    .badge-status.rejected {
        background-color: #991b1b;
        color: #fee2e2;
        border: 1px solid #ef4444;
    }

    .badge-kondisi.good {
        background-color: #065f46;
        color: #d1fae5;
        border: 1px solid #10b981;
    }

    .badge-kondisi.damaged {
        background-color: #854d0e;
        color: #fef9c3;
        border: 1px solid #ca8a04;
    }

    .badge-kondisi.lost {
        background-color: #991b1b;
        color: #fee2e2;
        border: 1px solid #ef4444;
    }

    .badge-denda {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-denda.has-denda {
        background-color: #991b1b;
        color: #fee2e2;
        border: 1px solid #ef4444;
        animation: pulse 2s infinite;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .btn-approve, .btn-reject {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }

    .btn-approve {
        background-color: #065f46;
        color: #d1fae5;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }

    .btn-approve:hover {
        background-color: #047857;
        transform: translateY(-2px);
    }

    .btn-reject {
        background-color: #991b1b;
        color: #fee2e2;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    }

    .btn-reject:hover {
        background-color: #b91c1c;
        transform: translateY(-2px);
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
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        }
        70% {
            box-shadow: 0 0 0 6px rgba(239, 68, 68, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .filter-section {
            flex-direction: column;
        }
        
        .filter-options {
            width: 100%;
        }
        
        .filter-select, .date-input {
            width: 100%;
        }
        
        .pengembalian-table th, 
        .pengembalian-table td {
            padding: 10px 12px;
            font-size: 13px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }
        
        .btn-approve, .btn-reject {
            width: 100%;
            justify-content: center;
        }
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
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const searchInput = document.getElementById('searchInput');
        const clearSearchBtn = document.getElementById('clearSearch');
        const statusFilter = document.getElementById('statusFilter');
        const kondisiFilter = document.getElementById('kondisiFilter');
        const dateFilter = document.getElementById('dateFilter');
        const clearDateFilterBtn = document.getElementById('clearDateFilter');
        const pengembalianRows = document.querySelectorAll('.pengembalian-row');
        const pengembalianTableBody = document.getElementById('pengembalianTableBody');
        
        // Function to filter table rows
        function filterTable() {
            const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const statusValue = statusFilter ? statusFilter.value.toLowerCase() : '';
            const kondisiValue = kondisiFilter ? kondisiFilter.value.toLowerCase() : '';
            const dateValue = dateFilter ? dateFilter.value : '';
            
            let visibleCount = 0;
            
            pengembalianRows.forEach(row => {
                const id = row.getAttribute('data-id');
                const user = row.getAttribute('data-user');
                const barang = row.getAttribute('data-barang');
                const alasan = row.getAttribute('data-alasan');
                const tanggal = row.getAttribute('data-tanggal');
                const kondisi = row.getAttribute('data-kondisi');
                const status = row.getAttribute('data-status');
                
                // Check if row matches all filters
                const matchesSearch = 
                    id.includes(searchTerm) || 
                    user.includes(searchTerm) || 
                    barang.includes(searchTerm) || 
                    alasan.includes(searchTerm);
                    
                const matchesStatus = statusValue === '' || status === statusValue;
                const matchesKondisi = kondisiValue === '' || kondisi === kondisiValue;
                const matchesDate = dateValue === '' || tanggal === dateValue;
                
                const isVisible = matchesSearch && matchesStatus && matchesKondisi && matchesDate;
                
                // Show or hide row
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                }
            });
            
            // Show or hide clear buttons
            if (clearSearchBtn) clearSearchBtn.style.display = searchTerm ? 'flex' : 'none';
            if (clearDateFilterBtn) clearDateFilterBtn.style.display = dateValue ? 'flex' : 'none';
            
            // Show no results message if needed
            if (visibleCount === 0 && pengembalianRows.length > 0) {
                let noResultsRow = document.getElementById('noResultsRow');
                
                if (!noResultsRow) {
                    noResultsRow = document.createElement('tr');
                    noResultsRow.id = 'noResultsRow';
                    noResultsRow.innerHTML = `
                        <td colspan="10" class="empty-data">
                            <div class="empty-state">
                                <i class="fas fa-search"></i>
                                <p>Tidak ada pengembalian yang sesuai dengan pencarian Anda</p>
                                <button id="resetFilters" class="btn-reset">
                                    <i class="fas fa-redo"></i> Reset Pencarian
                                </button>
                            </div>
                        </td>
                    `;
                    pengembalianTableBody.appendChild(noResultsRow);
                    
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
            if (searchInput) searchInput.value = '';
            if (statusFilter) statusFilter.value = '';
            if (kondisiFilter) kondisiFilter.value = '';
            if (dateFilter) dateFilter.value = '';
            if (clearSearchBtn) clearSearchBtn.style.display = 'none';
            if (clearDateFilterBtn) clearDateFilterBtn.style.display = 'none';
            filterTable();
        }
        
        // Event listeners
        if (searchInput) searchInput.addEventListener('input', filterTable);
        if (statusFilter) statusFilter.addEventListener('change', filterTable);
        if (kondisiFilter) kondisiFilter.addEventListener('change', filterTable);
        if (dateFilter) dateFilter.addEventListener('change', filterTable);
        
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                searchInput.value = '';
                clearSearchBtn.style.display = 'none';
                filterTable();
            });
        }
        
        if (clearDateFilterBtn) {
            clearDateFilterBtn.addEventListener('click', function() {
                dateFilter.value = '';
                clearDateFilterBtn.style.display = 'none';
                filterTable();
            });
        }
    });
</script>
@endsection
