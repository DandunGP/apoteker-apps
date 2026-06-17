<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan FIFO Kadaluarsa - {{ date('d M Y') }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .container {
            max-width: 850px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
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
        
        /* Floating Action Bar */
        .action-bar {
            max-width: 850px;
            margin: 20px auto 0 auto;
            padding: 12px 30px;
            background: #1E293B;
            border-radius: 6px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .btn {
            padding: 8px 16px;
            border-radius: 4px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary {
            background: #0F62FE;
            color: white;
        }
        .btn-primary:hover {
            background: #0046B5;
        }
        .btn-secondary {
            background: #475569;
            color: white;
        }
        .btn-secondary:hover {
            background: #334155;
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
            .no-print { 
                display: none !important; 
            }
        }
    </style>
    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</head>
<body style="background-color: #f3f4f6; padding-bottom: 60px;">

    <!-- Action Bar visible only on screen -->
    <div class="action-bar no-print">
        <span>Pratinjau Laporan FIFO Kadaluarsa</span>
        <div style="display: flex; gap: 10px;">
            <a href="{{ route('dashboard') }}" class="btn btn-secondary">Kembali ke Dashboard</a>
            <button onclick="window.print()" class="btn btn-primary">
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <div class="container" style="margin-top: 20px;">
        <div class="header">
            <div class="company-details">
                <h1>{{ config('app.pharmacy_name') }}</h1>
                <p>{{ config('app.pharmacy_address') }}</p>
                <p>Telp: {{ config('app.pharmacy_phone') }}</p>
            </div>
            <div class="invoice-details">
                <h2>LAPORAN FIFO KADALUARSA</h2>
                <p>Tanggal Cetak: <strong>{{ date('d F Y') }}</strong></p>
                <p>Status Stok: <strong>Aktif</strong></p>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40px; text-align: center;">No</th>
                    <th>Nama Obat</th>
                    <th>No. Batch</th>
                    <th style="text-align: center;">Tgl Kadaluwarsa</th>
                    <th style="text-align: center;">Sisa Hari</th>
                    <th style="text-align: right;">Stok Sisa (Unit)</th>
                    <th style="text-align: center; width: 140px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $idx => $batch)
                <tr>
                    <td style="text-align: center;">{{ $idx + 1 }}</td>
                    <td style="font-weight: bold;">
                        {{ $batch->medicine->nama ?? 'Unknown' }}
                        <br>
                        <span style="font-size: 11px; color: #666; font-weight: normal;">
                            {{ $batch->medicine->kode ?? '-' }} - {{ $batch->medicine->kategori ?? '-' }}
                        </span>
                    </td>
                    <td>#{{ $batch->no_batch }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($batch->tanggal_kadaluwarsa)->format('d M Y') }}</td>
                    <td style="text-align: center; font-weight: 600;">
                        {{ abs($batch->days_to_expiry) }} Hari
                    </td>
                    <td style="text-align: right; font-weight: bold;">{{ number_format($batch->stok_sisa, 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        <span class="status-pill" style="background: {{ $batch->status_bg }}; color: {{ $batch->status_color }};">
                            {{ $batch->status_label }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #666;">
                        Tidak ada data peringatan kadaluarsa.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="footer">
            <div class="signature-box">
                <p>Dibuat Oleh,</p>
                <div class="signature-line"></div>
                <p>Bagian Gudang</p>
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
