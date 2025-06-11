@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="mb-4">
                <a href="{{ route('admin.users.index') }}" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
            
            <div class="card shadow-sm">
                <div class="card-header">
                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i> Tambah User Baru</h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="create-user-form">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                    id="name" name="name" value="{{ old('name') }}" placeholder="Masukkan nama lengkap" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                    id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password" placeholder="Masukkan password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                <input type="password" class="form-control" 
                                    id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="form-label">Role</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                <select class="form-select @error('role') is-invalid @enderror" 
                                    id="role" name="role" required>
                                    <option value="">Pilih Role</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="button" onclick="confirmSubmit()" class="btn btn-primary py-2">
                                <i class="fas fa-save me-2"></i> Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .btn-back {
        display: inline-flex;
        align-items: center;
        color: #4b5563;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s;
        padding: 8px 0;
    }
    
    .btn-back i {
        margin-right: 8px;
    }
    
    .btn-back:hover {
        color: #1f2937;
    }
    
    .card {
        border-radius: 10px;
        border: none;
        overflow: hidden;
    }
    
    .card-header {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 15px 20px;
    }
    
    .card-header h4 {
        color: #111827;
        font-weight: 600;
        font-size: 18px;
    }
    
    .form-label {
        color: #4b5563;
        font-weight: 500;
        margin-bottom: 8px;
    }
    
    .input-group-text {
        background-color: #f9fafb;
        border-color: #d1d5db;
        color: #6b7280;
    }
    
    .form-control, .form-select {
        border-color: #d1d5db;
        padding: 10px 15px;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
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
        color: #4b5563;
        border-color: #d1d5db;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f3f4f6;
        color: #1f2937;
        border-color: #d1d5db;
    }
</style>
@endsection

@section('scripts')
<script>
    function confirmSubmit() {
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        
        Swal.fire({
            title: 'Konfirmasi Tambah User',
            html: `Apakah data berikut sudah benar?<br>
                  <b>Nama:</b> ${name}<br>
                  <b>Email:</b> ${email}`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('create-user-form').submit();
            }
        });
    }
</script>
@endsection
