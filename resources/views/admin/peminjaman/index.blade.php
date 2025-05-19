        @extends('layouts.app')

        @section('title', 'Daftar Peminjaman')

        @section('content')
        <div class="container py-4">
            <h1 class="mb-4 text-light">📋 Daftar Peminjaman</h1>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-dark table-striped table-hover table-bordered align-middle">
                    <thead class="table-light text-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Akun</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Alasan</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $peminjaman)
                            <tr>
                                <td>{{ $peminjaman->id }}</td>
                                <td>{{ $peminjaman->user->name ?? '-' }}</td>
                                <td>{{ $peminjaman->barang->nama_barang ?? '-' }}</td>
                                <td>{{ $peminjaman->jumlah }}</td>
                                <td>{{ $peminjaman->alasan_pinjam }}</td>
                                <td>{{ $peminjaman->tanggal_pinjam }}</td>
                                <td>{{ $peminjaman->tanggal_kembali }}</td>
                                <td>
                                    @if($peminjaman->status == 'menunggu')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @elseif($peminjaman->status == 'disetujui')
                                        <span class="badge bg-success">Disetujui</span>
                                    @elseif($peminjaman->status == 'ditolak')
                                        <span class="badge bg-danger">Ditolak</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($peminjaman->status) }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($peminjaman->status === 'menunggu')
                                        <div class="btn-group">
                                            <form action="{{ route('peminjaman.approve', $peminjaman->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-success" onclick="return confirm('Setujui peminjaman ini?')">Approve</button>
                                            </form>
                                            <form action="{{ route('peminjaman.reject', $peminjaman->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-danger" onclick="return confirm('Tolak peminjaman ini?')">Reject</button>
                                            </form>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted">Belum ada data peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endsection
