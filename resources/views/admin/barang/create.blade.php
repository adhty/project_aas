@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold">Tambah Barang Baru</h5>
                    <a href="{{ route('barang.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body p-4">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="nama" class="form-label fw-semibold">Nama Barang</label>
                                    <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                                        placeholder="Masukkan nama barang" value="{{ old('nama') }}" required>
                                    @error('nama')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="jumlah_barang" class="form-label fw-semibold">Jumlah Barang</label>
                                    <input type="number" name="jumlah_barang" id="jumlah_barang" 
                                        class="form-control @error('jumlah_barang') is-invalid @enderror" 
                                        min="0" value="{{ old('jumlah_barang', 0) }}" required>
                                    @error('jumlah_barang')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="id_kategori" class="form-label fw-semibold">Kategori</label>
                            <select name="id_kategori" id="id_kategori" 
                                class="form-select @error('id_kategori') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategoris as $kategori)
                                    <option value="{{ $kategori->id }}" {{ old('id_kategori') == $kategori->id ? 'selected' : '' }}>
                                        {{ $kategori->nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="foto" class="form-label fw-semibold">Foto Barang</label>
                            <div class="input-group">
                                <input type="file" name="foto" id="foto" 
                                    class="form-control @error('foto') is-invalid @enderror" 
                                    accept="image/*" onchange="previewImage()">
                                <label class="input-group-text" for="foto">
                                    <i class="fas fa-upload"></i>
                                </label>
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Format: JPG, JPEG, PNG (Maks. 2MB)</small>
                            <div class="mt-2">
                                <img id="preview" class="img-thumbnail d-none" style="max-height: 200px">
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <button type="reset" class="btn btn-outline-secondary me-2">
                                <i class="fas fa-undo me-1"></i> Reset
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Barang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 12px;
        border: none;
        overflow: hidden;
    }
    
    .card-header {
        border-bottom: 1px solid #f0f0f0;
    }
    
    .form-control, .form-select {
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }
    
    .input-group-text {
        background-color: #f8fafc;
        border-color: #e2e8f0;
        color: #64748b;
    }
    
    .btn {
        padding: 0.6rem 1.5rem;
        border-radius: 8px;
        font-weight: 500;
        transition: all 0.2s;
    }
    
    .btn-primary {
        background-color: #3b82f6;
        border-color: #3b82f6;
    }
    
    .btn-primary:hover {
        background-color: #2563eb;
        border-color: #2563eb;
    }
    
    .btn-outline-secondary {
        color: #64748b;
        border-color: #e2e8f0;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8fafc;
        color: #334155;
        border-color: #cbd5e1;
    }
    
    .img-thumbnail {
        border-radius: 8px;
        border: 1px dashed #cbd5e1;
    }
</style>

<script>
    function previewImage() {
        const preview = document.getElementById('preview');
        const file = document.getElementById('foto').files[0];
        const reader = new FileReader();
        
        reader.onloadend = function() {
            preview.src = reader.result;
            preview.classList.remove('d-none');
        }
        
        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = '';
            preview.classList.add('d-none');
        }
    }
</script>
@endsection
