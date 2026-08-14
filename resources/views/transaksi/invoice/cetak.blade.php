<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no, url=no">
    <title>Invoice - {{ $invoice->no_invoice }}</title>
    <style>
        @page { size: A4; margin: 15mm 18mm 15mm 15mm; }
        * { box-sizing: border-box; }
        html, body { margin: 0; padding: 0; background: #e9ecef; font-family: 'Calibri', 'Carlito', Arial, sans-serif; }
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 10mm auto;
            background: #fff;
            padding: 0;
            font-size: 17px;
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

        .company-header { text-align: center; }
        .company-name { font-size: 27px; font-weight: bold; margin: 0 0 4px 0; letter-spacing: 0.5px; }
        .company-detail { font-size: 15px; line-height: 1.6; margin: 0; }

        .divider { border-bottom: 2px solid #000; margin: 8px 0 10px 0; }

        .title-box { text-align: center; margin: 4px 0 14px 0; }
        .title-box h2 { margin: 0; font-size: 20px; text-decoration: underline; letter-spacing: 2px; }
        .title-box .page-indicator { font-size: 14px; margin-top: 2px; font-style: italic; }

        .top-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            font-size: 15px;
            margin-bottom: 12px;
        }
        .invoice-meta table { border-collapse: collapse; }
        .invoice-meta td { padding: 1px 4px 1px 0; vertical-align: top; }
        .invoice-meta td.label { white-space: nowrap; }
        .invoice-meta td.colon { padding: 0 4px; }

        .customer-info { text-align: right; max-width: 260px; }
        .customer-info .nama-customer { font-weight: bold; }
        .customer-info .alamat-customer { font-size: 12px; line-height: 1.3; }

        table.items { width: calc(100% - 3mm); border-collapse: collapse; table-layout: fixed; }
        table.items th, table.items td { border: 1px solid #000; padding: 3px 6px; }
        table.items th { background: #f0f0f0; font-size: 14px; text-align: center; font-weight: bold; white-space: normal; overflow: visible; }
        table.items td { font-size: 15px; vertical-align: middle; }
        table.items td.no { text-align: center; }
        table.items td.qty { text-align: center; }
        table.items td.unit { text-align: center; }
        table.items td.price, table.items td.total { text-align: right; white-space: nowrap; }

        table.items tbody td {
            height: 19px;
            padding: 3px 6px;
            border-left: 1px solid #000;
            border-right: 1px solid #000;
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
            padding: 4px 6px;
            font-size: 15px;
        }
        .summary-row td.label { text-align: right; }
        .summary-row td.value { text-align: right; white-space: nowrap; }
        .summary-row:first-of-type td { border-top: none; }
        .summary-row.grand-total td {
            font-weight: bold;
            border-top: 1px solid #000 !important;
            border-bottom: 1px solid #000 !important;
        }
        .summary-row:last-child td { border-bottom: 1px solid #000; }

        .catatan { font-size: 15px; margin-top: 8px; font-style: italic; }

        .terbilang { margin-top: 12px; font-size: 15px; font-style: italic; }

        .rekening-info { margin-top: 14px; font-size: 15px; line-height: 1.6; }

        .footer {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }
        .footer .box { width: 45%; font-size: 15px; text-align: center; }
        .footer .box .ttd-space { height: 160px; }
        .footer .box .nama-line {
            border-top: 1px solid #000;
            margin-top: 4px;
            padding-top: 4px;
            display: inline-block;
            min-width: 180px;
            font-weight: bold;
        }
        .footer .box .jabatan { font-size: 14px; margin-top: 2px; }

        @media screen {
            .page { padding: 15mm; }
        }

        @media print {
            body { background: #fff; }
            .page { margin: 0; padding: 0; box-shadow: none; }
            .toolbar { display: none !important; }
        }
    </style>
</head>
<body>

<div class="toolbar">
    <button onclick="window.print()">🖨️ Print / Save PDF</button>
</div>

@php
    $mainPerPage = 20;
    $allItems = $invoice->items->values();
    $totalItems = $allItems->count();

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
    $runningIndex = 0;
@endphp

@foreach($pageSizes as $pageIndex => $sizeThisPage)
    @php
        $startIndex = $runningIndex;
        $pageItems = $allItems->slice($startIndex, $sizeThisPage)->values();
        $isLastPage = ($pageIndex === $totalPages - 1);
    @endphp

    <div class="page">

        <div class="company-header">
            <p class="company-name">{{ strtoupper($invoice->perusahaan->nama_perusahaan ?? '-') }}</p>
            <p class="company-detail">
                {{ $invoice->perusahaan->alamat ?? '' }}<br>
                @if($invoice->perusahaan->telp)
                    Phone {{ $invoice->perusahaan->telp }}<br>
                @endif
                @if($invoice->perusahaan->email)
                    Email : {{ $invoice->perusahaan->email }}
                @endif
            </p>
        </div>

        <div class="divider"></div>

        <div class="title-box">
            <h2>SALES INVOICE</h2>
            @if($totalPages > 1)
                <div class="page-indicator">Halaman {{ $pageIndex + 1 }} dari {{ $totalPages }}</div>
            @endif
        </div>

        <div class="top-info">
            <div class="invoice-meta">
                <table>
                    <tr>
                        <td class="label">Invoice No.</td>
                        <td class="colon">:</td>
                        <td>{{ $invoice->no_invoice }}</td>
                    </tr>
                    <tr>
                        <td class="label">Invoice Date</td>
                        <td class="colon">:</td>
                        <td>{{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d / m / Y') }}</td>
                    </tr>
                    <tr>
                        <td class="label">Ref. No PO</td>
                        <td class="colon">:</td>
                        <td>{{ $invoice->no_po ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <div class="customer-info">
                <div class="nama-customer">{{ strtoupper($invoice->customer->nama_customer ?? '-') }}</div>
                <div class="alamat-customer">{{ $invoice->customer->alamat ?? '' }}</div>
            </div>
        </div>

        <table class="items">
            <colgroup>
                <col style="width:28px;">
                <col>
                <col style="width:70px;">
                <col style="width:55px;">
                <col style="width:90px;">
                <col style="width:100px;">
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
                    <tr class="summary-row">
                        <td class="no">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="label">SUBTOTAL</td>
                        <td class="value">{{ number_format($invoice->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="summary-row">
                        <td class="no">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="label">PPN ({{ rtrim(rtrim(number_format($invoice->ppn_persen, 2, ',', '.'), '0'), ',') }}%)</td>
                        <td class="value">{{ number_format($invoice->ppn_nominal, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="summary-row grand-total">
                        <td class="no">&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td class="label">TOTAL</td>
                        <td class="value">{{ number_format($invoice->total, 0, ',', '.') }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        @if($isLastPage)
            @if($invoice->catatan)
                <p class="catatan">Catatan: {{ $invoice->catatan }}</p>
            @endif

            <div class="terbilang">
                Terbilang : {{ \App\Helpers\Terbilang::rupiah($invoice->total) }}
            </div>

            <div class="rekening-info">
                Pembayaran agar ditransfer ke Rekening<br>
                {{ $invoice->rekening->nama_bank ?? '-' }} No. Rek {{ $invoice->rekening->no_rekening ?? '-' }}<br>
                a.n {{ $invoice->rekening->atas_nama ?? '-' }}
            </div>

            <div class="footer">
                <div class="box">
                    <div>
                        Sangatta, {{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->translatedFormat('d F Y') }}
                    </div>
                    <div class="ttd-space"></div>
                    <div class="nama-line">{{ $invoice->perusahaan->nama_direktur ?? $invoice->perusahaan->nama_perusahaan ?? '-' }}</div>
                    <div class="jabatan">{{ $invoice->perusahaan->jabatan_direktur ?? 'Direktur' }}</div>
                </div>
            </div>
        @endif

    </div>

    @php $runningIndex += $sizeThisPage; @endphp
@endforeach

<script>
    (function() {
        const noInvoice = @json($invoice->no_invoice);
        const tanggal = @json(\Carbon\Carbon::parse($invoice->tanggal_invoice)->locale('en')->translatedFormat('d-F-Y'));
        const perusahaan = @json($invoice->perusahaan->nama_perusahaan ?? '-');
        const customer = @json($invoice->customer->nama_customer ?? '-');
        const noPo = @json($invoice->no_po ?? '-');

        function sanitize(str) {
            return String(str).replace(/[\\/:*?"<>|]/g, '-').trim();
        }

        const fileName = [noInvoice, tanggal, perusahaan, customer, noPo]
            .map(sanitize)
            .join('_');

        const originalTitle = document.title;

        window.addEventListener('beforeprint', function() {
            document.title = fileName;
        });

        window.addEventListener('afterprint', function() {
            document.title = originalTitle;
        });

        document.title = fileName;
    })();
</script>

</body>
</html>
