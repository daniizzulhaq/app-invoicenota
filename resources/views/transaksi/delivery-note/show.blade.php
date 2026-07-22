@extends('layouts.main')

@section('title', 'Detail Delivery Note')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Delivery Note</h4>
        <div>
            <a href="{{ route('delivery-note.cetak', $deliveryNote) }}" target="_blank" class="btn btn-secondary btn-sm">Cetak PDF</a>
            <a href="{{ route('delivery-note.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <div class="card-box mb-3">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Perusahaan</div>
                <div class="fw-semibold">{{ $deliveryNote->perusahaan->nama_perusahaan ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Customer</div>
                <div class="fw-semibold">{{ $deliveryNote->customer->nama_customer ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">No. Delivery Note</div>
                <div class="fw-semibold">{{ $deliveryNote->no_delivery_note }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">No. PO</div>
                <div class="fw-semibold">{{ $deliveryNote->no_po ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal</div>
                <div class="fw-semibold">{{ \Carbon\Carbon::parse($deliveryNote->tanggal)->format('d-m-Y') }}</div>
            </div>
            @if($deliveryNote->catatan)
            <div class="col-12">
                <div class="text-muted small">Catatan</div>
                <div>{{ $deliveryNote->catatan }}</div>
            </div>
            @endif
        </div>
    </div>

    <div class="card-box mb-3">
        <h6 class="mb-3">Daftar Barang</h6>
        <table class="table table-bordered mb-0">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th>Qty</th>
                    <th>Satuan</th>
                    <th>Harga</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($deliveryNote->items as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->satuan ?? '-' }}</td>
                        <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($deliveryNote->invoice)
        <div class="alert alert-info">
            Delivery Note ini sudah memiliki Invoice: <strong>{{ $deliveryNote->invoice->no_invoice }}</strong>
        </div>
    @endif
@endsection