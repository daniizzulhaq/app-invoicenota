<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Delivery Note - {{ $deliveryNote->no_delivery_note }}</title>
    <style>
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            background: #e9ecef;
            font-family: 'Calibri', 'Carlito', Arial, sans-serif;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            background: #fff;
            padding: 15mm;
            font-size: 12px;
            color: #000;
            box-shadow: 0 0 6px rgba(0,0,0,0.15);
            page-break-after: always;
        }
        .page:last-child { page-break-after: auto; }

        .toolbar {
            width: 210mm;
            margin: 10mm auto 0 auto;
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
            margin-bottom: 12px;
        }
        .company-name {
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 0.3px;
            margin: 0 0 4px 0;
        }
        .company-sub {
            font-size: 10px;
            font-weight: bold;
            margin: 0 0 2px 0;
        }
        .company-detail {
            font-size: 11px;
            line-height: 1.6;
            margin: 0;
        }
        .kepada {
            text-align: right;
            font-size: 12px;
            padding-top: 4px;
            margin-right: 40px;
        }
        .kepada .nama-customer { font-weight: bold; font-size: 13px; margin-top: 3px; }

        .title-box {
            text-align: center;
            margin: 8px 0 12px 0;
        }
        .title-box h2 {
            margin: 0;
            font-size: 15px;
            text-decoration: underline;
            letter-spacing: 1px;
        }
        .title-box .no-dn {
            font-size: 11px;
            margin-top: 2px;
        }
        .title-box .page-indicator {
            font-size: 10px;
            margin-top: 2px;
            font-style: italic;
        }

        .info-po {
            text-align: right;
            font-size: 11px;
            margin-bottom: 6px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        table.items th, table.items td {
            border: 1px solid #000;
            padding: 3px 5px;
        }
        table.items th {
            background: #f0f0f0;
            font-size: 10px;
            text-align: center;
        }
        table.items td { font-size: 10.5px; }
        table.items td.no { text-align: center; }
        table.items td.qty { text-align: center; }
        table.items td.unit { text-align: center; }
        table.items td.price,
        table.items td.total { text-align: right; white-space: nowrap; }
        table.items tr.empty-row td { height: 15px; }

        .total-row td {
            font-weight: bold;
            text-align: right;
        }
        .total-row td.total-label {
            text-align: center;
            font-size: 10px;
        }

        .catatan {
            font-size: 10px;
            margin-top: 6px;
            font-style: italic;
        }
        .ket-terima {
            font-size: 10.5px;
            margin-top: 10px;
        }

        .footer {
            display: flex;
            justify-content: space-between;
            margin-top: 35px;
        }
        .footer .box {
            width: 45%;
            font-size: 10.5px;
            text-align: center;
        }
        .footer .box .ttd-space { height: 55px; }
        .footer .box .nama-line {
            border-top: 1px solid #000;
            margin-top: 4px;
            padding-top: 4px;
            display: inline-block;
            min-width: 160px;
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
    $perPage = 25;
    $allItems = $deliveryNote->items;
    $totalItems = $allItems->count();
    $totalPages = max(1, (int) ceil($totalItems / $perPage));
    $grandTotal = $allItems->sum('total');
@endphp

@for($page = 0; $page < $totalPages; $page++)
    @php
        $startIndex = $page * $perPage;
        $pageItems = $allItems->slice($startIndex, $perPage);
        $isLastPage = ($page === $totalPages - 1);
    @endphp

    <div class="page">

        <div class="header">
            <div>
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
                <div class="page-indicator">Halaman {{ $page + 1 }} dari {{ $totalPages }}</div>
            @endif
        </div>

        <div class="info-po">
            NO. PO&nbsp;:&nbsp; <strong>{{ $deliveryNote->no_po ?? '-' }}</strong>
        </div>

        <table class="items">
            <colgroup>
                <col style="width:28px;">
                <col>
                <col style="width:60px;">
                <col style="width:45px;">
                <col style="width:80px;">
                <col style="width:90px;">
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

                @php
                    $filledOnThisPage = $pageItems->count();
                @endphp
                @for($i = $filledOnThisPage; $i < $perPage; $i++)
                    <tr class="empty-row">
                        <td class="no">{{ $startIndex + $i + 1 }}</td>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endfor

                @if($isLastPage)
                    <tr class="total-row">
                        <td colspan="4"></td>
                        <td class="total-label">TOTAL<br>AMOUNT</td>
                        <td class="total">{{ number_format($grandTotal, 0, ',', '.') }}</td>
                    </tr>
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
                    <div class="ttd-space"></div>
                    <div class="nama-line">Penerima</div>
                </div>
                <div class="box">
                    <div>{{ $deliveryNote->perusahaan->alamat ?? '' }}, {{ \Carbon\Carbon::parse($deliveryNote->tanggal)->translatedFormat('d F Y') }}</div>
                    <div style="margin-top:2px;">Hormat Kami,</div>
                    <div class="ttd-space"></div>
                    <div class="nama-line">{{ $deliveryNote->perusahaan->nama_perusahaan ?? '-' }}</div>
                </div>
            </div>
        @endif

    </div>
@endfor

</body>
</html>