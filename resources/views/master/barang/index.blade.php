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

    <div class="mt-3">
        {{ $barangs->links() }}
    </div>
@endsection