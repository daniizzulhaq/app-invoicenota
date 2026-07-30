@extends('layouts.main')

@section('title', 'Barang')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Barang</h4>
        <a href="{{ route('barang.create') }}" class="btn btn-primary btn-sm">+ Tambah Barang</a>
    </div>

    <div class="card-box">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>Nama Barang</th>
                    <th>Satuan</th>
                    <th>Harga Default</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangs as $barang)
                    <tr>
                        <td>{{ $loop->iteration + ($barangs->currentPage() - 1) * $barangs->perPage() }}</td>
                        <td>{{ $barang->nama_barang }}</td>
                        <td>{{ $barang->satuan ?? '-' }}</td>
                        <td>{{ $barang->harga_default ? number_format($barang->harga_default, 0, ',', '.') : '-' }}</td>
                        <td>
                            <a href="{{ route('barang.edit', $barang) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('barang.destroy', $barang) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Belum ada data barang.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($barangs->hasPages())
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-3 pagination-wrap">
            <div class="text-muted small mb-2 mb-md-0">
                Menampilkan {{ $barangs->firstItem() }}–{{ $barangs->lastItem() }} dari {{ $barangs->total() }} data
            </div>
            <div>
                {{ $barangs->onEachSide(1)->links() }}
            </div>
        </div>
    @endif

    <style>
        /* Reset & rapikan struktur nav bawaan Laravel (Tailwind default) */
        .pagination-wrap nav {
            display: flex;
            align-items: center;
        }
        .pagination-wrap nav > div:first-child {
            display: none; /* sembunyikan teks "Showing X to Y of Z results" bawaan, sudah kita ganti manual di atas */
        }
        .pagination-wrap nav > div:last-child,
        .pagination-wrap nav > div {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        /* Tombol Previous / Next & nomor halaman */
        .pagination-wrap nav a,
        .pagination-wrap nav span:not([aria-current]) {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            padding: 0 12px !important;
            border-radius: 999px !important;
            border: 1px solid #e2e5e9 !important;
            background-color: #fff !important;
            color: #495057 !important;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none !important;
            box-shadow: none !important;
            transition: all 0.15s ease;
        }
        .pagination-wrap nav a:hover {
            background-color: #f1f3f5 !important;
            border-color: #ced4da !important;
            color: #212529 !important;
        }

        /* Halaman disabled (Previous di halaman 1 / Next di halaman terakhir) */
        .pagination-wrap nav span:not([aria-current]) {
            color: #ced4da !important;
            background-color: #f8f9fa !important;
            border-color: #eef0f2 !important;
            cursor: not-allowed;
        }

        /* Nomor halaman aktif */
        .pagination-wrap nav span[aria-current="page"] {
            display: inline-flex !important;
        }
        .pagination-wrap nav span[aria-current="page"] span {
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            min-width: 38px;
            height: 38px;
            border-radius: 999px !important;
            background-color: #0d6efd !important;
            border: 1px solid #0d6efd !important;
            color: #fff !important;
            font-weight: 600;
            font-size: 14px;
        }

        /* Sembunyikan ikon SVG panah bawaan Tailwind, ganti dengan teks polos */
        .pagination-wrap nav a svg,
        .pagination-wrap nav span svg {
            display: none;
        }
    </style>
@endsection