<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $sale->invoice_number }}</title>
    <style>
        @page {
            size: 80mm 200mm;
            margin: 0;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            padding: 10mm;
            margin: auto;
            color: #000;
            background: #fff;
            font-size: 12px;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 5mm;
        }
        .logo {
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 2px;
        }
        .address {
            font-size: 10px;
        }
        .separator {
            border-bottom: 1px dashed #000;
            margin: 3mm 0;
        }
        .info {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin: 2mm 0;
        }
        .items td {
            padding: 1mm 0;
        }
        .total-section {
            margin-top: 3mm;
            font-weight: bold;
            font-size: 13px;
        }
        .footer {
            text-align: center;
            margin-top: 8mm;
            font-size: 10px;
        }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">{{ config('app.pharmacy_name') }}</div>
        <div class="address">{{ config('app.pharmacy_address') }}</div>
        <div class="address">Telp: {{ config('app.pharmacy_phone') }}</div>
    </div>

    <div class="separator"></div>

    <div class="info">
        <span>Invoice:</span>
        <span>{{ $sale->invoice_number }}</span>
    </div>
    <div class="info">
        <span>Kasir:</span>
        <span>{{ $sale->user->name }}</span>
    </div>
    <div class="info">
        <span>Tanggal:</span>
        <span>{{ $sale->created_at->format('d/m/Y H:i') }}</span>
    </div>
    <div class="info">
        <span>Metode:</span>
        <span>{{ strtoupper($sale->payment_method ?? 'TUNAI') }}</span>
    </div>

    <div class="separator"></div>

    <table class="items">
        @foreach($sale->details as $detail)
            <tr>
                <td colspan="2" style="font-weight: bold;">{{ $detail->medicine?->nama ?? $detail->service?->nama ?? 'Item' }}</td>
            </tr>
            <tr>
                <td>{{ $detail->quantity }} x {{ number_format($detail->price, 0, ',', '.') }}</td>
                <td style="text-align: right;">{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="separator"></div>

    <div class="info total-section">
        <span>TOTAL:</span>
        <span>Rp {{ number_format($sale->total_price, 0, ',', '.') }}</span>
    </div>

    <div class="separator"></div>

    <div class="footer">
        <div>Terima Kasih</div>
        <div>Semoga Lekas Sembuh</div>
        <div style="margin-top: 3mm; font-size: 8px;">{{ now()->format('Y-m-d H:i:s') }}</div>
    </div>

    <script>
        window.onload = function() {
            // Optional: window.print();
        }
    </script>
</body>
</html>
