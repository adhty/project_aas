@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="barang-header">
        <h1><i class="fas fa-boxes"></i> Daftar Barang</h1>
        <a href="{{ route('barang.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Tambah Barang
        </a>
    </div>
    
    @if(session('success'))
        <div class="alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    
    <div class="search-filter-container">
        <div class="search-box">
            <i class="fas fa-search search-icon"></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Cari barang...">
            <button type="button" class="clear-search" id="clearSearch">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="filter-box">
            <select id="kategoriFilter" class="filter-select">
                <option value="">Semua Kategori</option>
                @foreach($kategoris ?? [] as $kategori)
                    <option value="{{ $kategori->nama }}">{{ $kategori->nama }}</option>
                @endforeach
            </select>
            
            <select id="stockFilter" class="filter-select">
                <option value="">Semua Stok</option>
                <option value="low">Stok Rendah (≤ 5)</option>
                <option value="normal">Stok Normal (> 5)</option>
            </select>
        </div>
    </div>
    
    <div class="barang-card">
        @if($barang->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open"></i>
                <p>Belum ada barang yang tersedia</p>
                <a href="{{ route('barang.create') }}" class="btn-add-empty">
                    <i class="fas fa-plus-circle"></i> Tambah Barang Pertama
                </a>
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
                    <tbody id="barangTableBody">
                        @foreach($barang as $index => $item)
                        <tr class="barang-row" 
                            data-nama="{{ strtolower($item->nama) }}" 
                            data-kategori="{{ strtolower($item->kategori->nama ?? '') }}"
                            data-stock="{{ $item->jumlah_barang }}">
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
                                <span class="badge-jumlah {{ $item->jumlah_barang <= 5 ? 'low-stock' : '' }}">
                                    {{ $item->jumlah_barang }}
                                </span>
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
                                <form action="{{ route('barang.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus barang ini?')">
                                        <i class="fas fa-trash-alt"></i> Hapus
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
    
    <div id="noResults" class="no-results" style="display: none;">
        <i class="fas fa-search"></i>
        <p>Tidak ada barang yang sesuai dengan pencarian Anda</p>
        <button type="button" id="resetFilters" class="btn-reset">
            <i class="fas fa-undo"></i> Reset Filter
        </button>
    </div>
</div>

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
        color: #0f172a;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .barang-header h1 i {
        margin-right: 10px;
        color: #60a5fa;
        font-size: 22px;
    }

    .btn-add {
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

    .btn-add i {
        margin-right: 8px;
    }

    .btn-add:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }

    /* Alert */
    .alert-success {
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        position: relative;
        animation: fadeInUp 0.5s ease;
        background: linear-gradient(135deg, #065f46, #047857);
        border-left: 4px solid #10b981;
        color: #ecfdf5;
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

    /* Card */
    .barang-card {
        background-color: #0f172a;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .barang-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
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
        color: #f8fafc;
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
        border-bottom: 2px solid #3b82f6;
    }
    
    .barang-table th:first-child {
        border-top-left-radius: 8px;
    }
    
    .barang-table th:last-child {
        border-top-right-radius: 8px;
    }

    .barang-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #334155;
        color: #f8fafc;
        vertical-align: middle;
    }

    .barang-table tr:hover td {
        background-color: #1e293b;
    }
    
    .barang-table tr:last-child td {
        border-bottom: none;
    }
    
    .barang-table tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }
    
    .barang-table tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
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
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }
    
    .barang-image:hover {
        transform: scale(1.1);
    }
    
    .no-image {
        width: 60px;
        height: 60px;
        background-color: #1e293b;
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
    
    .badge-jumlah.low-stock {
        background-color: #ef4444;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    }
    
    .badge-kategori {
        background-color: #4f46e5;
        color: white;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 2px 4px rgba(79, 70, 229, 0.3);
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 8px;
    }

    .btn-edit, .btn-delete {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
    }

    .btn-edit {
        background-color: #0d9488;
        color: #f0fdfa;
        border: none;
        box-shadow: 0 2px 4px rgba(13, 148, 136, 0.3);
    }

    .btn-edit:hover {
        background-color: #0f766e;
        transform: translateY(-2px);
        color: white;
        text-decoration: none;
    }

    .btn-delete {
        background-color: #991b1b;
        color: #fee2e2;
        border: none;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    }

    .btn-delete:hover {
        background-color: #b91c1c;
        transform: translateY(-2px);
        color: white;
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
        margin-bottom: 20px;
        font-size: 16px;
    }

    .btn-add-empty {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-size: 15px;
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
    }

    .btn-add-empty i {
        margin-right: 8px;
    }

    .btn-add-empty:hover {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }

    /* No Results */
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
            box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .barang-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .btn-add {
            width: 100%;
            justify-content: center;
        }
        
        .search-filter-container {
            flex-direction: column;
        }
        
        .filter-box {
            width: 100%;
        }
        
        .filter-select {
            flex: 1;
        }
        
        .barang-table th, 
        .barang-table td {
            padding: 10px 12px;
            font-size: 13px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 6px;
        }
        
        .btn-edit, .btn-delete {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const clearSearchBtn = document.getElementById('clearSearch');
    const kategoriFilter = document.getElementById('kategoriFilter');
    const stockFilter = document.getElementById('stockFilter');
    const resetFiltersBtn = document.getElementById('resetFilters');
    const barangTableBody = document.getElementById('barangTableBody');
    const noResults = document.getElementById('noResults');
    
    // Function to filter table rows
    function filterBarang() {
        const searchTerm = searchInput.value.toLowerCase();
        const kategoriValue = kategoriFilter.value.toLowerCase();
        const stockValue = stockFilter.value;
        
        const barangRows = barangTableBody.querySelectorAll('tr.barang-row');
        let visibleCount = 0;
        
        barangRows.forEach(row => {
            const nama = row.getAttribute('data-nama');
            const kategori = row.getAttribute('data-kategori');
            const stock = parseInt(row.getAttribute('data-stock'));
            
            // Check if row matches all filters
            const matchesSearch = nama.includes(searchTerm);
            const matchesKategori = kategoriValue === '' || kategori === kategoriValue;
            let matchesStock = true;
            
            if (stockValue === 'low') {
                matchesStock = stock <= 5;
            } else if (stockValue === 'normal') {
                matchesStock = stock > 5;
            }
            
            const isVisible = matchesSearch && matchesKategori && matchesStock;
            
            // Show or hide row
            row.style.display = isVisible ? '' : 'none';
            
            if (isVisible) {
                visibleCount++;
            }
        });
        
        // Show or hide "No Results" message
        if (visibleCount === 0 && barangRows.length > 0) {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }
    
    // Event listeners
    searchInput.addEventListener('input', filterBarang);
    kategoriFilter.addEventListener('change', filterBarang);
    stockFilter.addEventListener('change', filterBarang);
    
    clearSearchBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterBarang();
    });
    
    resetFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        kategoriFilter.value = '';
        stockFilter.value = '';
        filterBarang();
    });
});
</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
