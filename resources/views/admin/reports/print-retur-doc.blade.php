<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Jalan Retur Obat Apotek Pakis Medika Utama</title>
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
            margin-bottom: 20px;
        }
        .pharmacy-name {
            font-size: 18px;
            font-weight: 900;
            color: #0F3071;
        }
        .pharmacy-sub {
            font-size: 9.5px;
            color: #64748B;
            font-weight: 600;
            margin-top: 2px;
            line-height: 1.4;
        }
        .doc-title {
            font-size: 13px;
            font-weight: 900;
            color: #0F3071;
            text-align: right;
        }
        .doc-meta {
            font-size: 10px;
            color: #475569;
            text-align: right;
            margin-top: 4px;
            line-height: 1.4;
        }
        .recipient-box {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 5px;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 11px;
            line-height: 1.5;
        }
        .recipient-title {
            font-size: 9px;
            font-weight: 850;
            color: #0B3E9C;
            text-transform: uppercase;
            margin-bottom: 4px;
            letter-spacing: 0.5px;
        }
        .doc-body-text {
            font-size: 11.5px;
            line-height: 1.6;
            margin-bottom: 15px;
            color: #334155;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        .data-table th {
            background: #EFF6FF;
            color: #0B3E9C;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            padding: 9px 12px;
            border: 1px solid #BFDBFE;
            text-align: left;
        }
        .data-table td {
            padding: 10px 12px;
            border: 1px solid #E2E8F0;
            font-size: 10.5px;
        }
        .data-table tr:nth-child(even) td {
            background: #F8FAFC;
        }
        .footer-sig {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
            page-break-inside: avoid;
        }
        .sig-box {
            text-align: center;
            width: 180px;
        }
        .role {
            font-weight: 800;
            color: #475569;
            margin-bottom: 60px;
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
            <div class="pharmacy-sub">
                Jl. Raya Pakis No. 88, Malang, Jawa Timur<br>
                Telp: (0341) 788992 | Email: info@pakismedika.com
            </div>
        </div>
        <div>
            <div class="doc-title">NOTA RETUR OBAT KADALUWARSA</div>
            <div class="doc-meta">
                No. Dokumen: <strong>RET/{{ $adj->created_at->format('Ymd') }}/{{ $adj->id }}</strong><br>
                Tanggal: {{ $adj->created_at->format('d F Y') }}
            </div>
        </div>
    </div>

    <div class="recipient-box">
        <div class="recipient-title">Tujuan Pengembalian (Supplier):</div>
        <strong>Grup Supplier Farmasi Terkait</strong><br>
        Distributor Resmi Obat & Alat Kesehatan Apotek Pakis Medika Utama
    </div>

    <div class="doc-body-text">
        Dengan hormat,<br>
        Bersama surat ini kami kirimkan kembali produk obat yang telah mendekati masa kadaluwarsa (ED &lt; 3 Bulan) untuk dapat diproses penukarannya sesuai dengan kesepakatan jaminan retur distributor yang berlaku:
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Produk Obat</th>
                <th style="width: 20%;">Nomor Batch</th>
                <th style="width: 20%;">Tanggal Kadaluwarsa</th>
                <th style="width: 20%; text-align: right;">Jumlah Retur</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td><strong>{{ $adj->batch->medicine->nama ?? 'Unknown' }}</strong></td>
                <td><span style="font-family: monospace; font-weight: bold;">{{ $adj->batch->no_batch ?? '-' }}</span></td>
                <td>{{ date('d-m-Y', strtotime($adj->batch->tanggal_kadaluwarsa)) }}</td>
                <td style="text-align: right; font-weight: bold; color: #B91C1C;">{{ abs($adj->difference) }} Unit</td>
            </tr>
        </tbody>
    </table>

    <div class="doc-body-text" style="margin-top: -20px; font-size: 11px; color:#64748B;">
        * Mohon berkas tanda terima di bawah ini ditandatangani oleh salesman / pengirim barang distributor dan diserahkan kembali ke Apotek Pakis Medika Utama sebagai bukti serah terima retur resmi.
    </div>

    <div class="footer-sig">
        <div class="sig-box">
            <div class="role">Salesman / Distributor,</div>
            <div style="border-bottom: 1.5px solid #1E293B; width: 140px; margin: 0 auto 50px auto; height: 35px;"></div>
            <div class="name">( ........................................ )</div>
            <div class="nip">Tanda Tangan & Nama Terang</div>
        </div>
        <div class="sig-box">
            <div class="role">Kepala Apoteker Pakis,</div>
            <div class="name">apt. H. Ahmad Fauzi, S.Farm.</div>
            <div class="nip">SIPA: 19930812/SIPA-35.78/2026/2045</div>
        </div>
    </div>

</body>
</html>
