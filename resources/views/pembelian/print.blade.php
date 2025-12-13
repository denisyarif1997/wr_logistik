<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Order - {{ $pembelian->no_po }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
        }
        .meta {
            margin-bottom: 20px;
            width: 100%;
        }
        .meta td {
            vertical-align: top;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 5px;
        }
        .table th {
            background-color: #f0f0f0;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .totals {
            width: 40%;
            margin-left: auto;
        }
        .totals td {
            padding: 5px;
        }
        .footer {
            margin-top: 50px;
            width: 100%;
        }
        .footer td {
            text-align: center;
            width: 33%;
        }
        .signature-line {
            margin-top: 50px;
            border-top: 1px solid #000;
            width: 80%;
            margin-left: auto;
            margin-right: auto;
        }
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="header">
        <h1>PURCHASE ORDER</h1>
        <h3>{{ config('app.name') }}</h3>
    </div>

    <table class="meta">
        <tr>
            <td width="60%">
                <strong>Supplier:</strong><br>
                {{ $pembelian->supplier->nama_supplier ?? '-' }}<br>
                {{ $pembelian->supplier->alamat ?? '' }}<br>
                {{ $pembelian->supplier->no_telp ?? '' }}
            </td>
            <td width="40%">
                <strong>No. PO:</strong> {{ $pembelian->no_po }}<br>
                <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($pembelian->tanggal_po)->format('d-m-Y') }}<br>
                <strong>Status:</strong> {{ ucfirst($pembelian->status) }}
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th class="text-center" width="5%">No</th>
                <th>Nama Barang</th>
                <th class="text-center" width="10%">Qty</th>
                <th class="text-right" width="15%">Harga Satuan</th>
                <th class="text-right" width="15%">Diskon (@)</th>
                <th class="text-right" width="10%">PPN (%)</th>
                <th class="text-right" width="15%">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian->details as $index => $detail)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                <td class="text-center">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($detail->diskon, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($detail->ppn, 2, ',', '.') }}%</td>
                <td class="text-right">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        <tr>
            <td class="text-right"><strong>Subtotal:</strong></td>
            <td class="text-right">{{ number_format($pembelian->details->sum('subtotal'), 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Diskon Global:</strong></td>
            <td class="text-right">{{ number_format($pembelian->diskon, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>PPN Global:</strong></td>
            <td class="text-right">{{ number_format($pembelian->ppn, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Biaya Lain-lain:</strong></td>
            <td class="text-right">{{ number_format($pembelian->biaya_lain, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="text-right"><strong>Grand Total:</strong></td>
            <td class="text-right"><strong>{{ number_format($pembelian->details->sum('subtotal') - $pembelian->diskon + $pembelian->ppn + $pembelian->biaya_lain, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <table class="footer">
        <tr>
            <td>
                Dibuat Oleh,
                <div class="signature-line"></div>
                {{ $pembelian->creator->name ?? 'Admin' }}
            </td>
            <td>
                Disetujui Oleh,
                <div class="signature-line"></div>
                Manager
            </td>
            <td>
                Diterima Oleh,
                <div class="signature-line"></div>
                Supplier
            </td>
        </tr>
    </table>

</body>
</html>
