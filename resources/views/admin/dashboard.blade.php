@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <div class="welcome-text">
            <h1>Selamat Datang, {{ Auth::user()->name }}</h1>
            <h2>Dashboard Admin</h2>
        </div>
        <div class="date-display">
            <span id="current-date"></span>
        </div>
    </div>

    <!-- Kartu statistik -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <h3>Total Barang</h3>
                <p>{{ $totalBarang }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
                <h3>Kategori</h3>
                <p>{{ $totalKategori }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
                <h3>Peminjaman</h3>
                <p>{{ $totalPeminjaman }}</p>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-undo-alt"></i>
            </div>
            <div class="stat-info">
                <h3>Pengembalian</h3>
                <p>{{ $totalPengembalian }}</p>
            </div>
        </div>
    </div>

    <!-- Informasi Barang dan Stok -->
    <div class="info-section">
        <div class="info-card">
            <h2><i class="fas fa-box-open"></i> Informasi Barang</h2>
            <div class="table-responsive">
                <table class="info-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $barangList = \App\Models\Barang::with('kategori')->limit(5)->get();
                        @endphp

                        @forelse($barangList as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <i class="fas fa-box table-icon"></i>
                                {{ $item->nama }}
                            </td>
                            <td>
                                <span class="category-badge">
                                    <i class="fas fa-tag"></i>
                                    {{ $item->kategori->nama ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="quantity-badge">{{ $item->jumlah_barang }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <i class="fas fa-inbox" style="font-size: 24px; margin-bottom: 10px; opacity: 0.5;"></i>
                                <br>Tidak ada data barang
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="info-card">
            <h2><i class="fas fa-exclamation-triangle"></i> Stok Menipis</h2>
            <div class="table-responsive">
                <table class="info-table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Stok Tersisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $stokMenipis = \App\Models\Barang::with('kategori')
                                ->where('jumlah_barang', '<=', 10)
                                ->orderBy('jumlah_barang', 'asc')
                                ->limit(5)
                                ->get();
                        @endphp

                        @forelse($stokMenipis as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <i class="fas fa-exclamation-circle table-icon"></i>
                                {{ $item->nama }}
                            </td>
                            <td>
                                <span class="quantity-badge">{{ $item->jumlah_barang }}</span>
                            </td>
                            <td>
                                @if($item->jumlah_barang == 0)
                                    <span class="status-badge status-empty">
                                        <i class="fas fa-times-circle"></i>
                                        Habis
                                    </span>
                                @elseif($item->jumlah_barang <= 5)
                                    <span class="status-badge status-critical">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Kritis
                                    </span>
                                @else
                                    <span class="status-badge status-low">
                                        <i class="fas fa-exclamation-circle"></i>
                                        Rendah
                                    </span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">
                                <i class="fas fa-check-circle" style="font-size: 24px; margin-bottom: 10px; color: #10b981;"></i>
                                <br>Semua stok aman
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    /* Reset dan Base */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    /* Dashboard Container */
    .dashboard-container {
        padding: 25px;
        background-color: #f5f7fa;
        min-height: 100vh;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    /* Header Section */
    .dashboard-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 30px;
        border-radius: 12px;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .welcome-text h1 {
        font-size: 28px;
        font-weight: 700;
        margin-bottom: 5px;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .welcome-text h2 {
        font-size: 16px;
        font-weight: 400;
        opacity: 0.9;
    }

    .date-display {
        background: rgba(255, 255, 255, 0.2);
        padding: 12px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* Statistics Cards */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 25px;
        margin-bottom: 35px;
    }

    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 25px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #667eea, #764ba2);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: white;
        margin-bottom: 15px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .stat-card:nth-child(1) .stat-icon {
        background: linear-gradient(135deg, #667eea, #764ba2);
    }

    .stat-card:nth-child(2) .stat-icon {
        background: linear-gradient(135deg, #f093fb, #f5576c);
    }

    .stat-card:nth-child(3) .stat-icon {
        background: linear-gradient(135deg, #4facfe, #00f2fe);
    }

    .stat-card:nth-child(4) .stat-icon {
        background: linear-gradient(135deg, #43e97b, #38f9d7);
    }

    .stat-info h3 {
        font-size: 14px;
        color: #64748b;
        margin-bottom: 8px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-info p {
        font-size: 32px;
        font-weight: 800;
        color: #1e293b;
        margin: 0;
        line-height: 1;
    }

    /* Information Section */
    .info-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px;
        margin-top: 10px;
    }

    .info-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.08);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .info-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
    }

    .info-card h2 {
        background: linear-gradient(135deg, #e0e7ff, #c7d2fe);
        padding: 15px 20px;
        margin: 0;
        font-size: 16px;
        color: #3730a3;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        border-bottom: 1px solid #d1d5db;
        border-radius: 12px 12px 0 0;
    }

    .info-card h2 i {
        color: #4f46e5;
        font-size: 18px;
    }

    .table-responsive {
        overflow-x: auto;
        padding: 0;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .info-table th {
        background: #6366f1;
        color: white;
        padding: 16px 20px;
        text-align: center;
        font-weight: 700;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        border: none;
        box-shadow: 0 2px 4px rgba(99, 102, 241, 0.2);
        position: relative;
    }

    .info-table th::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, #8b5cf6, #06b6d4);
    }

    .info-table td {
        padding: 16px 20px;
        text-align: center;
        border-bottom: 1px solid #e5e7eb;
        color: #374151;
        font-size: 14px;
        font-weight: 500;
        vertical-align: middle;
        position: relative;
    }

    .info-table tbody tr {
        transition: all 0.3s ease;
        background: white;
        border-left: 4px solid transparent;
    }

    .info-table tbody tr:nth-child(even) {
        background: #f8fafc;
    }

    .info-table tbody tr:hover {
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.15);
        border-left: 4px solid #6366f1;
    }

    .info-table tbody tr:last-child td {
        border-bottom: 3px solid #6366f1;
    }

    /* Styling khusus untuk kolom nomor */
    .info-table td:first-child {
        font-weight: 700;
        color: #6366f1;
        background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        border-right: 2px solid #e5e7eb;
        font-size: 16px;
    }

    /* Styling untuk nama barang */
    .info-table td:nth-child(2) {
        font-weight: 600;
        color: #1f2937;
        text-align: left;
        padding-left: 24px;
    }

    /* Styling untuk kategori */
    .info-table td:nth-child(3) {
        color: #059669;
        font-weight: 600;
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
    }

    /* Styling untuk jumlah/stok */
    .info-table td:last-child {
        font-weight: 700;
        color: #dc2626;
        background: linear-gradient(135deg, #fef2f2, #fee2e2);
        border-left: 2px solid #e5e7eb;
        font-size: 16px;
    }

    /* Badge style untuk angka */
    .info-table td:last-child::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 8px;
        transform: translateY(-50%);
        width: 4px;
        height: 20px;
        background: #dc2626;
        border-radius: 2px;
    }

    .text-center {
        text-align: center;
        color: #9ca3af;
        font-style: italic;
        padding: 30px 20px;
    }

    /* Badge styling untuk kategori dan jumlah */
    .category-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(16, 185, 129, 0.3);
    }

    .quantity-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 700;
        min-width: 50px;
        box-shadow: 0 3px 6px rgba(239, 68, 68, 0.3);
        position: relative;
        overflow: hidden;
    }

    .quantity-badge::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        animation: shimmer 2s infinite;
    }

    @keyframes shimmer {
        0% { left: -100%; }
        100% { left: 100%; }
    }

    /* Icon styling */
    .table-icon {
        margin-right: 8px;
        font-size: 16px;
        opacity: 0.8;
    }

    /* Hover effects untuk badges */
    .info-table tbody tr:hover .category-badge {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(16, 185, 129, 0.4);
    }

    .info-table tbody tr:hover .quantity-badge {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(239, 68, 68, 0.4);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .dashboard-container {
            padding: 20px;
        }

        .stats-row {
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-container {
            padding: 15px;
        }

        .dashboard-header {
            flex-direction: column;
            gap: 20px;
            padding: 25px;
            text-align: center;
        }

        .welcome-text h1 {
            font-size: 24px;
        }

        .stats-row {
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .info-section {
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .stat-card {
            padding: 20px;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            font-size: 20px;
        }

        .stat-info p {
            font-size: 28px;
        }

        .info-table th,
        .info-table td {
            padding: 10px 8px;
            font-size: 12px;
        }

        .info-card h2 {
            padding: 12px 15px;
            font-size: 15px;
        }
    }

    @media (max-width: 480px) {
        .dashboard-container {
            padding: 10px;
        }

        .dashboard-header {
            padding: 20px;
        }

        .welcome-text h1 {
            font-size: 20px;
        }

        .welcome-text h2 {
            font-size: 14px;
        }

        .stat-card {
            padding: 15px;
        }

        .stat-info p {
            font-size: 24px;
        }

        .info-card h2 {
            padding: 12px 15px;
            font-size: 14px;
        }

        .info-table th,
        .info-table td {
            padding: 8px 6px;
            font-size: 11px;
        }

        .info-table th {
            font-size: 10px;
        }
    }
</style>

<script>
    // Tampilkan tanggal
    document.addEventListener('DOMContentLoaded', function() {
        const dateElement = document.getElementById('current-date');
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const now = new Date();
        dateElement.textContent = now.toLocaleDateString('id-ID', options);
    });
</script>
@endsection
