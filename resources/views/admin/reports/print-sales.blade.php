<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi Penjualan Apotek Pakis Medika Utama</title>
    <style>
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1E293B;
            background: #fff;
            padding: 15mm 20mm;
        }
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0B3E9C;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .pharmacy-name {
            font-size: 18px;
            font-weight: 900;
            color: #0F3071;
        }
        .pharmacy-sub {
            font-size: 10px;
            color: #64748B;
            font-weight: 600;
            margin-top: 2px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: 900;
            color: #0F3071;
            text-align: right;
        }
        .summary-banner {
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }
        .sum-box {
            flex: 1;
            border: 1px solid #E2E8F0;
            border-radius: 5px;
            padding: 8px 12px;
        }
        .s-lbl {
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            color: #64748B;
        }
        .s-val {
            font-size: 14px;
            font-weight: 900;
            color: #1E293B;
            margin-top: 2px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .data-table th {
            background: #EFF6FF;
            color: #0B3E9C;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            padding: 8px 10px;
            border: 1px solid #BFDBFE;
            text-align: left;
        }
        .data-table td {
            padding: 9px 10px;
            border: 1px solid #E2E8F0;
            font-size: 10.5px;
        }
        .data-table tr:nth-child(even) td {
            background: #F8FAFC;
        }
        .footer-sig {
            display: flex;
            justify-content: space-between;
            margin-top: 45px;
            page-break-inside: avoid;
        }
        .sig-box {
            text-align: center;
            width: 180px;
        }
        .role {
            font-weight: 800;
            color: #475569;
            margin-bottom: 50px;
        }
        .name {
            font-weight: 900;
            color: #1E293B;
            text-decoration: underline;
        }
        .nip {
            font-size: 9px;
            color: #64748B;
            margin-top: 2px;
        }
    </style>
</head>
<body onload="window.print();">

    <div class="doc-header">
        <div>
            <div class="pharmacy-name">APOTEK PAKIS MEDIKA UTAMA</div>
            <div class="pharmacy-sub">Sistem Kelola Rekapitulasi Kasir Penjualan</div>
        </div>
        <div>
            <div class="doc-title">REKAPITULASI PENJUALAN</div>
            <div style="font-size:10px; text-align:right; color:#64748B; margin-top:2px;">
                @if($dateFrom && $dateTo)
                    Periode: {{ date('d/m/Y', strtotime($dateFrom)) }} s/d {{ date('d/m/Y', strtotime($dateTo)) }}
                @else
                    Semua Periode Transaksi
                @endif
            </div>
        </div>
    </div>

    <div class="summary-banner">
        <div class="sum-box">
            <div class="s-lbl">Total Transaksi</div>
            <div class="s-val">{{ count($items) }} Penjualan</div>
        </div>
        <div class="sum-box">
            <div class="s-lbl">Total Nilai Pendapatan</div>
            <div class="s-val" style="color: #0B3E9C;">Rp {{ number_format($items->sum('total_price'), 0, ',', '.') }}</div>
        </div>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">No. Invoice</th>
                <th style="width: 25%;">Kasir Penerima</th>
                <th style="width: 20%;">Tanggal Transaksi</th>
                <th style="width: 12%;">Metode Pembayaran</th>
                <th style="width: 13%; text-align: right;">Total Nilai</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $idx => $i)
                <tr>
                    <td>{{ $idx + 1 }}</td>
                    <td><strong>{{ $i->invoice_number }}</strong></td>
                    <td>{{ $i->user->name ?? 'Kasir' }}</td>
                    <td>{{ date('d-m-Y H:i', strtotime($i->created_at)) }}</td>
                    <td>{{ strtoupper($i->payment_method) }}</td>
                    <td style="text-align: right;"><strong>Rp {{ number_format($i->total_price, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer-sig">
        <div class="sig-box">
            <div class="role">Staf Kasir Utama,</div>
            <div class="name">{{ auth()->user()->name }}</div>
            <div class="nip">ID Staf: #{{ auth()->user()->id }}</div>
        </div>
        <div class="sig-box">
            <div class="role">Kepala Apoteker Pakis,</div>
            <div class="name">apt. H. Ahmad Fauzi, S.Farm.</div>
            <div class="nip">SIPA: 19930812/SIPA-35.78/2026/2045</div>
        </div>
    </div>

</body>
</html>
