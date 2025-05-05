<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }

        body { display: flex; min-height: 100vh; background-color: #f4f6f8; }

        .sidebar {
            width: 250px;
            background-color: #4b3f74;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            background-color: #d5d8dc;
            color: #2e2e2e;
            padding: 10px;
            margin-bottom: 30px;
            border-radius: 8px;
            text-align: center;
        }

        .menu-group {
            margin-bottom: 20px;
        }

        .menu-group h4 {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .menu-group a {
            color: white;
            display: block;
            padding: 8px 0;
            text-decoration: none;
        }

        .menu-group a:hover {
            text-decoration: underline;
        }

        .logout {
            margin-top: auto;
        }

        .content {
            flex: 1;
            padding: 30px;
            background-color: #f9fbfc;
        }

        .card-container {
            display: flex;
            gap: 20px;
            margin-top: 20px;
        }

        .card {
            flex: 1;
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .card h3 {
            margin-top: 10px;
            color: #333;
        }

        .card span {
            font-size: 24px;
            font-weight: bold;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>SMK TARUNA BHAKTI</h2>

        <div class="menu-group">
            <h4>ADMINISTRATOR</h4>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        </div>

        <div class="menu-group">
            <h4>Data Utama</h4>
            <a href="{{ route('kategori.index') }}">Kategori barang</a>
            <a href="{{ route('barang.index') }}">Data Barang</a>
        </div>

        <div class="menu-group">
            <h4>Transaksi</h4>
            <a href="#">Peminjaman Barang</a>
            <a href="#">Pengembalian Barang</a>
        </div>

        <div class="menu-group">
            <h4>Laporan</h4>
            <a href="{{ route('stock.index') }}">Laporan Stok</a>
            <a href="#">Data Peminjaman Barang</a>
            <a href="#">Data Pengembalian Barang</a>
        </div>

        <div class="menu-group logout">
            <h4>OPTIONS</h4>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button style="background:none; color:white; border:none; cursor:pointer;">Logout</button>
            </form>
        </div>
    </div>

    <div class="content">
        @yield('content')
    </div>
</body>
</html>
