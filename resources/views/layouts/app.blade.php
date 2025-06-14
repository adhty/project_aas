<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #f1f5f9;
            color: #333;
        }

        .sidebar {
            width: 260px;
            background-color: #1e293b;
            color: #fff;
            padding: 30px 20px;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            overflow-y: auto; /* Tambahkan scroll vertikal */
            scrollbar-width: thin; /* Untuk Firefox */
            scrollbar-color: #475569 #1e293b; /* Untuk Firefox */
        }
        
        /* Styling untuk scrollbar di Chrome, Edge, dan Safari */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: #1e293b;
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background-color: #475569;
            border-radius: 6px;
        }
        
        /* Pastikan konten menu tidak terpotong */
        .menu-group {
            margin-bottom: 20px;
            width: 100%;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 10px;
        }
        
        .sidebar-logo {
            max-width: 25%;
            height: auto;
            margin: 0 auto;
            display: block;
        }
        
        .sidebar h2 {
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 30px;
            color: #fff;
        }

        .menu-group {
            margin-bottom: 20px;
        }
        
        .menu-title {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            margin-bottom: 10px;
            padding-left: 5px;
            font-weight: 600;
        }

        .menu-group a {
            display: block;
            color: #e2e8f0;
            text-decoration: none;
            padding: 10px 14px;
            border-radius: 6px;
            margin-bottom: 6px;
            transition: background-color 0.2s, color 0.2s;
        }

        .menu-group a:hover {
            background-color: #475569;
            color: #fff;
        }

        .menu-group a.active {
            background-color: #38bdf8;
            color: #0f172a;
            font-weight: bold;
        }

        .logout {
            margin-top: auto;
        }

        .logout button {
            background-color: transparent;
            border: none;
            color: #f87171;
            font-weight: bold;
            cursor: pointer;
            padding: 10px 12px;
            text-align: left;
            transition: background-color 0.2s;
            border-radius: 6px;
            width: 100%;
        }

        .logout button:hover {
            background-color: #7f1d1d;
        }

        .content {
            flex: 1;
            margin-left: 260px;
            padding: 40px;
            background-color: #f8fafc;
        }

        .card-container {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 30px;
        }

        .card {
            flex: 1 1 250px;
            background-color: #ffffff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
            text-align: center;
            border: 1px solid #e2e8f0;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08);
        }

        .card h3 {
            margin-top: 12px;
            color: #1e293b;
            font-size: 18px;
        }

        .card span {
            font-size: 28px;
            font-weight: 600;
            margin-top: 10px;
            display: block;
            color: #0f172a;
        }

        .sidebar-toggle {
            position: absolute;
            top: 20px;
            right: -15px;
            background: #38bdf8;
            color: #0f172a;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .sidebar.collapsed {
            transform: translateX(-260px);
        }
        
        .content.expanded {
            margin-left: 0;
        }
        
        .sidebar, .content {
            transition: all 0.3s ease;
        }
        
        @media (max-width: 768px) {
            .sidebar {
                z-index: 1000;
            }
            
            .content.expanded {
                width: 100%;
            }
        }
    </style>
    <!-- Di bagian head, tambahkan SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo-container">
            <img src="{{ asset('assets/logo.jpg') }}" alt="Logo Sarana Prasarana" class="sidebar-logo">
        </div>
        <h2>Sarana Prasarana</h2>

        <div class="menu-group">
            <h3 class="menu-title">Menu Utama</h3>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
        </div>

        <div class="menu-group">
            <h3 class="menu-title">Data Master</h3>
            <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">📂 Kategori Barang</a>
            <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">📦 Data Barang</a>
        </div>

        <div class="menu-group">
            <h3 class="menu-title">Transaksi</h3>
            <a href="{{ route('peminjaman.index') }}" class="{{ request()->is('peminjaman*') ? 'active' : '' }}">📋 Data Peminjaman</a>
            <a href="{{ route('admin.pengembalian.index') }}">🔁 Pengembalian Barang</a>
        </div>

        <div class="menu-group">
            <h3 class="menu-title">Laporan</h3>
            <a href="{{ route('laporan.peminjaman') }}">📚 Laporan Peminjaman</a>
            <a href="{{ route('laporan.pengembalian') }}">📥 Laporan Pengembalian</a>
        </div>

        <div class="menu-group">
            <h3 class="menu-title">Pengaturan</h3>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">👥 Manajemen User</a>
        </div>

        <div class="menu-group logout">
            <form method="POST" action="{{ route('logout') }}" id="logout-form">
                @csrf
                <button type="button" onclick="confirmLogout()">🚪 Logout</button>
            </form>
        </div>
    </div>

    <div class="content" id="content">
        @yield('content')
    </div>
    
    <!-- Add Font Awesome if not already included -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Add JavaScript for toggle functionality -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            const toggleBtn = document.getElementById('sidebarToggle');
            const toggleIcon = document.getElementById('toggleIcon');
            
            // Check if sidebar state is stored in localStorage
            const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
            
            // Apply initial state
            if (sidebarCollapsed) {
                sidebar.classList.add('collapsed');
                content.classList.add('expanded');
                toggleIcon.classList.remove('fa-chevron-left');
                toggleIcon.classList.add('fa-chevron-right');
            }
            
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                content.classList.toggle('expanded');
                
                // Toggle icon
                if (sidebar.classList.contains('collapsed')) {
                    toggleIcon.classList.remove('fa-chevron-left');
                    toggleIcon.classList.add('fa-chevron-right');
                    localStorage.setItem('sidebarCollapsed', 'true');
                } else {
                    toggleIcon.classList.remove('fa-chevron-right');
                    toggleIcon.classList.add('fa-chevron-left');
                    localStorage.setItem('sidebarCollapsed', 'false');
                }
            });
        });
    </script>

    <!-- Tambahkan script untuk konfirmasi logout -->
    <script>
        function confirmLogout() {
            if (confirm('Apakah Anda yakin ingin keluar dari Mobile ini?')) {
                document.getElementById('logout-form').submit();
            }
        }
    </script>
    <!-- Di bagian bawah sebelum </body>, tambahkan SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>
    <!-- Tambahkan yield untuk scripts -->
    @yield('scripts')
</body>
</html>
