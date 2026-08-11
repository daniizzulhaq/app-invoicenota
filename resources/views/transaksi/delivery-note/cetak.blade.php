<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Delivery Note - {{ $deliveryNote->no_delivery_note }}</title>
    <style>
    /* Kertas Continuous Form K3W - Portrait */
    @page { size: 9.5in 11in; margin: 3mm 6mm 3mm 2mm; }
    * { box-sizing: border-box; }
    html, body {
        margin: 0;
        padding: 0;
        background: #e9ecef;
        font-family: 'Calibri', 'Carlito', Arial, sans-serif;
    }
    .page {
        width: 9.5in;
        min-height: 11in;
        margin: 5mm auto;
        background: #fff;
        padding: 3mm 6mm 6mm 6mm;
        font-size: 16px;
        color: #000;
        box-shadow: 0 0 6px rgba(0,0,0,0.15);
        page-break-after: always;
    }
    .page:last-child { page-break-after: auto; }

    .toolbar {
        width: 9.5in;
        margin: 5mm auto 0 auto;
        text-align: center;
    }
    .toolbar button {
        padding: 8px 22px;
        font-size: 14px;
        font-family: inherit;
        cursor: pointer;
        border: 1px solid #444;
        background: #2d6cdf;
        color: #fff;
        border-radius: 4px;
    }
    .toolbar button:hover { background: #1e56b8; }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 2px solid #000;
        padding-bottom: 8px;
        margin-bottom: 10px;
    }
    .header .perusahaan-info {
        flex: 1 1 auto;
        max-width: 68%;
        padding-right: 20px;
    }
    .company-name {
        font-size: 29px;
        font-weight: bold;
        letter-spacing: 0.3px;
        margin: 0 0 4px 0;
    }
    .company-sub {
        font-size: 16px;
        font-weight: bold;
        margin: 0 0 3px 0;
    }
    .company-detail {
        font-size: 16px;
        line-height: 1.5;
        margin: 0;
    }
    .kepada {
        flex: 0 0 auto;
        text-align: right;
        font-size: 17px;
        padding-top: 3px;
        white-space: nowrap;
    }
    .kepada .nama-customer { font-weight: bold; font-size: 18px; margin-top: 4px; }

    .title-box {
        text-align: center;
        margin: 8px 0 11px 0;
    }
    .title-box h2 {
        margin: 0;
        font-size: 22px;
        text-decoration: underline;
        letter-spacing: 1px;
    }
    .title-box .no-dn {
        font-size: 16px;
        margin-top: 3px;
    }
    .title-box .page-indicator {
        font-size: 15px;
        margin-top: 3px;
        font-style: italic;
    }

    .info-po {
        text-align: right;
        font-size: 16px;
        margin-bottom: 6px;
    }

    table.items {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }
    table.items th, table.items td {
        border: 1px solid #000;
        padding: 5px 7px;
    }
    table.items th {
        background: #f0f0f0;
        font-size: 15px;
        text-align: center;
        white-space: normal;
        overflow: visible;
        padding: 4px 6px;
    }
    table.items td { font-size: 16px; vertical-align: middle; }
    table.items td.no { text-align: center; }
    table.items td.qty { text-align: center; }
    table.items td.unit { text-align: center; }
    table.items td.price,
    table.items td.total { text-align: right; white-space: nowrap; }

    table.items tbody td {
        height: 15px;
        padding: 3px 7px;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
        border-top: none;
        border-bottom: none;
    }
    table.items tbody td.no {
        border-top: none;
        border-bottom: none;
    }
    table.items tbody tr:first-child td {
        border-top: 1px solid #000;
    }
    table.items tbody tr:last-child td {
        border-bottom: 1px solid #000;
    }

    .summary-row td {
        font-weight: normal;
        text-align: right;
        border-top: none;
        border-bottom: none;
        border-left: 1px solid #000;
        border-right: 1px solid #000;
    }
    .summary-row td.total-label {
        text-align: center;
        font-size: 15px;
    }
    .summary-row:first-of-type td {
        border-top: none;
    }
    .summary-row.grand-total td {
        font-weight: bold;
        border-top: 1px solid #000 !important;
        border-bottom: 1px solid #000 !important;
    }
    .summary-row:last-child td {
        border-bottom: 1px solid #000;
    }

    .catatan {
        font-size: 15px;
        margin-top: 6px;
        font-style: italic;
    }
    .ket-terima {
        font-size: 16px;
        margin-top: 10px;
    }

    .footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-top: 10px;
    }
    .footer .box {
        width: 45%;
        font-size: 15px;
        text-align: center;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .footer .box .box-heading {
        min-height: 16px;
    }
    .footer .box .ttd-space { height: 85px; width: 100%; }
    .footer .box .nama-line {
        border-top: 1px solid #000;
        margin-top: 4px;
        padding-top: 4px;
        display: inline-block;
        min-width: 180px;
    }

    @media print {
        body { background: #fff; }
        .page { margin: 0; box-shadow: none; }
        .toolbar { display: none !important; }
    }
</style>
</head>
<body>

<div class="toolbar">
    <button onclick="window.print()">🖨️ Print / Save PDF</button>
</div>

@php
    $allItems = $deliveryNote->items->values();
    $totalItems = $allItems->count();

    $mainPerPage = 20;   // maksimal baris per halaman

    // Bagi item ke halaman-halaman berisi maksimal 20 baris.
    // Tidak ada lagi baris kosong pengisi — setiap halaman panjangnya
    // mengikuti jumlah item aktual di halaman itu.
    $pageSizes = [];
    $remaining = $totalItems;

    if ($remaining === 0) {
        $pageSizes = [0];
    } else {
        while ($remaining > 0) {
            $take = min($mainPerPage, $remaining);
            $pageSizes[] = $take;
            $remaining -= $take;
        }
    }

    $totalPages = count($pageSizes);
    $subtotal = $allItems->sum('total');
    $pakaiPpn = (bool) $deliveryNote->pakai_ppn;
    $ppnPersen = $deliveryNote->ppn_persen ?? 0;
    $ppnNominal = $pakaiPpn ? ($subtotal * $ppnPersen / 100) : 0;
    $grandTotal = $subtotal + $ppnNominal;

    $runningIndex = 0;
@endphp

@foreach($pageSizes as $pageIndex => $sizeThisPage)
    @php
        $startIndex = $runningIndex;
        $pageItems = $allItems->slice($startIndex, $sizeThisPage)->values();
        $isLastPage = ($pageIndex === $totalPages - 1);
    @endphp

    <div class="page">

        <div class="header">
            <div class="perusahaan-info">
                <p class="company-name">{{ strtoupper($deliveryNote->perusahaan->nama_perusahaan ?? '-') }}</p>
                @if($deliveryNote->perusahaan->deskripsi)
                    <p class="company-sub">{{ $deliveryNote->perusahaan->deskripsi }}</p>
                @endif
                <p class="company-detail">
                    {{ $deliveryNote->perusahaan->alamat ?? '' }}<br>
                    @if($deliveryNote->perusahaan->telp)
                        Telp. {{ $deliveryNote->perusahaan->telp }}<br>
                    @endif
                    @if($deliveryNote->perusahaan->email)
                        Email: {{ $deliveryNote->perusahaan->email }}
                    @endif
                </p>
            </div>

            <div class="kepada">
                <div>Kepada Yth.</div>
                <div class="nama-customer">{{ strtoupper($deliveryNote->customer->nama_customer ?? '-') }}</div>
            </div>
        </div>

        <div class="title-box">
            <h2>DELIVERY NOTE</h2>
            <div class="no-dn">{{ $deliveryNote->no_delivery_note }}</div>
            @if($totalPages > 1)
                <div class="page-indicator">Halaman {{ $pageIndex + 1 }} dari {{ $totalPages }}</div>
            @endif
        </div>

        <div class="info-po">
            NO. PO&nbsp;:&nbsp; <strong>{{ $deliveryNote->no_po ?? '-' }}</strong>
        </div>

        <table class="items">
            <colgroup>
                <col style="width:32px;">
                <col>
                <col style="width:80px;">
                <col style="width:65px;">
                <col style="width:95px;">
                <col style="width:105px;">
            </colgroup>
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY</th>
                    <th>UNIT</th>
                    <th>UNIT PRICE</th>
                    <th>TOTAL PRICE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pageItems as $localIndex => $item)
                    <tr>
                        <td class="no">{{ $startIndex + $localIndex + 1 }}</td>
                        <td>{{ strtoupper($item->nama_barang) }}</td>
                        <td class="qty">{{ rtrim(rtrim(number_format($item->qty, 2, ',', '.'), '0'), ',') }}</td>
                        <td class="unit">{{ $item->satuan ?? '-' }}</td>
                        <td class="price">{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="total">{{ number_format($item->total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach

                @if($isLastPage)
                    @if($pakaiPpn)
                        <tr class="summary-row">
                            <td class="no">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="total-label">SUBTOTAL</td>
                            <td class="total">{{ number_format($subtotal, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="summary-row">
                            <td class="no">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="total-label">PPN ({{ rtrim(rtrim(number_format($ppnPersen, 2, ',', '.'), '0'), ',') }}%)</td>
                            <td class="total">{{ number_format($ppnNominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr class="summary-row grand-total">
                            <td class="no">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="total-label">TOTAL</td>
                            <td class="total">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    @else
                        <tr class="summary-row grand-total">
                            <td class="no">&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td class="total-label">TOTAL</td>
                            <td class="total">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>

        @if($isLastPage)
            @if($deliveryNote->catatan)
                <p class="catatan">Catatan: {{ $deliveryNote->catatan }}</p>
            @endif

            <p class="ket-terima">
                Tanda terima barang, Barang diatas telah kami terima dalam keadaan cukup dan baik.
            </p>

            <div class="footer">
                <div class="box">
                    <div class="box-heading">&nbsp;</div>
                    <div class="ttd-space"></div>
                    <div class="nama-line">Penerima</div>
                </div>
                <div class="box">
                  <div class="box-heading">Sangatta, {{ $deliveryNote->tanggal ? \Carbon\Carbon::parse($deliveryNote->tanggal)->translatedFormat('d F Y') : '' }}</div>
                    <div class="ttd-space"></div>
                    <div class="nama-line">{{ $deliveryNote->perusahaan->nama_perusahaan ?? '-' }}</div>
                </div>
            </div>
        @endif

    </div>

    @php $runningIndex += $sizeThisPage; @endphp
@endforeach
</body>
</html>
