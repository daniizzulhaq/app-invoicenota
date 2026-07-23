@extends('layouts.main')

@section('title', 'Tambah Invoice')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Tambah Invoice</h4>
        <a href="{{ route('invoice.index') }}" class="btn btn-secondary btn-sm">Kembali</a>
    </div>

    <form action="{{ route('invoice.store') }}" method="POST" id="form-invoice">
        @csrf

        <div class="card-box mb-3">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Perusahaan <span class="text-danger">*</span></label>
                    <select name="perusahaan_id" id="select-perusahaan" class="form-select @error('perusahaan_id') is-invalid @enderror">
                        <option value="">-- Pilih Perusahaan --</option>
                        @foreach($perusahaans as $perusahaan)
                            <option value="{{ $perusahaan->id }}" {{ old('perusahaan_id') == $perusahaan->id ? 'selected' : '' }}>
                                {{ $perusahaan->nama_perusahaan }}
                            </option>
                        @endforeach
                    </select>
                    @error('perusahaan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Delivery Note <span class="text-danger">*</span></label>
                    <select name="delivery_note_id" id="select-dn" class="form-select @error('delivery_note_id') is-invalid @enderror" disabled>
                        <option value="">-- Pilih Perusahaan Dahulu --</option>
                    </select>
                    @error('delivery_note_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Customer</label>
                    <input type="text" id="display-customer" class="form-control" readonly placeholder="Otomatis dari Delivery Note">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Alamat Customer</label>
                    <input type="text" id="display-alamat" class="form-control" readonly placeholder="Otomatis dari Delivery Note">
                </div>

                <div class="col-md-4">
                    <label class="form-label">No. Invoice <span class="text-danger">*</span></label>
                    <input type="text" name="no_invoice" value="{{ old('no_invoice') }}" class="form-control @error('no_invoice') is-invalid @enderror">
                    @error('no_invoice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">Tanggal Invoice <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_invoice" value="{{ old('tanggal_invoice', date('Y-m-d')) }}" class="form-control @error('tanggal_invoice') is-invalid @enderror">
                    @error('tanggal_invoice')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">No. PO</label>
                    <input type="text" name="no_po" id="input-no-po" value="{{ old('no_po') }}" class="form-control @error('no_po') is-invalid @enderror">
                    @error('no_po')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rekening <span class="text-danger">*</span></label>
                    <select name="rekening_id" id="select-rekening" class="form-select @error('rekening_id') is-invalid @enderror">
                        <option value="">-- Pilih Perusahaan Dahulu --</option>
                    </select>
                    @error('rekening_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">PPN (%)</label>
                    <input type="number" step="0.01" min="0" max="100" name="ppn_persen" id="input-ppn" value="{{ old('ppn_persen', 11) }}" class="form-control @error('ppn_persen') is-invalid @enderror">
                    @error('ppn_persen')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-12">
                    <label class="form-label">Catatan</label>
                    <textarea name="catatan" id="input-catatan" class="form-control @error('catatan') is-invalid @enderror" rows="2" placeholder="Contoh: UNIT : AM EX 97">{{ old('catatan') }}</textarea>
                    <div class="form-text">Catatan ini akan tampil di dalam tabel barang saat dicetak PDF.</div>
                    @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="card-box mb-3">
            <h6 class="mb-3">Daftar Barang <small class="text-muted">(dari Delivery Note, harga bisa diubah manual)</small></h6>

            @error('items')
                <div class="alert alert-danger py-2">{{ $message }}</div>
            @enderror

            <table class="table table-bordered align-middle mb-0" id="table-barang">
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
                    <tr>
                        <td colspan="5" class="text-center text-muted">Pilih Delivery Note untuk menampilkan barang.</td>
                    </tr>
                </tbody>
            </table>

            <div class="row justify-content-end mt-3">
                <div class="col-md-4">
                    <div class="d-flex justify-content-between">
                        <span>Subtotal</span>
                        <span id="display-subtotal">0</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>PPN (<span id="display-ppn-persen">11</span>%)</span>
                        <span id="display-ppn-nominal">0</span>
                    </div>
                    <hr class="my-1">
                    <div class="d-flex justify-content-between fw-bold">
                        <span>Total</span>
                        <span id="display-total">0</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-primary px-4" id="btn-simpan" disabled>Simpan</button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    const selectPerusahaan = document.getElementById('select-perusahaan');
    const selectDn = document.getElementById('select-dn');
    const selectRekening = document.getElementById('select-rekening');
    const displayCustomer = document.getElementById('display-customer');
    const displayAlamat = document.getElementById('display-alamat');
    const inputNoPo = document.getElementById('input-no-po');
    const inputPpn = document.getElementById('input-ppn');
    const tbodyBarang = document.getElementById('tbody-barang');
    const btnSimpan = document.getElementById('btn-simpan');

    function formatRupiah(angka) {
        angka = parseFloat(angka) || 0;
        return new Intl.NumberFormat('id-ID').format(angka);
    }

    function unformat(str) {
        return parseFloat((str || '0').toString().replace(/\./g, '').replace(',', '.')) || 0;
    }

    let itemCount = 0;

    function hitungTotal() {
        let subtotal = 0;
        tbodyBarang.querySelectorAll('tr.row-barang').forEach(row => {
            const qty = parseFloat(row.querySelector('.input-qty').value) || 0;
            const harga = unformat(row.querySelector('.input-harga').value);
            const total = qty * harga;
            row.querySelector('.cell-total').textContent = formatRupiah(total);
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

    function renderItems(items) {
        tbodyBarang.innerHTML = '';
        itemCount = 0;

        if (!items || items.length === 0) {
            tbodyBarang.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Delivery Note ini tidak memiliki barang.</td></tr>';
            return;
        }

        items.forEach((item) => {
            const index = itemCount;
            const tr = document.createElement('tr');
            tr.className = 'row-barang';
            const harga = item.harga ?? 0;
            tr.innerHTML = `
                <td>
                    <input type="hidden" name="items[${index}][barang_id]" value="${item.barang_id ?? ''}">
                    <input type="text" name="items[${index}][nama_barang]" value="${item.nama_barang ?? ''}" class="form-control form-control-sm">
                </td>
                <td>
                    <input type="number" step="0.01" min="0.01" name="items[${index}][qty]" value="${item.qty ?? 1}" class="form-control form-control-sm input-qty">
                </td>
                <td>
                    <input type="text" name="items[${index}][satuan]" value="${item.satuan ?? ''}" class="form-control form-control-sm">
                </td>
                <td>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">Rp</span>
                        <input type="text" name="items[${index}][harga]" value="${harga ? formatRupiah(harga) : ''}" class="form-control form-control-sm input-harga" inputmode="numeric" placeholder="0">
                    </div>
                </td>
                <td class="text-end cell-total">${formatRupiah((item.qty ?? 1) * harga)}</td>
            `;
            tbodyBarang.appendChild(tr);
            itemCount++;
        });
    }

    tbodyBarang.addEventListener('input', function (e) {
        if (e.target.classList.contains('input-harga')) {
            const cursorAtEnd = e.target.selectionStart === e.target.value.length;
            const raw = unformat(e.target.value);
            e.target.value = raw ? formatRupiah(raw) : '';
            if (cursorAtEnd) {
                e.target.setSelectionRange(e.target.value.length, e.target.value.length);
            }
        }
        if (e.target.classList.contains('input-qty') || e.target.classList.contains('input-harga')) {
            hitungTotal();
        }
    });

    selectPerusahaan.addEventListener('change', function () {
        const perusahaanId = this.value;

        selectDn.innerHTML = '<option value="">-- Memuat... --</option>';
        selectDn.disabled = true;
        selectRekening.innerHTML = '<option value="">-- Pilih Perusahaan Dahulu --</option>';
        displayCustomer.value = '';
        displayAlamat.value = '';
        renderItems([]);
        hitungTotal();
        btnSimpan.disabled = true;

        if (!perusahaanId) {
            selectDn.innerHTML = '<option value="">-- Pilih Perusahaan Dahulu --</option>';
            return;
        }

        fetch(`/delivery-note-by-perusahaan/${perusahaanId}`)
            .then(res => res.json())
            .then(data => {
                selectDn.innerHTML = '<option value="">-- Pilih Delivery Note --</option>';
                data.forEach(dn => {
                    const opt = document.createElement('option');
                    opt.value = dn.id;
                    opt.textContent = `${dn.no_delivery_note} (${dn.tanggal ?? '-'})`;
                    selectDn.appendChild(opt);
                });
                selectDn.disabled = false;
            });

        fetch(`/rekening-by-perusahaan/${perusahaanId}`)
            .then(res => res.ok ? res.json() : [])
            .then(data => {
                if (Array.isArray(data) && data.length > 0) {
                    selectRekening.innerHTML = '<option value="">-- Pilih Rekening --</option>';
                    data.forEach(rek => {
                        const opt = document.createElement('option');
                        opt.value = rek.id;
                        opt.textContent = `${rek.nama_bank} - ${rek.no_rekening} a.n ${rek.atas_nama}`;
                        selectRekening.appendChild(opt);
                    });
                } else {
                    selectRekening.innerHTML = '<option value="">-- Tidak ada rekening --</option>';
                }
            })
            .catch(() => {
                selectRekening.innerHTML = '<option value="">-- Tidak ada rekening --</option>';
            });
    });

    selectDn.addEventListener('change', function () {
        const dnId = this.value;

        if (!dnId) {
            displayCustomer.value = '';
            displayAlamat.value = '';
            renderItems([]);
            hitungTotal();
            btnSimpan.disabled = true;
            return;
        }

        fetch(`/delivery-note-detail/${dnId}`)
            .then(res => res.json())
            .then(data => {
                displayCustomer.value = data.customer?.nama_customer ?? '';
                displayAlamat.value = data.customer?.alamat ?? '';
                inputNoPo.value = inputNoPo.value || data.no_po || '';

                const items = (data.items || []).map(item => ({
                    barang_id: item.barang_id,
                    nama_barang: item.nama_barang,
                    qty: item.qty,
                    satuan: item.satuan,
                    harga: item.harga,
                }));

                renderItems(items);
                hitungTotal();
                btnSimpan.disabled = items.length === 0;
            });
    });

    inputPpn.addEventListener('input', hitungTotal);

    document.getElementById('form-invoice').addEventListener('submit', function (e) {
        if (tbodyBarang.querySelectorAll('tr.row-barang').length === 0) {
            e.preventDefault();
            alert('Pilih Delivery Note yang memiliki barang terlebih dahulu.');
            return;
        }
        document.querySelectorAll('.input-harga').forEach(el => {
            el.value = unformat(el.value);
        });
    });
</script>
@endpush