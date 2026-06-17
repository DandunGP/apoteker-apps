<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Monitoring Kadaluwarsa Apotek Pakis Medika Utama</title>
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
            padding: 15mm 20mm; /* Put margins back on the body */
        }

        /* ── Header ────────────────────────────────────────── */
        .doc-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #0B3E9C;
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

        .sum-box .s-lbl {
            font-size: 8.5px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
            color: #64748B;
        }

        .sum-box .s-val {
            font-size: 14px;
            font-weight: 900;
            color: #1E293B;
            margin-top: 2px;
        }

        .s-val-red  { color: #DC2626 !important; }
        .s-val-blue { color: #0B3E9C !important; }

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
            margin-bottom: 30px;
        }

        .data-table th {
            background: #EFF6FF;
            color: #0B3E9C;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            padding: 8px 10px;
            border: 1px solid #BFDBFE;
            text-align: left;
        }

        .data-table td {
            padding: 9px 10px;
            border: 1px solid #E2E8F0;
            vertical-align: middle;
            color: #334155;
            font-size: 10.5px;
        }

        .data-table tr:nth-child(even) td {
            background: #F8FAFC;
        }

        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .badge-red { background: #FEF2F2; color: #DC2626; border: 1px solid #FCA5A5; }
        .badge-warning { background: #FFFBEB; color: #D97706; border: 1px solid #FCD34D; }
        .badge-blue { background: #EFF6FF; color: #0B3E9C; border: 1px solid #BFDBFE; }

        /* ── Footer / Signatures ────────────────────────────── */
        .footer-sig {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            page-break-inside: avoid;
        }

        .sig-box {
            text-align: center;
            width: 180px;
            font-size: 10.5px;
        }

        .sig-box .role {
            font-weight: 800;
            color: #475569;
            margin-bottom: 50px;
        }

        .sig-box .name {
            font-weight: 900;
            color: #1E293B;
            text-decoration: underline;
        }

        .sig-box .nip {
            font-size: 9px;
            color: #64748B;
            margin-top: 2px;
            font-weight: 600;
        }

        .print-date {
            font-size: 9px;
            color: #94A3B8;
            font-weight: 700;
            text-align: center;
            margin-top: 30px;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="doc-header">
        <div class="header-left">
            <div class="pharmacy-name">APOTEK PAKIS MEDIKA UTAMA</div>
            <div class="pharmacy-sub">CPMS v2.4 (Clinical Precision Management System)</div>
        </div>
        <div class="header-right">
            <span class="doc-title">LAPORAN MONITORING KADALUWARSA</span>
            <div>Dokumen Resmi Kontrol Inventaris Gudang</div>
        </div>
    </div>

    <!-- Summary Box Banner -->
    <div class="summary-banner">
        <div class="sum-box">
            <div class="s-lbl">Total Item Terfilter</div>
            <div class="s-val">{{ count($items) }} Item</div>
        </div>
        <div class="sum-box">
            <div class="s-lbl">Sudah Kadaluwarsa</div>
            <div class="s-val s-val-red">{{ $metrics['sudah_kadaluwarsa'] }} Item</div>
        </div>
        <div class="sum-box">
            <div class="s-lbl">Kadaluwarsa &lt; 3 Bulan</div>
            <div class="s-val s-val-red">{{ $metrics['hampir_kadaluwarsa'] }} Item</div>
        </div>
        <div class="sum-box">
            <div class="s-lbl">Kadaluwarsa &lt; 6 Bulan</div>
            <div class="s-val s-val-blue">{{ $metrics['akan_kadaluwarsa_6'] }} Item</div>
        </div>
    </div>

    <!-- Filter Bar Info -->
    <div class="filter-bar">
        <div>Filter Status: <span>{{ strtoupper($statusFilter) }}</span></div>
        <div>Kategori: <span>{{ strtoupper($categoryFilter) }}</span></div>
        <div>Urutan: <span>{{ strtoupper($sortOption) }}</span></div>
        @if($searchQuery)
            <div>Pencarian: <span>"{{ $searchQuery }}"</span></div>
        @endif
    </div>

    <!-- Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 25%;">Nama Obat / Kode</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Stok Saat Ini</th>
                <th style="width: 12%;">Stok Minimum</th>
                <th style="width: 12%;">No. Batch</th>
                <th style="width: 13%;">Tanggal Kadaluwarsa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        <strong style="color: #1E293B; display:block;">{{ $item->nama }}</strong>
                        <span style="font-size: 9px; color: #64748B; font-weight: 600;">{{ $item->kode }}</span>
                    </td>
                    <td style="font-weight: 700; color: #475569;">{{ $item->kategori }}</td>
                    <td>
                        @if($item->is_kritis)
                            <strong style="color: #DC2626;">{{ $item->total_stock }} {{ $item->satuan }} (KRITIS)</strong>
                        @else
                            <span style="font-weight: 700; color: #1E293B;">{{ $item->total_stock }} {{ $item->satuan }}</span>
                        @endif
                    </td>
                    <td style="color: #64748B; font-weight: 600;">{{ $item->min_stok }} {{ $item->satuan }}</td>
                    <td style="font-weight: 800; color: #475569;">{{ $item->no_batch }}</td>
                    <td>
                        @if($item->no_batch === '-')
                            <span style="color: #94A3B8; font-weight: 700;">-</span>
                        @elseif($item->days_to_expiry !== null && $item->days_to_expiry <= 30)
                            <span class="badge badge-red">{{ $item->tanggal_kadaluwarsa }}</span>
                        @elseif($item->days_to_expiry !== null && $item->days_to_expiry <= 90)
                            <span class="badge badge-warning">{{ $item->tanggal_kadaluwarsa }}</span>
                        @else
                            <span class="badge badge-blue">{{ $item->tanggal_kadaluwarsa }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94A3B8; padding: 20px;">
                        Tidak ada data obat yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Signature Footer -->
    <div class="footer-sig">
        <div class="sig-box">
            <div class="role">Staff Gudang Farmasi,</div>
            <div class="name">{{ auth()->user()->name }}</div>
            <div class="nip">ID Staf: #{{ auth()->user()->id }}</div>
        </div>
        <div class="sig-box">
            <div class="role">Kepala Apoteker Pakis,</div>
            <div class="name">apt. H. Ahmad Fauzi, S.Farm.</div>
            <div class="nip">SIPA: 19930812/SIPA-35.78/2026/2045</div>
        </div>
    </div>

    <!-- Print Timestamp -->
    <div class="print-date">
        Laporan dicetak otomatis pada {{ date('d M Y, H:i') }} WIB melalui System CPMS
    </div>

</body>
</html>
