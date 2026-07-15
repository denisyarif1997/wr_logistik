<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Billing - {{ $penjualan->no_penjualan }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            margin: 0;
            padding: 10px;
            width: 80mm;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }
        .header h2 {
            margin: 0 0 5px 0;
            font-size: 16px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            font-size: 10px;
        }
        .info {
            margin-bottom: 10px;
            font-size: 10px;
        }
        .info table {
            width: 100%;
        }
        .info td {
            padding: 1px 0;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .items th {
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 5px 2px;
            font-size: 10px;
            text-align: left;
        }
        .items td {
            padding: 3px 2px;
            font-size: 10px;
        }
        .items .text-right {
            text-align: right;
        }
        .items .text-center {
            text-align: center;
        }
        .totals {
            width: 100%;
            border-top: 1px dashed #000;
            padding-top: 5px;
        }
        .totals td {
            padding: 2px 2px;
            font-size: 11px;
        }
        .totals .text-right {
            text-align: right;
        }
        .grand-total {
            font-weight: bold;
            font-size: 13px;
        }
        .footer {
            text-align: center;
            margin-top: 15px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }
        .payment-info {
            margin-bottom: 10px;
            font-size: 10px;
        }
        .payment-info td {
            padding: 1px 0;
        }
        @media print {
            body { width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ config('app.name') }}</h2>
        <p>BUKTI PENJUALAN / BILLING</p>
        <p style="font-size:14px; font-weight:bold;">{{ $penjualan->no_penjualan }}</p>
    </div>

    <div class="info">
        <table>
            <tr>
                <td>Tanggal</td>
                <td>: {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td>Gudang</td>
                <td>: {{ $penjualan->gudang->nama_gudang ?? '-' }}</td>
            </tr>
            @if($penjualan->keterangan)
            <tr>
                <td>Keterangan</td>
                <td>: {{ $penjualan->keterangan }}</td>
            </tr>
            @endif
            <tr>
                <td>Kasir</td>
                <td>: {{ $penjualan->creator->name ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th style="width:5%">No</th>
                <th style="width:40%">Barang</th>
                <th style="width:12%" class="text-center">Qty</th>
                <th style="width:18%" class="text-right">Harga</th>
                <th style="width:25%" class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($penjualan->details as $index => $d)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $d->barang->nama_barang ?? '-' }}</td>
                <td class="text-center">{{ number_format($d->qty, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($d->harga_satuan, 0, ',', '.') }}</td>
                <td class="text-right">{{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals">
        @php
            $subtotal = $penjualan->details->sum('subtotal');
            $diskonAmount = $subtotal * ($penjualan->diskon / 100);
            $afterDiskon = $subtotal - $diskonAmount;
        @endphp
        <tr>
            <td style="width:60%">Subtotal</td>
            <td class="text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
        </tr>
        @if($penjualan->diskon > 0)
        <tr>
            <td>Diskon ({{ $penjualan->diskon }}%)</td>
            <td class="text-right">- Rp {{ number_format($diskonAmount, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($penjualan->ppn > 0)
        <tr>
            <td>PPN ({{ $penjualan->ppn_rate }}%)</td>
            <td class="text-right">Rp {{ number_format($penjualan->ppn, 0, ',', '.') }}</td>
        </tr>
        @endif
        <tr>
            <td style="width:60%"><strong>Total</strong></td>
            <td class="text-right grand-total">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="payment-info">
        <table>
            <tr>
                <td style="width:50%">Uang Bayar</td>
                <td class="text-right">Rp {{ number_format($penjualan->bayar, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Kembalian</td>
                <td class="text-right">Rp {{ number_format($penjualan->kembalian, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Terima kasih telah berbelanja di {{ config('app.name') }}</p>
        <p>Barang yang sudah dibeli tidak dapat dikembalikan</p>
        <p style="margin-top:5px;">{{ now()->format('d-m-Y H:i:s') }}</p>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>