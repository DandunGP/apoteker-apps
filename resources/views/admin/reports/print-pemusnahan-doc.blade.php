<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berita Acara Pemusnahan Obat Apotek Pakis Medika Utama</title>
    <style>
        @page { size: A4; margin: 0; }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1E293B;
            background: #fff;
            padding: 15mm 20mm;
            line-height: 1.6;
        }
        .doc-header {
            text-align: center;
            border-bottom: 3px double #0B3E9C;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .pharmacy-name {
            font-size: 20px;
            font-weight: 900;
            color: #0F3071;
            letter-spacing: 0.5px;
        }
        .pharmacy-sub {
            font-size: 9.5px;
            color: #64748B;
            font-weight: 600;
            margin-top: 3px;
        }
        .doc-title {
            font-size: 13px;
            font-weight: 900;
            color: #0F3071;
            text-align: center;
            text-transform: uppercase;
            margin-top: 15px;
            letter-spacing: 0.5px;
        }
        .doc-no {
            font-size: 10px;
            color: #475569;
            text-align: center;
            margin-bottom: 20px;
        }
        .intro-text {
            font-size: 11px;
            margin-bottom: 15px;
            text-align: justify;
        }
        .table-title {
            font-size: 10px;
            font-weight: 850;
            color: #0B3E9C;
            text-transform: uppercase;
            margin-bottom: 6px;
            letter-spacing: 0.4px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
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
        .footer-sig-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .sig-box {
            text-align: center;
        }
        .role {
            font-weight: 800;
            color: #475569;
            font-size: 10px;
            margin-bottom: 60px;
            line-height: 1.4;
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
        <div class="pharmacy-name">APOTEK PAKIS MEDIKA UTAMA</div>
        <div class="pharmacy-sub">
            Izin Apotek No: 442.1/092/35.07.112/2026 | Apoteker Penanggung Jawab: apt. H. Ahmad Fauzi, S.Farm.<br>
            Jl. Raya Pakis No. 88, Malang, Jawa Timur | Telp: (0341) 788992
        </div>
        <div class="doc-title">BERITA ACARA PEMUSNAHAN OBAT KADALUWARSA</div>
        <div class="doc-no">Nomor: BAP/{{ $adj->created_at->format('Ymd') }}/{{ $adj->id }}</div>
    </div>

    <div class="intro-text">
        Pada hari ini <strong>{{ $adj->created_at->isoFormat('dddd') }}</strong>, tanggal <strong>{{ $adj->created_at->isoFormat('D MMMM Y') }}</strong>, bertempat di Apotek Pakis Medika Utama, kami yang bertandatangan di bawah ini selaku Apoteker Penanggung Jawab Apotek disaksikan oleh saksi-saksi terkait, telah melakukan pemusnahan sediaan farmasi obat kadaluwarsa/rusak dengan rincian sebagai berikut:
    </div>

    <div class="table-title">Daftar Obat Yang Dimusnahkan:</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 35%;">Nama Sediaan / Obat</th>
                <th style="width: 20%;">Nomor Batch</th>
                <th style="width: 20%;">Tanggal Kadaluwarsa</th>
                <th style="width: 20%; text-align: right;">Jumlah Dimusnahkan</th>
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

    <div class="intro-text" style="margin-top: -10px;">
        Pemusnahan sediaan farmasi di atas dilakukan secara aman dengan metode penghancuran dan pelarutan sesuai dengan pedoman teknis pembuangan limbah obat yang dianjurkan oleh Badan Pengawas Obat dan Makanan (BPOM) RI guna mencegah penyalahgunaan obat.
    </div>

    <div class="intro-text">
        Demikian berita acara pemusnahan obat kadaluwarsa ini dibuat dengan sesungguhnya dalam rangkap 3 (tiga) untuk dikirimkan kepada Dinas Kesehatan Kabupaten/Kota dan Balai Besar Pengawas Obat dan Makanan (BBPOM) setempat.
    </div>

    <div class="footer-sig-grid">
        <div class="sig-box">
            <div class="role">Saksi I<br>(Petugas Apotek),</div>
            <div class="name">{{ $adj->user->name }}</div>
            <div class="nip">ID Staf: #{{ $adj->user->id }}</div>
        </div>
        <div class="sig-box">
            <div class="role">Saksi II<br>(Perwakilan Dinkes/BPOM),</div>
            <div class="name" style="text-decoration: none;">( ........................................ )</div>
            <div class="nip">NIP. .....................................</div>
        </div>
        <div class="sig-box">
            <div class="role">Apoteker Penanggung Jawab,<br>Apotek Pakis Medika Utama</div>
            <div class="name">apt. H. Ahmad Fauzi, S.Farm.</div>
            <div class="nip">SIPA: 19930812/SIPA-35.78/2026/2045</div>
        </div>
    </div>

</body>
</html>
