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
        }

        .sidebar h2 {
            font-size: 20px;
            font-weight: 600;
            text-align: center;
            margin-bottom: 40px;
            background-color: #334155;
            padding: 14px;
            border-radius: 10px;
        }

        .menu-group {
            margin-bottom: 30px;
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
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>📁 Sarana Prasarana</h2>

        <div class="menu-group">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">🏠 Dashboard</a>
        </div>

        <div class="menu-group">
            <a href="{{ route('kategori.index') }}" class="{{ request()->routeIs('kategori.*') ? 'active' : '' }}">📂 Kategori Barang</a>
            <a href="{{ route('barang.index') }}" class="{{ request()->routeIs('barang.*') ? 'active' : '' }}">📦 Data Barang</a>
        </div>

        <div class="menu-group">
            <a href="{{ route('peminjaman.index') }}" class="{{ request()->is('peminjaman*') ? 'active' : '' }}">📋 Data Peminjaman</a>
            <a href="{{ route('pengembalian.index') }}">🔁 Pengembalian Barang</a>
        </div>

        <div class="menu-group">
            <a href="{{ route('stock.index') }}" class="{{ request()->routeIs('stock.index') ? 'active' : '' }}">📊 Laporan Stok</a>
            <a href="{{ route('laporan.peminjaman') }}">📚 Laporan Peminjaman</a>
            <a href="{{ route('laporan.pengembalian') }}">📥 Laporan Pengembalian</a>
        </div>

        <div class="menu-group logout">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button>🚪 Logout</button>
            </form>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
