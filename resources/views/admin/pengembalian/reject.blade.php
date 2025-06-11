@extends('layouts.app')

@section('title', 'Tolak Pengembalian')

@section('content')
<div class="container py-4">
    <div class="reject-header">
        <h1><i class="fas fa-times-circle"></i> Tolak Pengembalian dengan Denda Manual</h1>
    </div>

    <div class="reject-card">
        <div class="alert-warning">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                <strong>Perhatian!</strong><br>
                Anda akan menolak pengembalian ini dan menetapkan denda manual.
            </div>
        </div>

        <div class="info-section">
            <h3><i class="fas fa-info-circle"></i> Informasi Pengembalian</h3>
            <div class="info-grid">
                <div class="info-item">
                    <label>ID Pengembalian</label>
                    <span class="info-value">{{ $pengembalian->id }}</span>
                </div>
                <div class="info-item">
                    <label>Nama Peminjam</label>
                    <span class="info-value">{{ $pengembalian->peminjaman->user->name ?? 'Data tidak tersedia' }}</span>
                </div>
                <div class="info-item">
                    <label>Barang</label>
                    <span class="info-value">{{ $pengembalian->peminjaman->barang->nama ?? 'Data tidak tersedia' }}</span>
                </div>
                <div class="info-item">
                    <label>Jumlah Kembali</label>
                    <span class="info-value">{{ $pengembalian->jumlah_kembali }}</span>
                </div>
                <div class="info-item">
                    <label>Kondisi Barang</label>
                    <span class="info-value">
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
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <label>Tanggal Pengembalian</label>
                    <span class="info-value">{{ $pengembalian->tanggal_pengembalian }}</span>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3><i class="fas fa-edit"></i> Form Penolakan</h3>
            <form action="{{ route('admin.pengembalian.process-reject', $pengembalian->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="biaya_denda_manual" class="form-label">
                        <i class="fas fa-money-bill-wave"></i> Biaya Denda Manual (Rp)
                    </label>
                    <input type="number"
                           class="form-control @error('biaya_denda_manual') is-invalid @enderror"
                           id="biaya_denda_manual"
                           name="biaya_denda_manual"
                           min="0"
                           step="1000"
                           value="{{ old('biaya_denda_manual', 0) }}"
                           placeholder="Masukkan jumlah denda"
                           required>
                    <small class="form-text">Masukkan jumlah denda yang ditentukan admin.</small>
                    @error('biaya_denda_manual')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="alasan_penolakan" class="form-label">
                        <i class="fas fa-comment-alt"></i> Alasan Penolakan
                    </label>
                    <textarea class="form-control @error('alasan_penolakan') is-invalid @enderror"
                              id="alasan_penolakan"
                              name="alasan_penolakan"
                              rows="4"
                              placeholder="Berikan alasan mengapa pengembalian ini ditolak..."
                              required>{{ old('alasan_penolakan') }}</textarea>
                    <small class="form-text">Berikan alasan yang jelas mengapa pengembalian ini ditolak.</small>
                    @error('alasan_penolakan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="action-buttons">
                    <a href="{{ route('admin.pengembalian.index') }}" class="btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn-danger" onclick="return confirm('Yakin ingin menolak pengembalian ini dengan denda manual?')">
                        <i class="fas fa-times-circle"></i> Tolak dengan Denda Manual
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* Container */
    .container {
        max-width: 1000px;
        margin: 0 auto;
    }

    /* Header */
    .reject-header {
        margin-bottom: 30px;
    }

    .reject-header h1 {
        font-size: 28px;
        color: #1e293b;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .reject-header h1 i {
        margin-right: 12px;
        color: #ef4444;
        font-size: 26px;
    }

    /* Card */
    .reject-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }

    /* Alert */
    .alert-warning {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #92400e;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border-bottom: 1px solid #e2e8f0;
    }

    .alert-warning i {
        font-size: 24px;
        color: #92400e;
    }

    .alert-warning strong {
        color: #78350f;
    }

    /* Info Section */
    .info-section {
        padding: 30px;
        border-bottom: 1px solid #e2e8f0;
    }

    .info-section h3 {
        font-size: 20px;
        color: #1e293b;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .info-section h3 i {
        margin-right: 10px;
        color: #3b82f6;
        font-size: 18px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .info-item label {
        font-weight: 600;
        color: #64748b;
        font-size: 14px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 16px;
        color: #1e293b;
        font-weight: 500;
    }

    /* Badges */
    .badge-kondisi {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
    }

    .badge-kondisi.good {
        background-color: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
    }

    .badge-kondisi.damaged {
        background-color: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }

    .badge-kondisi.lost {
        background-color: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
    }

    /* Form Section */
    .form-section {
        padding: 30px;
    }

    .form-section h3 {
        font-size: 20px;
        color: #1e293b;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        font-weight: 600;
    }

    .form-section h3 i {
        margin-right: 10px;
        color: #ef4444;
        font-size: 18px;
    }

    .form-group {
        margin-bottom: 25px;
    }

    .form-label {
        display: flex;
        align-items: center;
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
        font-size: 15px;
    }

    .form-label i {
        margin-right: 8px;
        color: #6b7280;
        font-size: 14px;
    }

    .form-control {
        width: 100%;
        padding: 12px 16px;
        border: 2px solid #e5e7eb;
        border-radius: 8px;
        font-size: 15px;
        transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .form-control:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .form-text {
        font-size: 13px;
        color: #6b7280;
        margin-top: 6px;
        display: block;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 13px;
        margin-top: 6px;
        display: block;
    }

    /* Action Buttons */
    .action-buttons {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
        gap: 15px;
    }

    .btn-secondary, .btn-danger {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
    }

    .btn-secondary {
        background-color: #6b7280;
        color: white;
        box-shadow: 0 2px 4px rgba(107, 114, 128, 0.3);
    }

    .btn-secondary:hover {
        background-color: #4b5563;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(107, 114, 128, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-danger {
        background-color: #ef4444;
        color: white;
        box-shadow: 0 2px 4px rgba(239, 68, 68, 0.3);
    }

    .btn-danger:hover {
        background-color: #dc2626;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(239, 68, 68, 0.4);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-secondary, .btn-danger {
            justify-content: center;
            width: 100%;
        }

        .reject-header h1 {
            font-size: 24px;
        }
    }
</style>
@endsection