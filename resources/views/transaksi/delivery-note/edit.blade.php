@extends('layouts.main')

@section('title', 'Edit Delivery Note')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Edit Delivery Note</h4>
        <a href="{{ route('delivery-note.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <form action="{{ route('delivery-note.update', $deliveryNote) }}" method="POST" id="form-dn">
        @csrf
        @method('PUT')

        <div class="card-box mb-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Perusahaan <span class="text-danger">*</span></label>
                    <select name="perusahaan_id" class="form-select @error('perusahaan_id') is-invalid @enderror">
                        <option value="">-- Pilih Perusahaan --</option>
                        @foreach($perusahaans as $perusahaan)
                            <option value="{{ $perusahaan->id }}" {{ old('perusahaan_id', $deliveryNote->perusahaan_id) == $perusahaan->id ? 'selected' : '' }}>
                                {{ $perusahaan->nama_perusahaan }}
                            </option>
                        @endforeach
                    </select>
                    @error('perusahaan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Customer <span class="text-danger">*</span></label>
                    <select name="customer_id" class="form-select @error('customer_id') is-invalid @enderror">
                        <option value="">-- Pilih Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id', $deliveryNote->customer_id) == $customer->id ? 'selected' : '' }}>
                                {{ $customer->nama_customer }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">No. PO</label>
                    <input type="text" name="no_po" value="{{ old('no_po', $deliveryNote->no_po) }}" class="form-control @error('no_po') is-invalid @enderror">
                    @error('no_po')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">No. Delivery Note <span class="text-danger">*</span></label>
                    <input type="text" name="no_delivery_note" value="{{ old('no_delivery_note', $deliveryNote->no_delivery_note) }}" class="form-control @error('no_delivery_note') is-invalid @enderror">
                    @error('no_delivery_note')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" value="{{ old('tanggal', $deliveryNote->tanggal ? \Carbon\Carbon::parse($deliveryNote->tanggal)->format('Y-m-d') : '') }}" class="form-control @error('tanggal') is-invalid @enderror">
                    @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label d-block">PPN</label>
                    <div class="form-check form-switch mt-1">
                        <input type="checkbox" name="pakai_ppn" value="1" class="form-check-input" id="pakai_ppn"
                            {{ old('pakai_ppn', $deliveryNote->pakai_ppn) ? 'checked' : '' }}>
                        <label class="form-check-label" for="pakai_ppn">Gunakan PPN</label>
                    </div>
                </div>

                <div class="col-md-4" id="wrap-ppn-persen" style="{{ old('pakai_ppn', $deliveryNote->pakai_ppn) ? '' : 'display:none;' }}">
                    <label class="form-label">PPN (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="ppn_persen"
                        value="{{ old('ppn_persen', $deliveryNote->ppn_persen ?? 11) }}" class="form-control @error('ppn_persen') is-invalid @enderror">
                    @error('ppn_persen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" rows="2" class="form-control @error('catatan') is-invalid @enderror">{{ old('catatan', $deliveryNote->catatan) }}</textarea>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card-box mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">Daftar Barang</h6>
                <button type="button" class="btn btn-sm btn-primary" id="btn-tambah-barang">+ Tambah Barang</button>
            </div>
            <p class="text-muted small mb-2">Qty, satuan, dan harga boleh dikosongkan di sini — bisa dilengkapi nanti saat membuat invoice.</p>

            @error('items')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror

            <table class="table table-bordered align-middle mb-0" id="table-barang">
                <thead>
                    <tr>
                        <th style="width: 30%">Barang</th>
                        <th style="width: 15%">Qty</th>
                        <th style="width: 15%">Satuan</th>
                        <th style="width: 20%">Harga</th>
                        <th style="width: 15%">Total</th>
                        <th style="width: 5%"></th>
                    </tr>
                </thead>
                <tbody id="tbody-barang">
                    {{-- diisi lewat JS dari data existing --}}
                </tbody>
            </table>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4">Update</button>
        </div>
    </form>

    <template id="row-template">
        <tr class="row-barang">
            <td>
                <select name="items[__INDEX__][barang_id]" class="form-select form-select-sm select-barang">
                    <option value="">-- Pilih dari Master (opsional) --</option>
                    @foreach($barangs as $barang)
                        <option value="{{ $barang->id }}"
                            data-nama="{{ $barang->nama_barang }}"
                            data-satuan="{{ $barang->satuan }}"
                            data-harga="{{ $barang->harga_default }}">
                            {{ $barang->nama_barang }}
                        </option>
                    @endforeach
                </select>
                <input type="text" name="items[__INDEX__][nama_barang]" class="form-control form-control-sm mt-1 input-nama-barang" placeholder="Atau ketik nama barang manual" required>
            </td>
            <td>
                <input type="number" step="0.01" min="0" name="items[__INDEX__][qty]" class="form-control form-control-sm input-qty" placeholder="opsional">
            </td>
            <td>
                <input type="text" name="items[__INDEX__][satuan]" class="form-control form-control-sm input-satuan" placeholder="pcs, unit, dll">
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <span class="input-group-text">Rp</span>
                    <input type="text" name="items[__INDEX__][harga]" class="form-control form-control-sm input-harga" inputmode="numeric" placeholder="opsional">
                </div>
            </td>
            <td>
                <input type="text" class="form-control form-control-sm input-total" readonly>
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger btn-hapus-baris">&times;</button>
            </td>
        </tr>
    </template>
@endsection

@push('scripts')
@php
    $existingItemsForJs = $deliveryNote->items->map(function ($i) {
        return [
            'barang_id' => $i->barang_id,
            'nama_barang' => $i->nama_barang,
            'qty' => $i->qty,
            'satuan' => $i->satuan,
            'harga' => $i->harga,
        ];
    });
@endphp
<script>
    let rowIndex = 0;
    const tbody = document.getElementById('tbody-barang');
    const template = document.getElementById('row-template');

    const existingItems = @json($existingItemsForJs);

    function formatRupiah(angka) {
        if (isNaN(angka)) return '';
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function unformat(str) {
        return parseFloat((str || '0').toString().replace(/\./g, '').replace(',', '.')) || 0;
    }

    function tambahBaris(data = null) {
        const clone = template.content.cloneNode(true);
        clone.querySelectorAll('[name]').forEach(el => {
            el.name = el.name.replace('__INDEX__', rowIndex);
        });

        if (data) {
            const row = clone.querySelector('.row-barang');
            if (data.barang_id) row.querySelector('.select-barang').value = data.barang_id;
            row.querySelector('.input-nama-barang').value = data.nama_barang ?? '';
            row.querySelector('.input-qty').value = data.qty ?? '';
            row.querySelector('.input-satuan').value = data.satuan ?? '';
            row.querySelector('.input-harga').value = data.harga ? formatRupiah(data.harga) : '';
            row.querySelector('.input-total').value = formatRupiah((data.qty || 0) * (data.harga || 0));
        }

        tbody.appendChild(clone);
        rowIndex++;
    }

    document.getElementById('btn-tambah-barang').addEventListener('click', () => tambahBaris());

    tbody.addEventListener('change', function (e) {
        const row = e.target.closest('tr');
        if (!row) return;

        if (e.target.classList.contains('select-barang')) {
            const opt = e.target.selectedOptions[0];
            const namaInput = row.querySelector('.input-nama-barang');
            const satuanInput = row.querySelector('.input-satuan');
            const hargaInput = row.querySelector('.input-harga');

            if (opt && opt.value) {
                namaInput.value = opt.dataset.nama || '';
                satuanInput.value = opt.dataset.satuan || '';
                hargaInput.value = opt.dataset.harga ? formatRupiah(opt.dataset.harga) : '';
            }
            hitungTotal(row);
        }

        if (e.target.classList.contains('input-qty')) {
            hitungTotal(row);
        }
    });

    tbody.addEventListener('input', function (e) {
        if (e.target.classList.contains('input-harga')) {
            const cursorAtEnd = e.target.selectionStart === e.target.value.length;
            const raw = unformat(e.target.value);
            e.target.value = raw ? formatRupiah(raw) : '';
            if (cursorAtEnd) {
                e.target.setSelectionRange(e.target.value.length, e.target.value.length);
            }
        }
        if (e.target.classList.contains('input-qty') || e.target.classList.contains('input-harga')) {
            hitungTotal(e.target.closest('tr'));
        }
    });

    tbody.addEventListener('click', function (e) {
        if (e.target.classList.contains('btn-hapus-baris')) {
            e.target.closest('tr').remove();
        }
    });

    function hitungTotal(row) {
        const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
        const harga = unformat(row.querySelector('.input-harga').value);
        row.querySelector('.input-total').value = formatRupiah(qty * harga);
    }

    if (existingItems.length > 0) {
        existingItems.forEach(item => tambahBaris(item));
    } else {
        tambahBaris();
    }

    document.getElementById('form-dn').addEventListener('submit', function (e) {
        if (tbody.querySelectorAll('.row-barang').length === 0) {
            e.preventDefault();
            alert('Tambahkan minimal 1 barang.');
            return;
        }
        document.querySelectorAll('.input-harga').forEach(el => {
            el.value = unformat(el.value);
        });
    });

    // toggle PPN
    const pakaiPpnCheckbox = document.getElementById('pakai_ppn');
    const wrapPpnPersen = document.getElementById('wrap-ppn-persen');
    pakaiPpnCheckbox.addEventListener('change', function () {
        wrapPpnPersen.style.display = this.checked ? '' : 'none';
    });
</script>
@endpush