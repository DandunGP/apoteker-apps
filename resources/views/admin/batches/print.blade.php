<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Faktur - {{ $batch->no_faktur }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 14px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 30px;
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
            color: #4F46E5;
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
        }
        .invoice-details table {
            margin-left: auto;
        }
        .invoice-details th {
            text-align: left;
            padding-right: 15px;
            color: #666;
            font-weight: normal;
        }
        .invoice-details td {
            font-weight: bold;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .badge-lunas { background: #ECFDF5; color: #10B981; border: 1px solid #10B981; }
        .badge-tempo { background: #FEF3C7; color: #D97706; border: 1px solid #D97706; }
        .badge-titipan { background: #F3E8FF; color: #9333EA; border: 1px solid #9333EA; }
        
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
            @page { margin: 0; }
            body { padding: 2cm; background-color: white !important; }
            .container { border: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body style="background-color: #f3f4f6;">
    <div class="container" style="background: white; margin-top: 20px;">
        <div class="header">
            <div class="company-details">
                <h1>{{ config('app.pharmacy_name') }}</h1>
                <p>{{ config('app.pharmacy_address') }}</p>
                <p>Telp: {{ config('app.pharmacy_phone') }}</p>
            </div>
            <div class="invoice-details">
                <h2>BUKTI PENERIMAAN OBAT</h2>
                <table>
                    <tr>
                        <th>No. Faktur:</th>
                        <td>{{ $batch->no_faktur }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk:</th>
                        <td>{{ \Carbon\Carbon::parse($batch->tanggal_masuk)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <th>Jenis Faktur:</th>
                        <td>
                            @if($batch->tipe_faktur == 'Lunas')
                                <span class="badge badge-lunas">LUNAS</span>
                            @elseif($batch->tipe_faktur == 'Tempo')
                                <span class="badge badge-tempo">TEMPO</span>
                            @else
                                <span class="badge badge-titipan">TITIPAN</span>
                            @endif
                        </td>
                    </tr>
                    @if($batch->tipe_faktur == 'Tempo' && $batch->tanggal_jatuh_tempo)
                    <tr>
                        <th>Jatuh Tempo:</th>
                        <td style="color: #D97706;">{{ \Carbon\Carbon::parse($batch->tanggal_jatuh_tempo)->format('d F Y') }}</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th style="width: 50px; text-align: center;">No</th>
                    <th>Nama Obat</th>
                    <th>No. Batch</th>
                    <th style="text-align: center;">Tgl Kadaluwarsa</th>
                    <th style="text-align: right;">Jumlah Qty</th>
                </tr>
            </thead>
            <tbody>
                @foreach($all_batches as $item)
                <tr>
                    <td style="text-align: center;">{{ $loop->iteration }}</td>
                    <td style="font-weight: bold;">{{ $item->medicine->nama }} <br><span style="font-size: 12px; color: #666; font-weight: normal;">{{ $item->medicine->kode }} - {{ $item->medicine->kategori }}</span></td>
                    <td>{{ $item->no_batch }}</td>
                    <td style="text-align: center;">{{ \Carbon\Carbon::parse($item->tanggal_kadaluwarsa)->format('d M Y') }}</td>
                    <td style="text-align: right; font-weight: bold; font-size: 16px;">{{ $item->stok_sisa }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <div class="signature-box">
                <p>Penerima,</p>
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
