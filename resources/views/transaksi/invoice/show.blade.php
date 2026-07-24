@extends('layouts.main')

@section('title', 'Detail Invoice')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Detail Invoice</h4>
        <div>
            <a href="{{ route('invoice.cetak', $invoice) }}" target="_blank" class="btn btn-secondary btn-sm">Cetak PDF</a>
            <a href="{{ route('invoice.index') }}" class="btn btn-outline-secondary btn-sm">Kembali</a>
        </div>
    </div>

    <div class="card-box mb-3">
        <div class="row g-3">
            <div class="col-md-6">
                <div class="text-muted small">Perusahaan</div>
                <div class="fw-semibold">{{ $invoice->perusahaan->nama_perusahaan ?? '-' }}</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Customer</div>
                <div class="fw-semibold">{{ $invoice->customer->nama_customer ?? '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">No. Invoice</div>
                <div class="fw-semibold">{{ $invoice->no_invoice }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">Tanggal Invoice</div>
                <div class="fw-semibold">{{ $invoice->tanggal_invoice ? \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d-m-Y') : '-' }}</div>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">No. PO</div>
                <div class="fw-semibold">{{ $invoice->no_po ?? '-' }}</div>
            </div>
            <div class="col-md-6">
               <div class="text-muted small">Delivery Note</div>
<div class="fw-semibold">
    @forelse($invoice->deliveryNotes as $dn)
        <span class="badge bg-secondary">{{ $dn->no_delivery_note }}</span>
    @empty
        -
    @endforelse
</div>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">Rekening</div>
                <div class="fw-semibold">{{ $invoice->rekening->nama_bank ?? '-' }} - {{ $invoice->rekening->no_rekening ?? '-' }} a.n {{ $invoice->rekening->atas_nama ?? '-' }}</div>
            </div>
            @if($invoice->catatan)
                <div class="col-md-12">
                    <div class="text-muted small">Catatan</div>
                    <div class="fw-semibold" style="white-space: pre-line;">{{ $invoice->catatan }}</div>
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
                @foreach($invoice->items as $item)
                    <tr>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->qty }}</td>
                        <td>{{ $item->satuan ?? '-' }}</td>
                        <td>{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                @if($invoice->catatan)
                    <tr>
                        <td colspan="5" style="white-space: pre-line;">{{ $invoice->catatan }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <div class="row justify-content-end mt-3">
            <div class="col-md-4">
                <div class="d-flex justify-content-between">
                    <span>Subtotal</span>
                    <span>{{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span>PPN ({{ $invoice->ppn_persen }}%)</span>
                    <span>{{ number_format($invoice->ppn_nominal, 0, ',', '.') }}</span>
                </div>
                <hr class="my-1">
                <div class="d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span>{{ number_format($invoice->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
@endsection