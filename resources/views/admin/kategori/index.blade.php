@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="kategori-header">
        <h1><i class="fas fa-tags"></i> Daftar Kategori</h1>
        <a href="{{ route('kategori.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i> Tambah Kategori
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
    
    @if(session('error'))
        <div class="alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
    
    <div class="kategori-card">
        @if($kategoris->isEmpty())
            <div class="empty-state">
                <i class="fas fa-folder-open"></i>
                <p>Belum ada kategori yang tersedia</p>
                <a href="{{ route('kategori.create') }}" class="btn-add-empty">
                    <i class="fas fa-plus-circle"></i> Tambah Kategori Pertama
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="kategori-table">
                    <thead>
                        <tr>
                            <th width="80">No</th>
                            <th>Nama Kategori</th>
                            <th>Jumlah Barang</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($kategoris as $index => $kategori)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="kategori-name">
                                    <i class="fas fa-tag"></i>
                                    <span>{{ $kategori->nama }}</span>
                                </div>
                            </td>
                            <td>
                                <span class="badge-count">
                                    {{ $kategori->barangs->count() }} barang
                                </span>
                            </td>
                            <td class="action-buttons">
                                <a href="{{ route('kategori.edit', $kategori->id) }}" class="btn-edit">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('kategori.destroy', $kategori->id) }}" method="POST" class="d-inline delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
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
</div>

<style>
    /* Gaya dasar */
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header */
    .kategori-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .kategori-header h1 {
        font-size: 24px;
        color: #f8fafc;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .kategori-header h1 i {
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
    .kategori-card {
        background-color: #0f172a;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .kategori-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.4);
    }

    /* Table */
    .table-responsive {
        overflow-x: auto;
    }

    .kategori-table {
        width: 100%;
        border-collapse: collapse;
    }

    .kategori-table th {
        background-color: #1e293b;
        color: #f8fafc;
        padding: 14px 16px;
        text-align: left;
        font-weight: 600;
        white-space: nowrap;
        border-bottom: 2px solid #3b82f6;
    }
    
    .kategori-table th:first-child {
        border-top-left-radius: 8px;
    }
    
    .kategori-table th:last-child {
        border-top-right-radius: 8px;
    }

    .kategori-table td {
        padding: 14px 16px;
        border-bottom: 1px solid #334155;
        color: #f8fafc;
        vertical-align: middle;
    }

    .kategori-table tr:hover td {
        background-color: #1e293b;
    }
    
    .kategori-table tr:last-child td {
        border-bottom: none;
    }
    
    .kategori-table tr:last-child td:first-child {
        border-bottom-left-radius: 8px;
    }
    
    .kategori-table tr:last-child td:last-child {
        border-bottom-right-radius: 8px;
    }

    .text-center {
        text-align: center;
    }

    /* Kategori name */
    .kategori-name {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .kategori-name i {
        color: #60a5fa;
        font-size: 16px;
    }

    .kategori-name span {
        font-weight: 500;
    }

    /* Badge */
    .badge-count {
        background-color: #3b82f6;
        color: white;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
        display: inline-block;
        box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
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
        .kategori-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        
        .btn-add {
            width: 100%;
            justify-content: center;
        }
        
        .kategori-table th, 
        .kategori-table td {
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

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endsection
