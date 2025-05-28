@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-secondary me-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-user-edit me-2"></i> Edit User
                        </h5>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="text-center">
                        @csrf
                        @method('PUT')
                        
                        <div class="text-center mb-4">
                            <div class="avatar-placeholder mb-3">
                                <i class="fas fa-user-circle fa-5x text-secondary"></i>
                            </div>
                        </div>
                        
                        <div class="form-group row justify-content-center mb-4">
                            <label class="col-md-12 text-center mb-2 fw-bold">Nama Lengkap</label>
                            <div class="col-md-8">
                                <input type="text" name="name" class="form-control text-center @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row justify-content-center mb-4">
                            <label class="col-md-12 text-center mb-2 fw-bold">Email</label>
                            <div class="col-md-8">
                                <input type="email" name="email" class="form-control text-center @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row justify-content-center mb-4">
                            <label class="col-md-12 text-center mb-2 fw-bold">Password <span class="text-muted">(Kosongkan jika tidak ingin mengubah password)</span></label>
                            <div class="col-md-8">
                                <input type="password" name="password" class="form-control text-center @error('password') is-invalid @enderror">
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group row justify-content-center mb-4">
                            <label class="col-md-12 text-center mb-2 fw-bold">Role</label>
                            <div class="col-md-8">
                                <select name="role" class="form-select text-center @error('role') is-invalid @enderror">
                                    <option value="admin" {{ $user->hasRole('admin') ? 'selected' : '' }}>Admin</option>
                                    <option value="user" {{ $user->hasRole('user') ? 'selected' : '' }}>User</option>
                                </select>
                                @error('role')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="form-group text-center mt-5">
                            <button type="submit" class="btn btn-primary px-4 py-2">
                                <i class="fas fa-save me-2"></i> Simpan Perubahan
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
        border-radius: 15px;
        border: none;
    }
    
    .card-header {
        border-bottom: 1px solid #eee;
        border-top-left-radius: 15px !important;
        border-top-right-radius: 15px !important;
    }
    
    .form-control, .form-select {
        padding: 10px 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        transition: all 0.3s;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
    }
    
    .btn-primary {
        background-color: #4f46e5;
        border-color: #4f46e5;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .btn-primary:hover {
        background-color: #4338ca;
        border-color: #4338ca;
    }
    
    .btn-outline-secondary {
        border-radius: 8px;
    }
    
    .avatar-placeholder {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background-color: #f3f4f6;
    }
</style>
@endsection
