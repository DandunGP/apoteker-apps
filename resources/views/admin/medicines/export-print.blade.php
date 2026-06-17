<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Obat & Inventaris - {{ date('d M Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 10px;
            font-size: 14px;
            background-color: #fff;
        }
        .container {
            max-width: 850px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .company-details h1 {
            margin: 0 0 5px 0;
            font-size: 24px;
            color: #0F62FE;
        }
        .company-details p {
            margin: 2px 0;
            color: #666;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-details h2 {
            margin: 0 0 10px 0;
            color: #333;
            font-size: 20px;
            letter-spacing: -0.5px;
        }
        .invoice-details p {
            margin: 2px 0;
            color: #666;
            font-size: 13px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px 12px;
            text-align: left;
            font-size: 13px;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #475569;
        }
        .status-pill {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-align: center;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }
        .signature-box {
            width: 200px;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        
        @media print {
            @page {
                size: auto;
                margin: 0;
            }
            body { 
                padding: 1.5cm; 
                background-color: white !important; 
            }
            .container { 
                border: none; 
                padding: 0; 
                box-shadow: none;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="header">
            <div class="company-details">
                <h1>{{ config('app.pharmacy_name', 'APOTEK SEHAT UTAMA') }}</h1>
                <p>{{ config('app.pharmacy_address', 'Jl. Sudirman No. 123, Jakarta') }}</p>
                <p>Telp: {{ config('app.pharmacy_phone', '(021) 555-0199') }}</p>
            </div>
            <div class="invoice-details">
                <h2>LAPORAN DATA INVENTARIS OBAT</h2>
                <p>Tanggal Cetak: <strong>{{ date('d F Y') }}</strong></p>
                <p>Total Item (SKU): <strong>{{ count($medicines) }}</strong></p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th style="width: 100px;">Kode Obat</th>
                    <th>Nama Obat / Alkes</th>
                    <th>Kategori</th>
                    <th style="text-align: center; width: 70px;">Satuan</th>
                    <th style="text-align: right; width: 100px;">Harga Jual</th>
                    <th style="text-align: center; width: 80px;">Min. Stok</th>
                    <th style="text-align: center; width: 80px;">Stok Sisa</th>
                    <th style="text-align: center; width: 120px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($medicines as $idx => $medicine)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: bold; color: #475569;">{{ $medicine->kode }}</td>
                    <td style="font-weight: bold;">
                        {{ $medicine->nama }}
                    </td>
                    <td>{{ $medicine->kategori }}</td>
                    <td style="text-align: center;">{{ $medicine->satuan }}</td>
                    <td style="text-align: right; font-weight: 600; color: #0F62FE;">
                        Rp {{ number_format($medicine->harga, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center; color: #64748B;">{{ $medicine->min_stok }}</td>
                    <td style="text-align: center; font-weight: bold;">
                        {{ number_format($medicine->total_stock, 0, ',', '.') }}
                    </td>
                    <td style="text-align: center;">
                        <span class="status-pill" style="background: {{ $medicine->status_bg }}; color: {{ $medicine->status_color }};">
                            {{ $medicine->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 20px; color: #666;">
                        Tidak ada data obat terdaftar.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div class="signature-box">
                <p>Dibuat Oleh,</p>
                <div class="signature-line"></div>
                <p>Bagian Gudang / Inventaris</p>
            </div>
            <div class="signature-box">
                <p>Mengetahui,</p>
                <div class="signature-line"></div>
                <p>Apoteker / Penanggung Jawab</p>
            </div>
        </div>
    </div>
</body>
</html>
