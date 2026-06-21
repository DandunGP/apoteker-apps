<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Peringatan Kadaluwarsa Obat</title>
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f4f5f7;
            color: #333333;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            border: 1px solid #e1e4e8;
        }
        .email-header {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: #ffffff;
            padding: 30px 40px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .email-header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            opacity: 0.9;
        }
        .email-body {
            padding: 40px;
        }
        .salutation {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
        }
        .alert-card {
            background-color: #fef3c7;
            border-left: 4px solid #d97706;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .alert-card p {
            margin: 0;
            color: #92400e;
            font-weight: 500;
            font-size: 15px;
        }
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .details-table th, .details-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }
        .details-table th {
            background-color: #f9fafb;
            color: #4b5563;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            width: 35%;
        }
        .details-table td {
            color: #1f2937;
            font-size: 15px;
        }
        .action-container {
            text-align: center;
            margin: 35px 0 15px 0;
        }
        .btn-action {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: #ffffff !important;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            transition: all 0.2s ease;
        }
        .email-footer {
            background-color: #f9fafb;
            padding: 25px 40px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer p {
            margin: 5px 0;
        }
        .pharmacy-name {
            font-weight: 600;
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Peringatan Kadaluwarsa</h1>
            <p>Sistem Pemantauan Obat Apotek</p>
        </div>

        <!-- Body -->
        <div class="email-body">
            <div class="salutation">Halo, {{ $user->name }}</div>
            <p>Sistem mendeteksi adanya obat yang telah atau akan segera memasuki masa kadaluwarsa. Berikut adalah detail informasinya:</p>
            
            <div class="alert-card">
                <p>{{ $messageContent }}</p>
            </div>

            <table class="details-table">
                <tr>
                    <th>Nama Obat</th>
                    <td>{{ $batch->medicine->nama ?? '-' }} ({{ $batch->medicine->kode ?? '-' }})</td>
                </tr>
                <tr>
                    <th>Nomor Batch</th>
                    <td><code>{{ $batch->no_batch }}</code></td>
                </tr>
                <tr>
                    <th>Sisa Stok</th>
                    <td>{{ $batch->stok_sisa }} {{ $batch->medicine->satuan ?? 'Satuan' }}</td>
                </tr>
                <tr>
                    <th>Tanggal Kadaluwarsa</th>
                    <td>{{ \Carbon\Carbon::parse($batch->tanggal_kadaluwarsa)->translatedFormat('d F Y') }}</td>
                </tr>
            </table>

            <div class="action-container">
                <a href="{{ url('/admin/monitoring/expiry') }}" class="btn-action">Lihat Monitoring Kadaluwarsa</a>
            </div>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p class="pharmacy-name">{{ config('app.name', 'Apotek Pakis Medika Utama') }}</p>
            <p>Email ini dikirimkan secara otomatis oleh sistem. Mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>
