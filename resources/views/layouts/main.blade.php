<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Delivery Note & Invoice</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f4f6f9; }
        .sidebar {
            position: fixed; top: 0; left: 0; width: 250px; height: 100vh;
            background: #1e2129; color: #fff; overflow-y: auto;
        }
        .sidebar .brand {
            padding: 1.1rem 1.25rem; font-weight: 700; font-size: 1.05rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }
        .sidebar .nav-section {
            padding: 0.75rem 1.25rem 0.35rem; font-size: 0.72rem;
            text-transform: uppercase; letter-spacing: 0.5px; color: #8a8f98;
        }
        .sidebar .nav-link {
            color: #d0d3d9; padding: 0.55rem 1.25rem; display: flex;
            align-items: center; gap: 0.6rem; font-size: 0.92rem;
            border-left: 3px solid transparent;
        }
        .sidebar .nav-link:hover { background: rgba(255,255,255,0.06); color: #fff; }
        .sidebar .nav-link.active {
            background: rgba(255,255,255,0.08); color: #fff;
            border-left: 3px solid #0d6efd;
        }
        .main-content { margin-left: 250px; }
        .topbar {
            background: #fff; border-bottom: 1px solid #e9ecef; padding: 0.75rem 1.5rem;
            display: flex; justify-content: space-between; align-items: center;
        }
        .content-area { padding: 1.75rem; }
        .card-box {
            background: #fff; border-radius: 0.5rem; padding: 1.25rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }
        @media (max-width: 768px) {
            .sidebar { width: 210px; }
            .main-content { margin-left: 210px; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <aside class="sidebar">
        <div class="brand">DN & Invoice App</div>

        <div class="nav-section">Menu</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span>🏠</span> Dashboard
        </a>

        <div class="nav-section">Master</div>
        <a href="{{ route('perusahaan.index') }}" class="nav-link {{ request()->routeIs('perusahaan.*') ? 'active' : '' }}">
            <span>🏢</span> Perusahaan
        </a>
        <a href="{{ route('customer.index') }}" class="nav-link {{ request()->routeIs('customer.*') ? 'active' : '' }}">
            <span>👤</span> Customer
        </a>
        <a href="{{ route('barang.index') }}" class="nav-link {{ request()->routeIs('barang.*') ? 'active' : '' }}">
            <span>📦</span> Barang
        </a>
        <a href="{{ route('rekening.index') }}" class="nav-link {{ request()->routeIs('rekening.*') ? 'active' : '' }}">
            <span>🏦</span> Rekening
        </a>

        <div class="nav-section">Transaksi</div>
        <a href="{{ route('delivery-note.index') }}" class="nav-link {{ request()->routeIs('delivery-note.*') ? 'active' : '' }}">
            <span>🚚</span> Delivery Note
        </a>
        <a href="{{ route('invoice.index') }}" class="nav-link {{ request()->routeIs('invoice.*') ? 'active' : '' }}">
            <span>🧾</span> Invoice
        </a>

        <div class="nav-section">Laporan</div>
        <a href="{{ route('laporan.delivery-note') }}" class="nav-link {{ request()->routeIs('laporan.delivery-note') ? 'active' : '' }}">
            <span>📊</span> Laporan Delivery Note
        </a>
        <a href="{{ route('laporan.invoice') }}" class="nav-link {{ request()->routeIs('laporan.invoice') ? 'active' : '' }}">
            <span>📈</span> Laporan Invoice
        </a>
    </aside>

    <div class="main-content">
        <div class="topbar">
            <h5 class="mb-0">@yield('title', 'Dashboard')</h5>
            <div class="d-flex align-items-center gap-3">
                <span class="text-dark">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-dark btn-sm">Logout</button>
                </form>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>