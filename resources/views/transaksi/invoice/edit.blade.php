@extends('layouts.main')

@section('title', 'Edit Invoice')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Invoice</h4>
        <a href="{{ route('invoice.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <form action="{{ route('invoice.update', $invoice) }}" method="POST" id="form-invoice">
        @csrf
        @method('PUT')

        <div class="card-box mb-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Perusahaan</label>
                    <input type="text" class="form-control" value="{{ $invoice->perusahaan->nama_perusahaan ?? '-' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Delivery Note</label>
                    <input type="text" class="form-control" value="{{ $invoice->deliveryNote->no_delivery_note ?? '-' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Customer</label>
                    <input type="text" class="form-control" value="{{ $invoice->customer->nama_customer ?? '-' }}" readonly>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alamat Customer</label>
                    <input type="text" class="form-control" value="{{ $invoice->customer->alamat ?? '-' }}" readonly>
                </div>

                <div class="col-md-4">
                    <label class="form-label">No. Invoice <span class="text-danger">*</span></label>
                    <input type="text" name="no_invoice" value="{{ old('no_invoice', $invoice->no_invoice) }}" class="form-control @error('no_invoice') is-invalid @enderror">
                    @error('no_invoice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Invoice <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_invoice" value="{{ old('tanggal_invoice', \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('Y-m-d')) }}" class="form-control @error('tanggal_invoice') is-invalid @enderror">
                    @error('tanggal_invoice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">No. PO</label>
                    <input type="text" name="no_po" value="{{ old('no_po', $invoice->no_po) }}" class="form-control @error('no_po') is-invalid @enderror">
                    @error('no_po')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rekening <span class="text-danger">*</span></label>
                    <select name="rekening_id" class="form-select @error('rekening_id') is-invalid @enderror">
                        <option value="">-- Pilih Rekening --</option>
                        @foreach($invoice->perusahaan->rekenings as $rekening)
                            <option value="{{ $rekening->id }}" {{ old('rekening_id', $invoice->rekening_id) == $rekening->id ? 'selected' : '' }}>
                                {{ $rekening->nama_bank }} - {{ $rekening->no_rekening }} a.n {{ $rekening->atas_nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('rekening_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">PPN (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="ppn_persen" id="input-ppn" value="{{ old('ppn_persen', $invoice->ppn_persen) }}" class="form-control @error('ppn_persen') is-invalid @enderror">
                    @error('ppn_persen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card-box mb-3">
            <h6 class="mb-3">Daftar Barang</h6>

            @error('items')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror

            <table class="table table-bordered align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 35%">Barang</th>
                        <th style="width: 15%">Qty</th>
                        <th style="width: 15%">Satuan</th>
                        <th style="width: 17%">Harga</th>
                        <th style="width: 18%">Total</th>
                    </tr>
                </thead>
                <tbody id="tbody-barang">
                    @foreach($invoice->items as $index => $item)
                        <tr>
                            <td>
                                <input type="hidden" name="items[{{ $index }}][barang_id]" value="{{ $item->barang_id }}">
                                <input type="text" name="items[{{ $index }}][nama_barang]" value="{{ $item->nama_barang }}" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0.01" name="items[{{ $index }}][qty]" value="{{ $item->qty }}" class="form-control form-control-sm input-qty">
                            </td>
                            <td>
                                <input type="text" name="items[{{ $index }}][satuan]" value="{{ $item->satuan }}" class="form-control form-control-sm">
                            </td>
                            <td>
                                <input type="number" step="0.01" min="0" name="items[{{ $index }}][harga]" value="{{ $item->harga }}" class="form-control form-control-sm input-harga">
                            </td>
                            <td class="text-end input-total">{{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="row justify-content-end mt-3">
                <div class="col-md-4">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span id="display-subtotal">{{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>PPN (<span id="display-ppn-persen">{{ $invoice->ppn_persen }}</span>%)</span>
                        <span id="display-ppn-nominal">{{ number_format($invoice->ppn_nominal, 0, ',', '.') }}</span>
                    </div>
                    <hr class="my-1">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span id="display-total">{{ number_format($invoice->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">Update</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const tbodyBarang = document.getElementById('tbody-barang');
    const inputPpn = document.getElementById('input-ppn');

    function formatRupiah(angka) {
        angka = parseFloat(angka) || 0;
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function hitungUlang() {
        let subtotal = 0;
        tbodyBarang.querySelectorAll('tr').forEach(row => {
            const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
            const harga = parseFloat(row.querySelector('.input-harga').value) || 0;
            const total = qty * harga;
            row.querySelector('.input-total').textContent = formatRupiah(total);
            subtotal += total;
        });

        const ppnPersen = parseFloat(inputPpn.value) || 0;
        const ppnNominal = subtotal * ppnPersen / 100;
        const total = subtotal + ppnNominal;

        document.getElementById('display-subtotal').textContent = formatRupiah(subtotal);
        document.getElementById('display-ppn-persen').textContent = ppnPersen;
        document.getElementById('display-ppn-nominal').textContent = formatRupiah(ppnNominal);
        document.getElementById('display-total').textContent = formatRupiah(total);
    }

    tbodyBarang.addEventListener('input', function (e) {
        if (e.target.classList.contains('input-qty') || e.target.classList.contains('input-harga')) {
            hitungUlang();
        }
    });

    inputPpn.addEventListener('input', hitungUlang);
</script>
@endpush