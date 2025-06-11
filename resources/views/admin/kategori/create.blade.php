@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="kategori-header">
        <h1><i class="fas fa-plus-circle"></i> Tambah Kategori Baru</h1>
        <a href="{{ route('kategori.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="kategori-form-card">
        @if(session('success'))
            <div class="alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" onclick="this.parentElement.style.display='none'">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        @endif

        <form action="{{ route('kategori.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="nama">Nama Kategori</label>
                <div class="input-icon-wrapper">
                    <i class="fas fa-tag"></i>
                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                           value="{{ old('nama') }}" placeholder="Masukkan nama kategori" required autofocus>
                </div>
                @error('nama')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
                <div class="form-text">
                    Nama kategori harus unik dan mendeskripsikan jenis barang dengan jelas.
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-save"></i> Simpan Kategori
                </button>
                <a href="{{ route('kategori.index') }}" class="btn-cancel">
                    <i class="fas fa-times"></i> Batal
                </a>
            </div>
        </form>
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
    .kategori-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .kategori-header h1 {
        font-size: 24px;
        color: #1e293b;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }
    
    .kategori-header h1 i {
        margin-right: 10px;
        color: #3b82f6;
        font-size: 22px;
    }

    .btn-back {
        background: linear-gradient(135deg, #64748b, #475569);
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(100, 116, 139, 0.2);
    }

    .btn-back i {
        margin-right: 8px;
    }

    .btn-back:hover {
        background: linear-gradient(135deg, #475569, #334155);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(100, 116, 139, 0.3);
        color: white;
    }

    /* Form Card */
    .kategori-form-card {
        background-color: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        padding: 30px;
        transition: all 0.3s ease;
        animation: fadeInUp 0.5s ease;
    }
    
    .kategori-form-card:hover {
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }

    /* Alert */
    .alert-success {
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border-left: 4px solid #10b981;
        color: #065f46;
        padding: 16px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .alert-success .btn-close {
        background: none;
        border: none;
        color: #065f46;
        font-size: 16px;
        margin-left: 10px;
        cursor: pointer;
    }

    .alert-success .btn-close:hover {
        color: #10b981;
    }

    /* Form Group */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        font-weight: 600;
        color: #1e293b;
    }

    .input-icon-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-wrapper i {
        position: absolute;
        left: 10px;
        color: #64748b;
    }

    .form-control {
        padding-left: 30px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .form-control:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }

    .invalid-feedback {
        display: block;
        margin-top: 5px;
        color: #ef4444;
    }

    .form-text {
        font-size: 14px;
        color: #64748b;
        margin-top: 5px;
    }

    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-submit {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 16px;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2);
    }

    .btn-submit i {
        margin-right: 8px;
    }

    .btn-submit:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(59, 130, 246, 0.3);
    }

    .btn-cancel {
        background: linear-gradient(135deg, #f8fafc, #e2e8f0);
        color: #64748b;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 16px;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px rgba(100, 116, 139, 0.2);
    }

    .btn-cancel i {
        margin-right: 8px;
    }

    .btn-cancel:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        transform: translateY(-2px);
        box-shadow: 0 6px 8px rgba(100, 116, 139, 0.3);
        color: #334155;
    }

    /* Animasi */
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
</style>
@endsection
