@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')

    <div class="mb-4">
        <h4 class="mb-0">Selamat datang, {{ auth()->user()->name }} 👋</h4>
        <small class="text-muted">Sistem Delivery Note & Invoice</small>
    </div>

    {{-- QUICK ACCESS MASTER --}}
    <div class="mb-4">
        <div class="section-title">Master</div>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('perusahaan.index') }}" class="quick-card">
                    <span class="quick-icon">🏢</span>
                    <div class="quick-title">Perusahaan</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('customer.index') }}" class="quick-card">
                    <span class="quick-icon">👤</span>
                    <div class="quick-title">Customer</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('barang.index') }}" class="quick-card">
                    <span class="quick-icon">📦</span>
                    <div class="quick-title">Barang</div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('rekening.index') }}" class="quick-card">
                    <span class="quick-icon">🏦</span>
                    <div class="quick-title">Rekening</div>
                </a>
            </div>
        </div>
    </div>

    {{-- QUICK ACCESS TRANSAKSI --}}
    <div class="mb-4">
        <div class="section-title">Transaksi</div>
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('delivery-note.index') }}" class="quick-card">
                    <span class="quick-icon">🚚</span>
                    <div>
                        <div class="quick-title">Delivery Note</div>
                        <div class="quick-desc">Kelola surat jalan pengiriman</div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('invoice.index') }}" class="quick-card">
                    <span class="quick-icon">🧾</span>
                    <div>
                        <div class="quick-title">Invoice</div>
                        <div class="quick-desc">Kelola tagihan invoice</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    {{-- QUICK ACCESS LAPORAN --}}
    <div class="mb-4">
        <div class="section-title">Laporan</div>
        <div class="row g-3">
            <div class="col-md-6">
                <a href="{{ route('laporan.delivery-note') }}" class="quick-card">
                    <span class="quick-icon">📊</span>
                    <div>
                        <div class="quick-title">Laporan Delivery Note</div>
                        <div class="quick-desc">Rekap surat jalan</div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('laporan.invoice') }}" class="quick-card">
                    <span class="quick-icon">📈</span>
                    <div>
                        <div class="quick-title">Laporan Invoice</div>
                        <div class="quick-desc">Rekap invoice</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

@endsection