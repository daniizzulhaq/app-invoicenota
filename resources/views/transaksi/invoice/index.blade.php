@extends('layouts.main')

@section('title', 'Invoice')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Data Invoice</h4>
        <a href="{{ route('invoice.create') }}" class="btn btn-primary btn-sm">+ Tambah Invoice</a>
    </div>

    <div class="card-box mb-3">
        <form method="GET" action="{{ route('invoice.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Cari No. Invoice">
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
                    <a href="{{ route('invoice.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                </div>
            @endif
        </form>
    </div>

    <div class="card-box">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th style="width: 50px">#</th>
                    <th>No. Invoice</th>
                    <th>No. PO</th>
                    <th>Tanggal</th>
                    <th>Perusahaan</th>
                    <th>Customer</th>
                    <th>No. Delivery Note</th>
                    <th>Total</th>
                    <th style="width: 220px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td>{{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}</td>
                        <td>{{ $invoice->no_invoice }}</td>
                        <td>{{ $invoice->no_po ?? '-' }}</td>
                        <td>{{ $invoice->tanggal_invoice ? \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d-m-Y') : '-' }}</td>
                        <td>{{ $invoice->perusahaan->nama_perusahaan ?? '-' }}</td>
                        <td>{{ $invoice->customer->nama_customer ?? '-' }}</td>
                        <td>{{ $invoice->deliveryNote->no_delivery_note ?? '-' }}</td>
                        <td>{{ number_format($invoice->total, 0, ',', '.') }}</td>
                        <td>
                            <a href="{{ route('invoice.show', $invoice) }}" class="btn btn-sm btn-info">Detail</a>
                            <a href="{{ route('invoice.edit', $invoice) }}" class="btn btn-sm btn-warning">Edit</a>
                            <a href="{{ route('invoice.cetak', $invoice) }}" target="_blank" class="btn btn-sm btn-secondary">Cetak</a>
                            <form action="{{ route('invoice.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada data invoice.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $invoices->links() }}
    </div>
@endsection