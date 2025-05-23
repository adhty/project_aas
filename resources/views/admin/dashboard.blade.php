@extends('layouts.app')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Dashboard</h1>
        <div class="date-display">
            <span id="current-date"></span>
        </div>
    </div>

    <!-- Kartu statistik dengan styling yang ditingkatkan -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-box"></i>
            </div>
            <div class="stat-info">
                <h3>Total Barang</h3>
                <p>{{ $totalBarang }}</p>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="stat-label">Jumlah seluruh data barang</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-tags"></i>
            </div>
            <div class="stat-info">
                <h3>Kategori</h3>
                <p>{{ $totalKategori }}</p>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="stat-label">Total kategori barang</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
                <h3>Peminjaman</h3>
                <p>{{ $totalPeminjaman }}</p>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="stat-label">Total peminjaman barang</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-undo-alt"></i>
            </div>
            <div class="stat-info">
                <h3>Pengembalian</h3>
                <p>{{ $totalPengembalian }}</p>
                <div class="stat-progress">
                    <div class="progress-bar" style="width: 100%"></div>
                </div>
                <span class="stat-label">Total pengembalian barang</span>
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
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Fallback data jika $barangs tidak tersedia
                            $barangList = \App\Models\Barang::with('kategori')->limit(5)->get();
                        @endphp
                        
                        @forelse($barangList as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>
                                <span class="badge-kategori">
                                    <i class="fas fa-tag"></i> {{ $item->kategori->nama ?? '-' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge-jumlah">{{ $item->jumlah_barang }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center">Tidak ada data barang</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="info-card">
            <h2><i class="fas fa-cubes"></i> Informasi Stok</h2>
            <div class="table-responsive">
                <table class="info-table">
                    <thead>
                        <tr>
                            <th>Nama Barang</th>
                            <th>Stok Tersedia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            // Menggunakan data barang sebagai fallback jika $stocks tidak tersedia
                            $stockList = $barangList ?? collect();
                        @endphp
                        
                        @forelse($stockList as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            <td>
                                <span class="badge-jumlah">{{ $item->jumlah_barang }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="2" class="text-center">Tidak ada data stok</td>
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
    /* Gaya dasar */
    .dashboard-container {
        padding: 20px;
        background-color: #f8fafc;
        max-width: 1200px;
        margin: 0 auto;
        border-radius: 10px;
    }

    /* Header */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #e2e8f0;
    }

    .dashboard-header h1 {
        font-size: 24px;
        color: #1e293b;
        margin: 0;
        font-weight: 600;
    }

    .date-display {
        color: #64748b;
        font-size: 14px;
        background-color: #f1f5f9;
        padding: 6px 12px;
        border-radius: 6px;
    }

    /* Kartu statistik yang ditingkatkan */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .stat-card {
        background-color: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s, box-shadow 0.2s;
        position: relative;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        position: absolute;
        top: 20px;
        right: 20px;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }

    .stat-card:nth-child(1) .stat-icon {
        background-color: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .stat-card:nth-child(2) .stat-icon {
        background-color: rgba(16, 185, 129, 0.1);
        color: #10b981;
    }

    .stat-card:nth-child(3) .stat-icon {
        background-color: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .stat-card:nth-child(4) .stat-icon {
        background-color: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .stat-info {
        display: flex;
        flex-direction: column;
    }

    .stat-info h3 {
        font-size: 14px;
        color: #64748b;
        margin: 0 0 6px 0;
        font-weight: 500;
    }

    .stat-info p {
        font-size: 32px;
        font-weight: 700;
        color: #0f172a;
        margin: 0 0 15px 0;
    }

    .stat-progress {
        width: 100%;
        height: 6px;
        background-color: #f1f5f9;
        border-radius: 10px;
        margin-bottom: 10px;
    }

    .stat-card:nth-child(1) .progress-bar {
        background-color: #3b82f6;
    }

    .stat-card:nth-child(2) .progress-bar {
        background-color: #10b981;
    }

    .stat-card:nth-child(3) .progress-bar {
        background-color: #f59e0b;
    }

    .stat-card:nth-child(4) .progress-bar {
        background-color: #ef4444;
    }

    .progress-bar {
        height: 100%;
        border-radius: 10px;
    }

    .stat-label {
        font-size: 12px;
        color: #94a3b8;
    }

    /* Informasi Barang dan Stok */
    .info-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-top: 30px;
    }

    .info-card {
        background-color: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .info-card h2 {
        font-size: 18px;
        color: #1e293b;
        margin: 0 0 16px 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .info-card h2 i {
        margin-right: 10px;
        color: #3b82f6;
    }

    .table-responsive {
        overflow-x: auto;
    }

    .info-table {
        width: 100%;
        border-collapse: collapse;
    }

    .info-table th, 
    .info-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-table th {
        background-color: #f8fafc;
        font-weight: 600;
        color: #64748b;
    }

    .info-table tr:hover {
        background-color: #f1f5f9;
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

    /* Responsif */
    @media (max-width: 768px) {
        .stats-row {
            grid-template-columns: 1fr;
        }
        
        .info-section {
            grid-template-columns: 1fr;
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
