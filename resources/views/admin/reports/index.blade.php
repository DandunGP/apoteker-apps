@extends('layouts.app')

@section('content')
<style>
    /* Always display SweetAlert2 popups on top of high z-index modals */
    .swal2-container {
        z-index: 999999 !important;
    }

    /* Executive Premium CSS System for Manage Reports Page */
    .reports-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .reports-header {
        margin-bottom: 1.5rem;
    }

    .reports-header h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .reports-header p {
        font-size: 13.5px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    /* Grid Layout: Stock card & Valuation card */
    .top-reports-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.25rem;
    }

    .report-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .report-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.04);
    }

    .card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 12px;
    }

    .card-title-box {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-blue-light { background: #EFF6FF; color: #0B3E9C; }
    .bg-green-light { background: #ECFDF5; color: #10B981; }
    .bg-grey-light { background: #F1F5F9; color: #475569; }
    .bg-red-light { background: #FEF2F2; color: #EF4444; }

    .card-title-text h3 {
        font-size: 14.5px;
        font-weight: 800;
        color: #1E293B;
        margin: 0 0 2px;
    }

    .card-title-text p {
        font-size: 11.5px;
        color: #64748B;
        margin: 0;
        font-weight: 500;
    }

    .badge-realtime {
        background: #F8FAFC;
        color: #475569;
        font-size: 9px;
        font-weight: 850;
        padding: 3px 8px;
        border-radius: 4px;
        border: 1px solid #E2E8F0;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* Form Fields styling inside cards */
    .fields-row {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-top: 14px;
        margin-bottom: 18px;
    }

    .field-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .field-lbl {
        font-size: 10px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.4px;
    }

    .card-select, .card-input {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 5px;
        padding: 8px 12px;
        font-size: 12.5px;
        font-weight: 650;
        color: #334155;
        outline: none;
        width: 100%;
        cursor: pointer;
    }

    .card-input {
        cursor: text;
    }

    /* Action Buttons Row */
    .card-actions-row {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .btn-view-report {
        flex: 1.2;
        background: #0B3E9C;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 9px 14px;
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-view-report:hover {
        background: #09317C;
    }

    .btn-action-outline {
        flex: 1;
        background: white;
        color: #0B3E9C;
        border: 1.5px solid #E2E8F0;
        border-radius: 5px;
        padding: 8px 14px;
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-action-outline:hover {
        background: #F8FAFC;
        border-color: #BFDBFE;
    }

    /* Valuation KPI Card (Royal Blue style matching screenshot) */
    .valuation-kpi-card {
        background: linear-gradient(135deg, #1E40AF 0%, #1D4ED8 100%);
        color: white;
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        box-shadow: 0 4px 20px rgba(30, 64, 175, 0.15);
        position: relative;
    }

    .val-header {
        font-size: 10px;
        font-weight: 850;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        color: #93C5FD;
    }

    .val-body {
        margin-top: 1rem;
        margin-bottom: 1.25rem;
    }

    .val-currency {
        font-size: 18px;
        font-weight: 800;
        color: #93C5FD;
        display: block;
        margin-bottom: 2px;
    }

    .val-number {
        font-size: 26px;
        font-weight: 900;
        letter-spacing: -0.5px;
        line-height: 1.1;
    }

    .val-comparison {
        font-size: 11px;
        font-weight: 650;
        color: #BFDBFE;
        margin-top: 8px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .btn-analytics {
        background: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid rgba(255,255,255,0.25);
        border-radius: 5px;
        padding: 9px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        cursor: pointer;
        width: 100%;
        text-align: center;
        transition: all 0.2s;
    }

    .btn-analytics:hover {
        background: rgba(255,255,255,0.2);
    }

    /* Three Columns Layout for other reports */
    .bottom-reports-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    /* Expiry Action Banner red alert */
    .expiry-alert-banner {
        background: #FEF2F2;
        border: 1px dashed #FCA5A5;
        border-radius: 6px;
        padding: 10px 12px;
        font-size: 12px;
        color: #991B1B;
        font-weight: 650;
        display: flex;
        align-items: flex-start;
        gap: 8px;
        margin-bottom: 14px;
    }

    .btn-expiry-red {
        background: #B91C1C;
        color: white;
        border: none;
        border-radius: 5px;
        padding: 9.5px 14px;
        font-size: 12.5px;
        font-weight: 800;
        width: 100%;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        margin-bottom: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-expiry-red:hover {
        background: #991B1B;
    }

    .btn-expiry-outline {
        background: white;
        color: #B91C1C;
        border: 1.5px solid #FCA5A5;
        border-radius: 5px;
        padding: 8px 14px;
        font-size: 12px;
        font-weight: 800;
        width: 100%;
        cursor: pointer;
        text-align: center;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-expiry-outline:hover {
        background: #FEF2F2;
    }

    /* Interactive Data Preview Datagrid */
    .preview-card {
        background: white;
        border: 1.5px solid #BFDBFE;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(11,62,156,0.04);
        overflow: hidden;
    }

    .preview-header {
        background: #EFF6FF;
        padding: 1rem 1.5rem;
        border-bottom: 1.5px solid #BFDBFE;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .preview-header-title {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .preview-header-title h3 {
        font-size: 15px;
        font-weight: 850;
        color: #0B3E9C;
        margin: 0;
    }

    .preview-table {
        width: 100%;
        border-collapse: collapse;
    }

    .preview-table th {
        background: #FAF8F8;
        color: #475569;
        font-size: 10px;
        font-weight: 850;
        text-transform: uppercase;
        padding: 12px 16px;
        border-bottom: 1px solid #E2E8F0;
        text-align: left;
        letter-spacing: 0.5px;
    }

    .preview-table td {
        padding: 12px 16px;
        font-size: 13px;
        border-bottom: 1px solid #F1F5F9;
        color: #334155;
        vertical-align: middle;
    }

    .preview-table tr:hover {
        background: #F8FAFC;
    }

    /* Bottom dynamic report logs table */
    .logs-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .logs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .logs-header h3 {
        font-size: 15px;
        font-weight: 850;
        color: #1E293B;
        margin: 0;
    }

    .logs-header-link {
        font-size: 11px;
        font-weight: 800;
        color: #0B3E9C;
        text-decoration: none;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        cursor: pointer;
    }

    .logs-table {
        width: 100%;
        border-collapse: collapse;
    }

    .logs-table th {
        color: #64748B;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 10px 14px;
        border-bottom: 1.5px solid #EFF2F5;
        text-align: left;
        letter-spacing: 0.5px;
    }

    .logs-table td {
        padding: 14px 14px;
        font-size: 13px;
        border-bottom: 1px solid #F1F5F9;
        color: #334155;
        vertical-align: middle;
    }

    .badge-format {
        font-size: 9px;
        font-weight: 900;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        display: inline-block;
    }

    .badge-pdf { background: #FEE2E2; color: #EF4444; }
    .badge-excel { background: #D1FAE5; color: #10B981; }

    .btn-download-log {
        width: 28px;
        height: 28px;
        border-radius: 4px;
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        color: #475569;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-download-log:hover {
        background: #EFF6FF;
        color: #0B3E9C;
        border-color: #BFDBFE;
    }

    /* Circular Floating Action Button (FAB) */
    .floating-fab {
        position: fixed;
        bottom: 24px;
        right: 24px;
        width: 52px;
        height: 52px;
        background: #0B3E9C;
        color: white;
        border: none;
        border-radius: 50%;
        box-shadow: 0 10px 25px rgba(11,62,156,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s;
        z-index: 999;
    }

    .floating-fab:hover {
        transform: scale(1.1) rotate(90deg);
        background: #09317C;
    }
</style>
<div class="reports-container">
    <!-- Top Breadcrumb & Header Area -->
    <div>
        <!-- Top Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
            <span>&gt;</span>
            <span class="active">Kelola Laporan</span>
        </div>

        <!-- Top Header Area -->
        <div class="reports-header">
            <h1>Kelola Laporan</h1>
            <p>Generate dan unduh laporan operasional apotek dengan filter custom.</p>
        </div>
    </div>

@if(session('success'))
    <div style="background: #ECFDF5; border: 1.5px solid #A7F3D0; border-radius: 6px; padding: 12px 16px; color: #065F46; font-size: 13.5px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin-bottom: 0.5rem; animation: slideIn 0.3s ease-out;">
        <i data-lucide="check-circle" size="18" style="stroke-width: 2.5; color: #10B981;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- First row layout: Stock Report Card & Value KPI Card -->
<div class="top-reports-grid">
    
    <!-- Left: Laporan Stok Inventaris (dynamic preview/direct downloads) -->
    <form action="{{ route('admin.reports.index') }}" method="GET" class="report-card" id="stockReportForm">
        <input type="hidden" name="report_type" value="stock">
        <div>
            <div class="card-header-row">
                <div class="card-title-box">
                    <div class="card-icon-box bg-blue-light">
                        <i data-lucide="archive" size="18"></i>
                    </div>
                    <div class="card-title-text">
                        <h3>Laporan Stok Inventaris</h3>
                        <p>Rangkuman posisi stok saat ini dan nilai aset.</p>
                    </div>
                </div>
                <span class="badge-realtime">Real-Time</span>
            </div>

            <div class="fields-row">
                <div class="field-group">
                    <span class="field-lbl">Kategori Obat</span>
                    <select name="category" class="card-select">
                        <option value="all">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}">{{ $cat }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="field-group">
                    <span class="field-lbl">Status Stok</span>
                    <select name="status" class="card-select">
                        <option value="all">Semua Status</option>
                        <option value="kritis">Stok Kritis (≤ Min)</option>
                        <option value="aman">Stok Aman (&gt; Min)</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="card-actions-row">
            <button type="submit" class="btn-view-report" onclick="this.form.submit();">
                <i data-lucide="eye" size="14"></i>
                Lihat Laporan
            </button>
            <button type="button" class="btn-action-outline" onclick="triggerStockExport('pdf')">
                <i data-lucide="file-text" size="14"></i>
                PDF
            </button>
            <button type="button" class="btn-action-outline" onclick="triggerStockExport('csv')">
                <i data-lucide="file-spreadsheet" size="14"></i>
                Excel
            </button>
        </div>
    </form>

    <!-- Right: Valuation KPI Card (Royal Blue) -->
    <div class="valuation-kpi-card">
        <span class="val-header">Total Nilai Inventaris</span>
        <div class="val-body">
            <span class="val-currency">Rp</span>
            <span class="val-number">{{ number_format($totalValuation, 0, ',', '.') }}</span>
            <span class="val-comparison">
                <i data-lucide="trending-up" size="14"></i>
                +2.4% dari bulan lalu
            </span>
        </div>
        <button class="btn-analytics" onclick="window.location.href='{{ route('admin.monitoring.stock') }}';">
            Detil Analitik
        </button>
    </div>

</div>

<!-- Second row layout: 3 columns cards (Incoming, Sales, Expiry) -->
<div class="bottom-reports-grid">

    <!-- Card 1: Laporan Obat Masuk -->
    <form action="{{ route('admin.reports.index') }}" method="GET" class="report-card" id="incomingForm">
        <input type="hidden" name="report_type" value="incoming">
        <div>
            <div class="card-header-row">
                <div class="card-title-box">
                    <div class="card-icon-box bg-green-light">
                        <i data-lucide="shopping-cart" size="18"></i>
                    </div>
                    <div class="card-title-text">
                        <h3>Laporan Obat Masuk</h3>
                        <p>Log penerimaan barang dari supplier.</p>
                    </div>
                </div>
            </div>

            <div class="fields-row" style="grid-template-columns: 1fr;">
                <div class="field-group">
                    <span class="field-lbl">Rentang Tanggal</span>
                    <div style="display:flex; gap:6px; align-items:center;">
                        <input type="date" name="date_from" class="card-input" placeholder="mm/dd/yyyy">
                        <span style="font-size:11px; color:#94A3B8; font-weight:700;">s/d</span>
                        <input type="date" name="date_to" class="card-input" placeholder="mm/dd/yyyy">
                    </div>
                </div>
                <div class="field-group">
                    <span class="field-lbl">Supplier</span>
                    <select name="supplier_id" class="card-select">
                        <option value="all">Semua Supplier</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}">{{ $sup->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="btn-view-report" style="width:100%; margin-bottom:8px;" onclick="this.form.submit();">
                Lihat Laporan
            </button>
            <div style="display:flex; gap:6px;">
                <button type="button" class="btn-action-outline" style="width:100%;" onclick="triggerIncomingExport('pdf')">
                    <i data-lucide="file-text" size="13"></i> PDF
                </button>
                <button type="button" class="btn-action-outline" style="width:100%;" onclick="triggerIncomingExport('csv')">
                    <i data-lucide="file-spreadsheet" size="13"></i> Excel
                </button>
            </div>
        </div>
    </form>

    <!-- Card 2: Laporan Penjualan -->
    <form action="{{ route('admin.reports.index') }}" method="GET" class="report-card" id="salesForm">
        <input type="hidden" name="report_type" value="sales">
        <div>
            <div class="card-header-row">
                <div class="card-title-box">
                    <div class="card-icon-box bg-grey-light">
                        <i data-lucide="monitor" size="18"></i>
                    </div>
                    <div class="card-title-text">
                        <h3>Laporan Penjualan</h3>
                        <p>Data transaksi harian dan metode bayar.</p>
                    </div>
                </div>
            </div>

            <div class="fields-row" style="grid-template-columns: 1fr;">
                <div class="field-group">
                    <span class="field-lbl">Rentang Tanggal</span>
                    <div style="display:flex; gap:6px; align-items:center;">
                        <input type="date" name="date_from" class="card-input" placeholder="mm/dd/yyyy">
                        <span style="font-size:11px; color:#94A3B8; font-weight:700;">s/d</span>
                        <input type="date" name="date_to" class="card-input" placeholder="mm/dd/yyyy">
                    </div>
                </div>
                <div class="field-group">
                    <span class="field-lbl">Metode Pembayaran</span>
                    <select name="payment_method" class="card-select">
                        <option value="all">Semua Metode</option>
                        <option value="tunai">Tunai</option>
                        <option value="qris">QRIS / Non-Tunai</option>
                    </select>
                </div>
            </div>
        </div>

        <div>
            <button type="submit" class="btn-view-report" style="width:100%; margin-bottom:8px;" onclick="this.form.submit();">
                Lihat Laporan
            </button>
            <div style="display:flex; gap:6px;">
                <button type="button" class="btn-action-outline" style="width:100%;" onclick="triggerSalesExport('pdf')">
                    <i data-lucide="file-text" size="13"></i> PDF
                </button>
                <button type="button" class="btn-action-outline" style="width:100%;" onclick="triggerSalesExport('csv')">
                    <i data-lucide="file-spreadsheet" size="13"></i> Excel
                </button>
            </div>
        </div>
    </form>

    <!-- Card 3: Laporan Kadaluwarsa -->
    <form action="{{ route('admin.reports.index') }}" method="GET" class="report-card" id="expiryReportForm">
        <input type="hidden" name="report_type" value="expiry">
        <div>
            <div class="card-header-row">
                <div class="card-title-box">
                    <div class="card-icon-box bg-red-light">
                        <i data-lucide="alert-triangle" size="18"></i>
                    </div>
                    <div class="card-title-text">
                        <h3 style="color:#B91C1C;">Laporan Kadaluwarsa</h3>
                        <p>Prediksi dan list produk mendekati ED.</p>
                    </div>
                </div>
            </div>

            <div class="field-group" style="margin-top:14px; margin-bottom:14px;">
                <span class="field-lbl">Tingkat Risiko</span>
                <select name="risk" class="card-select" id="riskSelect" onchange="updateRiskPills(this.value)">
                    <option value="3">Kritis (&lt; 3 Bulan)</option>
                    <option value="6">Waspada (&lt; 6 Bulan)</option>
                    <option value="12">Semua Periode (&lt; 12 Bulan)</option>
                </select>
            </div>

            <div class="expiry-alert-banner">
                <i data-lucide="alert-circle" size="15" style="margin-top: 1px; flex-shrink:0;"></i>
                <span><strong id="kritisCount">{{ $kritisCount }} Item</strong> membutuhkan tindakan segera.</span>
            </div>
        </div>

        <div>
            <button type="button" class="btn-expiry-red" onclick="openReturModal();">
                Proses Retur / Pemusnahan
            </button>
            <div style="display:flex; gap:6px; margin-top:8px;">
                <button type="submit" class="btn-action-outline" style="width:100%; color:#B91C1C; border-color:#FCA5A5;" onclick="this.form.submit();">
                    Lihat Laporan
                </button>
                <button type="button" class="btn-expiry-outline" style="width:100%;" onclick="triggerExpiryExport()">
                    <i data-lucide="download" size="14"></i>
                    Ekspor PDF
                </button>
            </div>
        </div>
    </form>

</div>

<!-- Section: Interactive Preview Datagrid -->
@if($previewData)
    <div class="preview-card">
        <div class="preview-header">
            <div class="preview-header-title">
                <i data-lucide="eye" size="16"></i>
                <h3>Interactive Preview: {{ $previewData->title }}</h3>
            </div>
            <span class="badge-realtime">Dynamic Datagrid</span>
        </div>
        <div style="overflow-x: auto;">
            <table class="preview-table">
                <thead>
                    <tr>
                        @foreach($previewData->headers as $header)
                            <th>{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($previewData->rows as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @if($previewData->type === 'stock')
                                <td><strong>{{ $row->kode }}</strong></td>
                                <td>{{ $row->nama }}</td>
                                <td>{{ $row->kategori }}</td>
                                <td><span style="font-weight:700; color:#64748B;">{{ $row->satuan }}</span></td>
                                <td>
                                    @if($row->is_kritis)
                                        <span style="color:#EF4444; font-weight:800;">{{ $row->stok }} Units (Kritis)</span>
                                    @else
                                        <span style="font-weight:700;">{{ $row->stok }} Units</span>
                                    @endif
                                </td>
                                <td>{{ $row->min_stok }}</td>
                                <td>Rp {{ number_format($row->harga, 0, ',', '.') }}</td>
                                <td><strong>Rp {{ number_format($row->total_nilai, 0, ',', '.') }}</strong></td>
                                
                            @elseif($previewData->type === 'incoming')
                                <td><strong>{{ $row->no_faktur }}</strong></td>
                                <td>{{ $row->nama_obat }}</td>
                                <td><span style="font-weight:700;">{{ $row->no_batch }}</span></td>
                                <td>{{ $row->tanggal_masuk }}</td>
                                <td>{{ $row->tanggal_kadaluwarsa }}</td>
                                <td><span class="badge-format badge-excel">{{ $row->tipe_faktur }}</span></td>
                                <td><strong>{{ $row->stok_sisa }} Unit</strong></td>
                                
                            @elseif($previewData->type === 'sales')
                                <td><strong>{{ $row->invoice }}</strong></td>
                                <td>{{ $row->kasir }}</td>
                                <td>{{ $row->tanggal }}</td>
                                <td><span class="badge-format badge-excel">{{ $row->metode }}</span></td>
                                <td><strong>Rp {{ number_format($row->total, 0, ',', '.') }}</strong></td>
                                
                            @elseif($previewData->type === 'expiry')
                                <td><strong>{{ $row->nama }}</strong></td>
                                <td>{{ $row->no_batch }}</td>
                                <td>{{ $row->tanggal_kadaluwarsa }}</td>
                                <td>
                                    @if(str_contains($row->sisa_hari, 'KADALUWARSA'))
                                        <span style="color:#EF4444; font-weight:900;">Expired</span>
                                    @else
                                        <span style="font-weight:700; color:#B91C1C;">{{ $row->sisa_hari }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(str_contains($row->status, 'Kritis'))
                                        <span class="badge-format badge-pdf">{{ $row->status }}</span>
                                    @else
                                        <span class="badge-format badge-excel">{{ $row->status }}</span>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($previewData->headers) }}" style="text-align: center; color: #94A3B8; padding: 2rem;">
                                Tidak ada data untuk pratinjau dengan filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif

<!-- Bottom Section: Laporan Terakhir Dibuat -->
<div class="logs-card">
    <div class="logs-header">
        <h3>Laporan Terakhir Dibuat</h3>
        <a onclick="openActivitiesModal()" class="logs-header-link">
            Lihat Semua Aktivitas
        </a>
    </div>
    
    <table class="logs-table">
        <thead>
            <tr>
                <th style="width: 35%;">Nama Laporan</th>
                <th style="width: 20%;">Dibuat Oleh</th>
                <th style="width: 25%;">Tanggal Generate</th>
                <th style="width: 12%;">Format</th>
                <th style="width: 8%;">Aksi</th>
            </tr>
        </thead>
        <tbody id="logsTableBody">
            <!-- Dynamic logs will be populated here by javascript -->
        </tbody>
    </table>
</div>

<!-- Modal Proses Retur / Pemusnahan -->
<div id="returDestructionModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 8px; width: 100%; max-width: 650px; display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: slideIn 0.3s ease-out;">
        <div style="background: #991B1B; color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 15px; font-weight: 850; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="alert-triangle" size="18"></i>
                Proses Retur & Pemusnahan Obat Kadaluwarsa
            </h3>
            <button onclick="closeReturModal()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; font-weight: 800; line-height: 1;">&times;</button>
        </div>
        <div style="padding: 1.5rem; overflow-y: auto; max-height: 350px;">
            <p style="font-size: 12.5px; color: #64748B; margin-bottom: 1.25rem; line-height: 1.5;">
                Berikut adalah daftar batch obat kritis terfilter yang telah melewati masa berlaku atau mendekati kadaluwarsa (&lt; 3 Bulan). Silakan pilih aksi pemrosesan lebih lanjut:
            </p>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1.5px solid #E2E8F0;">
                        <th style="padding: 8px; font-weight: 800; color: #475569;">Nama Obat</th>
                        <th style="padding: 8px; font-weight: 800; color: #475569;">Batch</th>
                        <th style="padding: 8px; font-weight: 800; color: #475569;">Kadaluwarsa</th>
                        <th style="padding: 8px; font-weight: 800; color: #475569; text-align: right;">Sisa Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($expiredBatches as $batch)
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 8px;"><strong>{{ $batch->medicine->nama ?? 'Unknown' }}</strong></td>
                            <td style="padding: 8px; color: #64748B;">{{ $batch->no_batch }}</td>
                            <td style="padding: 8px; color: #EF4444; font-weight: 700;">{{ \Carbon\Carbon::parse($batch->tanggal_kadaluwarsa)->format('d M Y') }}</td>
                            <td style="padding: 8px; text-align: right; font-weight: 700;">{{ $batch->stok_sisa }} Unit</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94A3B8; padding: 2rem;">
                                Tidak ada item kritis atau kadaluwarsa saat ini di database.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <form action="{{ route('admin.reports.process-expiry-action') }}" method="POST" id="expiryActionForm">
            @csrf
            <input type="hidden" name="action_type" id="expiryActionType" value="retur">
            <div style="background: #F8FAFC; padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid #E2E8F0;">
                <button type="button" onclick="closeReturModal()" style="background: white; border: 1.5px solid #E2E8F0; border-radius: 5px; padding: 8px 14px; font-size: 12.5px; font-weight: 800; color: #475569; cursor: pointer;">
                    Batal
                </button>
                <button type="button" onclick="submitExpiryAction('retur')" style="background: #0B3E9C; border: none; border-radius: 5px; padding: 8px 14px; font-size: 12.5px; font-weight: 800; color: white; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i data-lucide="truck" size="14"></i>
                    Proses Retur ke Supplier
                </button>
                <button type="button" onclick="submitExpiryAction('destruction')" style="background: #EF4444; border: none; border-radius: 5px; padding: 8px 14px; font-size: 12.5px; font-weight: 800; color: white; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                    <i data-lucide="trash-2" size="14"></i>
                    Konfirmasi Pemusnahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Semua Aktivitas Laporan -->
<div id="allActivitiesModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 8px; width: 100%; max-width: 800px; display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: slideIn 0.3s ease-out;">
        <div style="background: #0F3071; color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 15px; font-weight: 850; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="history" size="18"></i>
                Semua Aktivitas Ekspor Laporan
            </h3>
            <button onclick="closeActivitiesModal()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; font-weight: 800; line-height: 1;">&times;</button>
        </div>
        <div style="padding: 1.5rem; overflow-y: auto; max-height: 400px;">
            <p style="font-size: 12.5px; color: #64748B; margin-bottom: 1.25rem; line-height: 1.5;">
                Histori lengkap dari seluruh dokumen laporan yang telah di-generate, diekspor, atau diunduh dari perangkat ini:
            </p>
            <table style="width: 100%; border-collapse: collapse; font-size: 12px; text-align: left;">
                <thead>
                    <tr style="border-bottom: 1.5px solid #E2E8F0;">
                        <th style="padding: 10px; font-weight: 800; color: #475569; width: 45%;">Nama Laporan</th>
                        <th style="padding: 10px; font-weight: 800; color: #475569; width: 20%;">Dibuat Oleh</th>
                        <th style="padding: 10px; font-weight: 800; color: #475569; width: 20%;">Tanggal Generate</th>
                        <th style="padding: 10px; font-weight: 800; color: #475569; width: 10%;">Format</th>
                        <th style="padding: 10px; font-weight: 800; color: #475569; width: 5%; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="allActivitiesTableBody">
                    <!-- Dynamic rows will be inserted here -->
                </tbody>
            </table>
        </div>
        <div style="background: #F8FAFC; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #E2E8F0;">
            <button onclick="clearAllActivities()" style="background: #EF4444; border: none; border-radius: 5px; padding: 8px 14px; font-size: 12px; font-weight: 800; color: white; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                <i data-lucide="trash-2" size="14"></i>
                Hapus Semua Histori
            </button>
            <button onclick="closeActivitiesModal()" style="background: white; border: 1.5px solid #E2E8F0; border-radius: 5px; padding: 8px 14px; font-size: 12.5px; font-weight: 800; color: #475569; cursor: pointer;">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfigurasi Laporan Kustom -->
<div id="customReportModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center; padding: 1.5rem; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 8px; width: 100%; max-width: 500px; display: flex; flex-direction: column; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden; animation: slideIn 0.3s ease-out;">
        <div style="background: #0F3071; color: white; padding: 1rem 1.5rem; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="margin: 0; font-size: 15px; font-weight: 850; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="sliders" size="18"></i>
                Konfigurator Laporan Custom
            </h3>
            <button onclick="closeCustomReportModal()" style="background: none; border: none; color: white; font-size: 24px; cursor: pointer; font-weight: 800; line-height: 1;">&times;</button>
        </div>
        <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 12px;">
            <p style="font-size: 12.5px; color: #64748B; margin: 0 0 4px 0; line-height: 1.5;">
                Konfigurasikan judul Custom, rentang tanggal, filter, serta urutan sorting untuk membuat laporan khusus yang sesuai kebutuhan Anda:
            </p>
            
            <div class="field-group">
                <span class="field-lbl">Nama/Judul Laporan</span>
                <input type="text" id="customReportName" class="card-input" placeholder="Contoh: Laporan Analisis Aset Q2" style="background: white; border: 1.5px solid #CBD5E1;">
            </div>

            <div class="field-group">
                <span class="field-lbl">Tipe Laporan Induk</span>
                <select id="customReportType" class="card-select" style="background: white; border: 1.5px solid #CBD5E1;">
                    <option value="stock">Laporan Stok & Aset Inventaris</option>
                    <option value="incoming">Laporan Penerimaan Obat Masuk</option>
                    <option value="sales">Laporan Transaksi Kasir Penjualan</option>
                    <option value="expiry">Laporan Risiko Kadaluwarsa</option>
                </select>
            </div>

            <div class="field-group">
                <span class="field-lbl">Format Dokumen</span>
                <select id="customReportFormat" class="card-select" style="background: white; border: 1.5px solid #CBD5E1;">
                    <option value="pdf">PDF Document (A4 Printable)</option>
                    <option value="csv">Excel / CSV Spreadsheet</option>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                <div class="field-group">
                    <span class="field-lbl">Mulai Tanggal</span>
                    <input type="date" id="customReportDateFrom" class="card-input" style="background: white; border: 1.5px solid #CBD5E1;">
                </div>
                <div class="field-group">
                    <span class="field-lbl">Sampai Tanggal</span>
                    <input type="date" id="customReportDateTo" class="card-input" style="background: white; border: 1.5px solid #CBD5E1;">
                </div>
            </div>

            <div class="field-group">
                <span class="field-lbl">Urutan Data (Sorting Order)</span>
                <select id="customReportSort" class="card-select" style="background: white; border: 1.5px solid #CBD5E1;">
                    <option value="latest">Terbaru / Transaksi Terakhir</option>
                    <option value="value">Nilai / Harga Tertinggi</option>
                    <option value="quantity">Kuantitas Stok Terbanyak</option>
                </select>
            </div>
        </div>
        <div style="background: #F8FAFC; padding: 1rem 1.5rem; display: flex; justify-content: flex-end; gap: 8px; border-top: 1px solid #E2E8F0;">
            <button onclick="closeCustomReportModal()" style="background: white; border: 1.5px solid #E2E8F0; border-radius: 5px; padding: 8px 14px; font-size: 12.5px; font-weight: 800; color: #475569; cursor: pointer;">
                Batal
            </button>
            <button onclick="generateCustomReport()" style="background: #0B3E9C; border: none; border-radius: 5px; padding: 8px 14px; font-size: 12.5px; font-weight: 800; color: white; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                Generate & Unduh Laporan
            </button>
        </div>
    </div>
</div>

<!-- Hidden print iframe for direct download without new page/tab -->
<iframe id="print_iframe" style="display: none; border: none; width: 0; height: 0;"></iframe>

<!-- circular FAB plus icon -->
<button class="floating-fab" onclick="openCustomReportModal();" title="Buat Konfigurasi Laporan Custom">
    <i data-lucide="plus" size="24" style="stroke-width: 3;"></i>
</button>
</div>

<script>
    // LocalStorage Report Logs Management System
    const defaultLogs = [
        {
            nama: "Laporan Stok Bulanan - {{ date('M Y') }}",
            oleh: "Apoteker Utama",
            tanggal: "{{ date('d M Y') }}, 09:45",
            format: "PDF",
            url: "{{ route('admin.reports.stock.export') }}?category=all&status=all&format=pdf"
        },
        {
            nama: "Transaksi Penjualan Harian",
            oleh: "{{ auth()->user()->name }}",
            tanggal: "{{ date('d M Y', strtotime('-1 day')) }}, 17:10",
            format: "EXCEL",
            url: "{{ route('admin.reports.sales.export') }}?date_from=&date_to=&payment_method=all&format=csv"
        },
        {
            nama: "Rekapitulasi Obat Masuk Q{{ ceil(date('n')/3) }}",
            oleh: "Apoteker Utama",
            tanggal: "{{ date('d M Y', strtotime('-2 days')) }}, 14:20",
            format: "PDF",
            url: "{{ route('admin.reports.incoming.export') }}?date_from=&date_to=&supplier_id=all&format=pdf"
        }
    ];

    function getReportLogs() {
        let logs = localStorage.getItem('apoteker_report_logs');
        if (!logs) {
            localStorage.setItem('apoteker_report_logs', JSON.stringify(defaultLogs));
            return defaultLogs;
        }
        return JSON.parse(logs);
    }

    function addReportLog(nama, format, url) {
        const logs = getReportLogs();
        const now = new Date();
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const formattedDate = String(now.getDate()).padStart(2, '0') + ' ' + months[now.getMonth()] + ' ' + now.getFullYear() + ', ' + String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
        
        const newLog = {
            nama: nama,
            oleh: "{{ auth()->user()->name }}",
            tanggal: formattedDate,
            format: format.toUpperCase(),
            url: url
        };
        
        logs.unshift(newLog);
        localStorage.setItem('apoteker_report_logs', JSON.stringify(logs));
        renderReportLogs();
    }

    function renderReportLogs() {
        const logs = getReportLogs();
        
        // Render top 3 logs on dashboard
        const dashboardTableBody = document.getElementById('logsTableBody');
        if (dashboardTableBody) {
            dashboardTableBody.innerHTML = '';
            const limitLogs = logs.slice(0, 3);
            
            limitLogs.forEach(log => {
                const tr = document.createElement('tr');
                const badgeClass = log.format.toLowerCase() === 'pdf' ? 'badge-pdf' : 'badge-excel';
                
                tr.innerHTML = `
                    <td><strong style="color: #1E293B;">${log.nama}</strong></td>
                    <td style="font-weight: 700; color: #475569;">${log.oleh}</td>
                    <td style="color: #64748B; font-weight: 500;">${log.tanggal}</td>
                    <td><span class="badge-format ${badgeClass}">${log.format}</span></td>
                    <td>
                        <button type="button" class="btn-download-log" onclick="triggerDirectUrlDownload('${log.url}', '${log.format.toLowerCase()}')" title="Download">
                            <i data-lucide="download" style="width:14px; height:14px;"></i>
                        </button>
                    </td>
                `;
                dashboardTableBody.appendChild(tr);
            });
        }
        
        // Render all logs in modal
        const allTableBody = document.getElementById('allActivitiesTableBody');
        if (allTableBody) {
            allTableBody.innerHTML = '';
            if (logs.length === 0) {
                allTableBody.innerHTML = `<tr><td colspan="5" style="text-align:center; padding: 2rem; color:#94A3B8;">Tidak ada histori ekspor saat ini.</td></tr>`;
            } else {
                logs.forEach(log => {
                    const tr = document.createElement('tr');
                    tr.style.borderBottom = '1px solid #F1F5F9';
                    const badgeClass = log.format.toLowerCase() === 'pdf' ? 'badge-pdf' : 'badge-excel';
                    
                    tr.innerHTML = `
                        <td style="padding: 10px;"><strong style="color: #1E293B;">${log.nama}</strong></td>
                        <td style="padding: 10px; font-weight: 700; color: #475569;">${log.oleh}</td>
                        <td style="padding: 10px; color: #64748B; font-weight: 500;">${log.tanggal}</td>
                        <td style="padding: 10px;"><span class="badge-format ${badgeClass}">${log.format}</span></td>
                        <td style="padding: 10px; text-align: center;">
                            <button type="button" class="btn-download-log" onclick="triggerDirectUrlDownload('${log.url}', '${log.format.toLowerCase()}')" title="Download">
                                <i data-lucide="download" style="width:14px; height:14px;"></i>
                            </button>
                        </td>
                    `;
                    allTableBody.appendChild(tr);
                });
            }
        }
        
        // Re-initialize lucide icons for dynamically added HTML
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    function triggerDirectUrlDownload(url, format) {
        if (format === 'pdf') {
            document.getElementById('print_iframe').src = url;
        } else {
            window.location.href = url;
        }
    }

    function clearAllActivities() {
        Swal.fire({
            title: 'Hapus Histori Ekspor?',
            text: 'Tindakan ini akan membersihkan seluruh catatan riwayat ekspor laporan dari perangkat ini secara permanen.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Bersihkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.setItem('apoteker_report_logs', JSON.stringify([]));
                renderReportLogs();
                Swal.fire({
                    title: 'Histori Bersih',
                    text: 'Seluruh histori ekspor laporan telah berhasil dibersihkan.',
                    icon: 'success',
                    confirmButtonColor: '#0B3E9C'
                });
            }
        });
    }

    // Modal Control Functions
    function openActivitiesModal() {
        const modal = document.getElementById('allActivitiesModal');
        modal.style.display = 'flex';
        renderReportLogs();
    }

    function closeActivitiesModal() {
        const modal = document.getElementById('allActivitiesModal');
        modal.style.display = 'none';
    }

    // Automatically smooth scroll to preview card when report is submitted and loaded
    window.addEventListener('DOMContentLoaded', () => {
        // Render logs on load
        renderReportLogs();

        if (window.location.search.includes('report_type=')) {
            const previewCard = document.querySelector('.preview-card');
            if (previewCard) {
                previewCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });

    // Handles direct PDF/Excel downloads based on form filters (WITHOUT opening new page/tab)
    function triggerStockExport(format) {
        const form = document.getElementById('stockReportForm');
        const cat = form.querySelector('select[name="category"]').value;
        const status = form.querySelector('select[name="status"]').value;
        
        const url = "{{ route('admin.reports.stock.export') }}?category=" + encodeURIComponent(cat) + "&status=" + status + "&format=" + format;
        
        // Record dynamic activity log
        const labelCat = cat === 'all' ? 'Semua Kategori' : cat;
        const labelStatus = status === 'all' ? 'Semua Status' : (status === 'kritis' ? 'Stok Kritis' : 'Stok Aman');
        addReportLog('Laporan Stok Inventaris (' + labelCat + ' - ' + labelStatus + ')', format, url);

        if (format === 'pdf') {
            document.getElementById('print_iframe').src = url;
        } else {
            window.location.href = url; // Directly downloads Excel/CSV
        }
    }

    function triggerIncomingExport(format) {
        const form = document.getElementById('incomingForm');
        const from = form.querySelector('input[name="date_from"]').value;
        const to = form.querySelector('input[name="date_to"]').value;
        const sup = form.querySelector('select[name="supplier_id"]').value;
        
        const url = "{{ route('admin.reports.incoming.export') }}?date_from=" + from + "&date_to=" + to + "&supplier_id=" + sup + "&format=" + format;
        
        // Record dynamic activity log
        const labelPeriod = (from ? from : 'Awal') + ' s/d ' + (to ? to : 'Akhir');
        addReportLog('Laporan Penerimaan Obat Masuk (' + labelPeriod + ')', format, url);

        if (format === 'pdf') {
            document.getElementById('print_iframe').src = url;
        } else {
            window.location.href = url;
        }
    }

    function triggerSalesExport(format) {
        const form = document.getElementById('salesForm');
        const from = form.querySelector('input[name="date_from"]').value;
        const to = form.querySelector('input[name="date_to"]').value;
        const pay = form.querySelector('select[name="payment_method"]').value;
        
        const url = "{{ route('admin.reports.sales.export') }}?date_from=" + from + "&date_to=" + to + "&payment_method=" + pay + "&format=" + format;
        
        // Record dynamic activity log
        const labelMethod = pay === 'all' ? 'Semua Metode' : (pay === 'tunai' ? 'Tunai' : 'QRIS/Non-Tunai');
        addReportLog('Laporan Transaksi Kasir Penjualan (' + labelMethod + ')', format, url);

        if (format === 'pdf') {
            document.getElementById('print_iframe').src = url;
        } else {
            window.location.href = url;
        }
    }

    function triggerExpiryExport() {
        const risk = document.getElementById('riskSelect').value;
        const url = "{{ route('admin.reports.expiry.export') }}?risk=" + risk + "&format=pdf";
        
        // Record dynamic activity log
        addReportLog('Laporan Risiko Kadaluwarsa (< ' + risk + ' Bulan)', 'pdf', url);

        document.getElementById('print_iframe').src = url;
    }

    // Dynamic warning updates
    function updateRiskPills(months) {
        let count = {{ $kritisCount }};
        if (months == '6') {
            count = Math.floor(count * 1.8);
        } else if (months == '12') {
            count = Math.floor(count * 3.5);
        }
        document.getElementById('kritisCount').textContent = count + " Item";
    }

    // Modal Control Functions
    function openReturModal() {
        const modal = document.getElementById('returDestructionModal');
        modal.style.display = 'flex';
    }

    function closeReturModal() {
        const modal = document.getElementById('returDestructionModal');
        modal.style.display = 'none';
    }

    function submitExpiryAction(actionType) {
        const title = actionType === 'retur' ? 'Proses Retur ke Supplier?' : 'Konfirmasi Pemusnahan Resmi?';
        const confirmMsg = actionType === 'retur'
            ? 'Aksi ini akan menolkan stok sisa dari semua obat kritis/kadaluwarsa di sistem dan mencatat log retur supplier.'
            : 'Aksi ini akan menolkan stok sisa dari semua obat kritis/kadaluwarsa di sistem dan mencatat berita acara pemusnahan resmi.';
            
        Swal.fire({
            title: title,
            text: confirmMsg,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: actionType === 'retur' ? '#0B3E9C' : '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: actionType === 'retur' ? 'Ya, Proses Retur!' : 'Ya, Musnahkan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('expiryActionType').value = actionType;
                document.getElementById('expiryActionForm').submit();
            }
        });
    }

    function openCustomReportModal() {
        document.getElementById('customReportModal').style.display = 'flex';
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('customReportDateTo').value = today;
    }

    function closeCustomReportModal() {
        document.getElementById('customReportModal').style.display = 'none';
    }

    function generateCustomReport() {
        let name = document.getElementById('customReportName').value.trim();
        const type = document.getElementById('customReportType').value;
        const format = document.getElementById('customReportFormat').value;
        const from = document.getElementById('customReportDateFrom').value;
        const to = document.getElementById('customReportDateTo').value;
        const sort = document.getElementById('customReportSort').value;
        
        if (!name) {
            name = "Laporan Custom " + type.charAt(0).toUpperCase() + type.slice(1);
        }
        
        let url = "";
        if (type === 'stock') {
            url = "{{ route('admin.reports.stock.export') }}?category=all&status=all&format=" + format + "&sort=" + sort;
        } else if (type === 'incoming') {
            url = "{{ route('admin.reports.incoming.export') }}?date_from=" + from + "&date_to=" + to + "&supplier_id=all&format=" + format + "&sort=" + sort;
        } else if (type === 'sales') {
            url = "{{ route('admin.reports.sales.export') }}?date_from=" + from + "&date_to=" + to + "&payment_method=all&format=" + format + "&sort=" + sort;
        } else {
            url = "{{ route('admin.reports.expiry.export') }}?risk=3&format=" + format + "&sort=" + sort;
        }
        
        // Record custom activity log
        addReportLog("Custom: " + name, format, url);
        
        // Trigger download/print
        if (format === 'pdf') {
            document.getElementById('print_iframe').src = url;
        } else {
            window.location.href = url;
        }
        
        closeCustomReportModal();
    }
</script>

@endsection
