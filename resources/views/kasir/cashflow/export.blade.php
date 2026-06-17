<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Arus Kas — {{ $periodLabel }}</title>
    <style>
        @page {
            size: A4;
            margin: 0; /* Hides default browser header and footer */
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px;
            color: #1E293B;
            background: #fff;
            line-height: 1.5;
            padding: 15mm 20mm; /* Put printable margins back on the body */
        }

        /* ── Header ────────────────────────────────────────── */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0F62FE;
            padding-bottom: 10px;
            margin-bottom: 14px;
        }

        .header-left .pharmacy-name {
            font-size: 18px;
            font-weight: 900;
            color: #0F3071;
            letter-spacing: -0.5px;
        }

        .header-left .pharmacy-sub {
            font-size: 10px;
            color: #64748B;
            font-weight: 600;
            margin-top: 2px;
        }

        .header-right {
            text-align: right;
            font-size: 10px;
            color: #64748B;
            font-weight: 600;
        }

        .header-right .doc-title {
            font-size: 13px;
            font-weight: 900;
            color: #0F3071;
            display: block;
            margin-bottom: 2px;
        }

        /* ── Summary banner ─────────────────────────────────── */
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

        .sum-box-blue {
            background: #0F62FE;
            border-color: #0F62FE;
            color: white;
        }

        .sum-box .s-lbl {
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #64748B;
        }

        .sum-box-blue .s-lbl { color: #BFDBFE; }

        .sum-box .s-val {
            font-size: 14px;
            font-weight: 900;
            color: #1E293B;
            margin-top: 2px;
        }

        .sum-box-blue .s-val { color: white; }
        .s-val-red  { color: #EF4444 !important; }
        .s-val-green { color: #059669 !important; }

        /* ── Filter info bar ────────────────────────────────── */
        .filter-bar {
            background: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 4px;
            padding: 7px 12px;
            margin-bottom: 14px;
            display: flex;
            gap: 20px;
            font-size: 10px;
            font-weight: 700;
            color: #475569;
        }

        .filter-bar span { color: #1E293B; font-weight: 900; }

        /* ── Table ──────────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead tr {
            background: #F1F5F9;
        }

        .data-table th {
            padding: 8px 10px;
            text-align: left;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #475569;
            border-bottom: 2px solid #CBD5E1;
        }

        .data-table td {
            padding: 9px 10px;
            font-size: 10.5px;
            border-bottom: 1px solid #F1F5F9;
            vertical-align: top;
        }

        .data-table tbody tr:nth-child(even) td {
            background: #FAFBFC;
        }

        .pill {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 8px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        .pill-masuk  { background: #DCFCE7; color: #15803D; }
        .pill-keluar { background: #FEE2E2; color: #B91C1C; }

        .cat-sub {
            font-size: 8.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .3px;
            margin-top: 2px;
        }

        .cat-penjualan  { color: #3B82F6; }
        .cat-inkaso     { color: #F59E0B; }
        .cat-operasional{ color: #8B5CF6; }
        .cat-modal      { color: #059669; }

        .nom-masuk  { font-weight: 800; color: #1E293B; }
        .nom-keluar { font-weight: 800; color: #EF4444; }

        /* ── Footer ─────────────────────────────────────────── */
        .doc-footer {
            margin-top: 18px;
            border-top: 1px solid #E2E8F0;
            padding-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 9.5px;
            color: #64748B;
        }

        .signature-block {
            text-align: center;
            font-size: 9.5px;
            font-weight: 700;
            color: #1E293B;
        }

        .signature-block .sig-line {
            border-top: 1px solid #64748B;
            width: 130px;
            margin: 40px auto 4px;
        }

        @media print {
            body { font-size: 10px; }
        }
    </style>
</head>
<body>

    <!-- Document body (printable) -->
    <div>

        <!-- Header -->
        <div class="doc-header">
            <div class="header-left">
                <div class="pharmacy-name">{{ config('app.pharmacy_name', 'Apotek Pakis Medika Utama') }}</div>
                <div class="pharmacy-sub">{{ config('app.pharmacy_address', 'Jl. Pakis Raya No. 1, Surabaya') }} | Telp: {{ config('app.pharmacy_phone', '(031) 123-4567') }}</div>
            </div>
            <div class="header-right">
                <span class="doc-title">Laporan Arus Kas</span>
                Periode: {{ $periodLabel }}<br>
                Dicetak: {{ now()->format('d M Y, H:i') }} WIB<br>
                Oleh: {{ auth()->user()->name }}
            </div>
        </div>

        <!-- Summary banner -->
        <div class="summary-banner">
            <div class="sum-box">
                <div class="s-lbl">Total Uang Masuk</div>
                <div class="s-val s-val-green">{{ $stats['total_masuk'] }}</div>
            </div>
            <div class="sum-box">
                <div class="s-lbl">Total Uang Keluar</div>
                <div class="s-val s-val-red">{{ $stats['total_keluar'] }}</div>
            </div>
            <div class="sum-box sum-box-blue">
                <div class="s-lbl">Saldo Kas</div>
                <div class="s-val">{{ $stats['saldo'] }}</div>
            </div>
            <div class="sum-box">
                <div class="s-lbl">Total Entri</div>
                <div class="s-val" style="color:#0F62FE;">{{ $allEntries->count() }}</div>
            </div>
        </div>

        <!-- Filter info -->
        <div class="filter-bar">
            <div>Periode: <span>{{ $periodLabel }}</span></div>
            <div>Tipe: <span>{{ $type === 'semua' ? 'Semua Tipe' : ucfirst($type) }}</span></div>
            <div>Dari: <span>{{ $rangeFrom->format('d M Y') }}</span></div>
            <div>Sampai: <span>{{ $rangeTo->format('d M Y') }}</span></div>
        </div>

        <!-- Data table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width:5%">No</th>
                    <th style="width:15%">Tanggal & Waktu</th>
                    <th style="width:10%">Jenis</th>
                    <th style="width:45%">Keterangan</th>
                    <th style="width:20%;text-align:right">Nominal</th>
                    <th style="width:5%;text-align:center">Src</th>
                </tr>
            </thead>
            <tbody>
                @forelse($allEntries as $i => $cf)
                    <tr>
                        <td style="color:#94A3B8;font-weight:700;">{{ $i + 1 }}</td>
                        <td style="color:#475569;font-weight:600;white-space:nowrap;">{{ $cf->time_formatted }}</td>
                        <td>
                            <span class="pill {{ $cf->type === 'masuk' ? 'pill-masuk' : 'pill-keluar' }}">
                                {{ strtoupper($cf->type) }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight:700;color:#1E293B;">{{ $cf->keterangan }}</div>
                            <div class="cat-sub
                                @if($cf->category === 'LAPORAN_PENJUALAN') cat-penjualan
                                @elseif($cf->category === 'INKASO_SUPPLIER') cat-inkaso
                                @elseif($cf->category === 'OPERASIONAL') cat-operasional
                                @else cat-modal @endif">
                                {{ $cf->category_label }}
                                — {{ isset($cf->is_auto) && $cf->is_auto ? 'Otomatis' : 'Manual' }}
                            </div>
                        </td>
                        <td style="text-align:right">
                            <span class="{{ $cf->type === 'masuk' ? 'nom-masuk' : 'nom-keluar' }}">
                                {{ $cf->nominal_formatted }}
                            </span>
                        </td>
                        <td style="text-align:center;font-size:9px;color:#94A3B8;font-weight:700;">
                            {{ isset($cf->is_auto) && $cf->is_auto ? 'SYS' : 'MNL' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;padding:2rem;color:#94A3B8;">
                            Tidak ada data untuk periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Footer -->
        <div class="doc-footer">
            <div>
                <div style="font-weight:700;color:#1E293B;margin-bottom:3px;">{{ config('app.pharmacy_name', 'Apotek Pakis Medika Utama') }}</div>
                <div>Dokumen ini digenerate otomatis oleh sistem</div>
                <div>pada {{ now()->format('d M Y \p\u\k\u\l H:i') }} WIB</div>
            </div>
            <div class="signature-block">
                <div class="sig-line"></div>
                <div>{{ auth()->user()->name }}</div>
                <div style="font-size:8.5px;color:#64748B;margin-top:2px;">Kasir / Penanggung Jawab</div>
            </div>
        </div>

    </div>
</body>
</html>
