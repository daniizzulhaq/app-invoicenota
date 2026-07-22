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
                <a href="{{ route('delivery-note.index') }}" class="quick-card quick-card-wide">
                    <span class="quick-icon">🚚</span>
                    <div>
                        <div class="quick-title">Delivery Note</div>
                        <div class="quick-desc">Kelola surat jalan pengiriman</div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('invoice.index') }}" class="quick-card quick-card-wide">
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
                <a href="{{ route('laporan.delivery-note') }}" class="quick-card quick-card-wide">
                    <span class="quick-icon">📊</span>
                    <div>
                        <div class="quick-title">Laporan Delivery Note</div>
                        <div class="quick-desc">Rekap surat jalan</div>
                    </div>
                </a>
            </div>
            <div class="col-md-6">
                <a href="{{ route('laporan.invoice') }}" class="quick-card quick-card-wide">
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

@push('styles')
<style>
    .section-title {
        font-size: 13px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: #94a3b8;
        margin-bottom: 14px;
        padding-left: 2px;
    }

    .quick-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: #ffffff;
        border: 1px solid #eef0f3;
        border-radius: 16px;
        padding: 18px;
        text-decoration: none;
        color: #1e293b;
        height: 100%;
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.03);
        transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
    }

    .quick-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -8px rgba(79, 70, 229, 0.18);
        border-color: #c7d2fe;
        color: #1e293b;
        text-decoration: none;
    }

    .quick-card:active {
        transform: translateY(-1px);
    }

    .quick-icon {
        flex-shrink: 0;
        width: 46px;
        height: 46px;
        border-radius: 12px;
        background: linear-gradient(135deg, #eef2ff, #f5f3ff);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    /* Kartu master (icon + judul saja, vertikal ditengah) */
    .col-6.col-md-3 .quick-card {
        flex-direction: column;
        text-align: center;
        gap: 10px;
        padding: 20px 12px;
    }

    .col-6.col-md-3 .quick-icon {
        width: 52px;
        height: 52px;
        font-size: 24px;
    }

    .quick-title {
        font-size: 14.5px;
        font-weight: 600;
        color: #1e293b;
        line-height: 1.3;
    }

    .quick-desc {
        font-size: 12.5px;
        color: #94a3b8;
        margin-top: 2px;
        line-height: 1.4;
    }

    /* Kartu wide (transaksi & laporan) beri aksen warna beda per hover */
    .quick-card-wide {
        position: relative;
        overflow: hidden;
    }

    .quick-card-wide::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #6366f1, #8b5cf6);
        opacity: 0;
        transition: opacity 0.15s ease;
    }

    .quick-card-wide:hover::before {
        opacity: 1;
    }
</style>
@endpush