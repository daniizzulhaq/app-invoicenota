@extends('layouts.main')

@section('title', 'Delivery Note')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Delivery Note</h4>
        <a href="{{ route('delivery-note.create') }}" class="btn btn-primary btn-sm">+ Tambah Delivery Note</a>
    </div>

    <div class="card-box mb-3">
        <form method="GET" action="{{ route('delivery-note.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari No. Delivery Note / No. PO">
            </div>
            <div class="col-md-4">
                <select name="perusahaan_id" class="form-select">
                    <option value="">-- Semua Perusahaan --</option>
                    @foreach($perusahaans as $perusahaan)
                        <option value="{{ $perusahaan->id }}" {{ request('perusahaan_id') == $perusahaan->id ? 'selected' : '' }}>
                            {{ $perusahaan->nama_perusahaan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100">Cari</button>
            </div>
            @if(request('search') || request('perusahaan_id'))
                <div class="col-md-2">
                    <a href="{{ route('delivery-note.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>

    <div class="card-box">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>No. Delivery Note</th>
                    <th>No. PO</th>
                    <th>Tanggal</th>
                    <th>Perusahaan</th>
                    <th>Customer</th>
                    <th style="width: 220px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($deliveryNotes as $dn)
                    <tr>
                        <td>{{ $loop->iteration + ($deliveryNotes->currentPage() - 1) * $deliveryNotes->perPage() }}</td>
                        <td>{{ $dn->no_delivery_note }}</td>
                        <td>{{ $dn->no_po ?? '-' }}</td>
                        <td>{{ $dn->tanggal ? \Carbon\Carbon::parse($dn->tanggal)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $dn->perusahaan->nama_perusahaan ?? '-' }}</td>
                        <td>{{ $dn->customer->nama_customer ?? '-' }}</td>
                        <td>
                            <a href="{{ route('delivery-note.show', $dn) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('delivery-note.edit', $dn) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('delivery-note.cetak', $dn) }}" target="_blank" class="btn btn-sm btn-secondary">Cetak</a>
                            <form action="{{ route('delivery-note.destroy', $dn) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada data delivery note.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $deliveryNotes->links() }}
    </div>
@endsection