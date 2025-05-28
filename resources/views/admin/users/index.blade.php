@extends('layouts.app')

@section('content')
<div class="container">
    <div class="peminjaman-header">
        <h1><i class="fas fa-users me-2"></i> Manajemen User</h1>
        <div class="header-actions">
            <a href="{{ route('admin.users.create') }}" class="btn-add">
                <i class="fas fa-plus"></i> Tambah User
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th class="ps-3" width="5%">No</th>
                            <th width="25%">Nama</th>
                            <th width="25%">Email</th>
                            <th width="20%">Role</th>
                            <th width="15%">Tanggal Dibuat</th>
                            <th width="10%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr class="text-center">
                                <td class="ps-3">{{ $index + $users->firstItem() }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @php
                                        $roleText = '';
                                        if ($user->roles->isNotEmpty()) {
                                            $roleText = $user->roles->first()->name;
                                        } elseif (!empty($user->role)) {
                                            $roleText = $user->role;
                                        } else {
                                            $roleText = 'Tidak ada role';
                                        }
                                    @endphp
                                    <span class="badge bg-primary rounded-pill" style="font-size: 11px; padding: 4px 8px;">
                                        {{ ucfirst($roleText) }}
                                    </span>
                                </td>
                                <td>{{ $user->created_at->format('d M Y') }}</td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="btn-action edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-action delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Tidak ada data user</h5>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-center py-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    /* Gaya dasar */
    .container {
        max-width: 1200px;
        margin: 0 auto;
    }

    /* Header */
    .peminjaman-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .peminjaman-header h1 {
        font-size: 24px;
        color: #000000;
        margin: 0;
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-add {
        background-color: #3b82f6;
        color: white;
        border: none;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 14px;
        display: flex;
        align-items: center;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .btn-add i {
        margin-right: 8px;
    }

    .btn-add:hover {
        background-color: #2563eb;
        color: white;
    }

    /* Table styling */
    .card {
        border-radius: 10px;
        border: none;
        overflow: hidden;
        margin-bottom: 30px;
    }

    .table {
        margin-bottom: 0;
    }

    .table th {
        font-weight: 600;
        font-size: 14px;
        color: #4b5563;
        padding: 12px 16px;
        text-align: center;
    }

    .table td {
        vertical-align: middle;
        font-size: 13px;
        color: #1f2937;
        padding: 10px 16px;
        border-bottom: 1px solid #e5e7eb;
        text-align: center;
    }

    .badge {
        font-weight: 500;
        padding: 4px 8px;
        font-size: 11px;
    }

    /* Action buttons */
    .action-buttons {
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .btn-action {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid;
        transition: all 0.2s;
        background: transparent;
        cursor: pointer;
    }

    .btn-action.edit {
        color: #4f46e5;
        border-color: #4f46e5;
    }

    .btn-action.edit:hover {
        background-color: #4f46e5;
        color: white;
    }

    .btn-action.delete {
        color: #ef4444;
        border-color: #ef4444;
    }

    .btn-action.delete:hover {
        background-color: #ef4444;
        color: white;
    }

    /* Pagination styling */
    .pagination {
        margin-bottom: 0;
    }
</style>
@endsection






