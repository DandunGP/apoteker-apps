@extends('layouts.app')

@section('title', 'Dashboard Kasir')

@section('content')

<style>
    /* Premium clinical-theme corporate dashboard for Kasir */
    .dashboard-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Outfit', sans-serif;
    }

    /* Header Section styling */
    .dashboard-header {
        margin-bottom: 0.5rem;
    }

    .title-area h1 {
        font-size: 26px;
        font-weight: 800;
        color: #1E3A8A; /* Dark premium blue */
        margin: 0;
    }

    .title-area p {
        font-size: 13.5px;
        color: #64748B;
        margin: 6px 0 0 0;
        font-weight: 500;
    }

    /* Top Grid Widgets (4 Columns) */
    .widgets-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }

    /* Card standard styling */
    .widget-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 155px;
    }

    .widget-top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .icon-wrapper {
        width: 38px;
        height: 38px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-blue { background: #EFF6FF; color: #3B82F6; }
    .icon-green { background: #ECFDF5; color: #10B981; }
    .icon-gray { background: #F1F5F9; color: #64748B; }

    .widget-badge {
        font-size: 11px;
        font-weight: 800;
    }

    .badge-trend { color: #10B981; }
    .badge-danger { color: #EF4444; }

    .widget-middle {
        margin-top: 1rem;
    }

    .widget-label {
        font-size: 11px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .widget-value {
        font-size: 22px;
        font-weight: 800;
        color: #1E293B;
        margin: 4px 0 0 0;
    }

    .widget-footer {
        font-size: 11px;
        color: #64748B;
        font-weight: 600;
        margin-top: 8px;
    }

    /* Cashflow widget custom styles */
    .cashflow-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 11.5px;
        font-weight: 700;
        margin-top: 5px;
    }

    .cashflow-label-in { color: #059669; font-size: 9px; font-weight: 800; }
    .cashflow-label-out { color: #DC2626; font-size: 9px; font-weight: 800; }

    /* Middle Laporan Header row */
    .laporan-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
    }

    .laporan-title-area h2 {
        font-size: 18px;
        font-weight: 800;
        color: #1E3A8A;
        margin: 0;
    }

    .laporan-title-area p {
        font-size: 12.5px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .btn-laporan-lengkap {
        background: #0056B3;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 18px;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-laporan-lengkap:hover {
        background: #004494;
    }

    /* Split Grid layout */
    .main-grid {
        display: grid;
        grid-template-columns: 2.3fr 1fr;
        gap: 1.5rem;
    }

    .main-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        padding: 1.5rem;
    }

    .main-card-title {
        font-size: 14px;
        font-weight: 800;
        color: #1E293B;
        margin: 0 0 1rem 0;
    }

    /* Table styles */
    .sales-table {
        width: 100%;
        border-collapse: collapse;
    }

    .sales-table th {
        background: #EFF6FF;
        color: #1E3A8A;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 12px 16px;
        border-bottom: 1px solid #BFDBFE;
        letter-spacing: 0.5px;
        text-align: left;
    }

    .sales-table td {
        padding: 14px 16px;
        font-size: 13px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        color: #334155;
    }

    .sales-table tbody tr:last-child td {
        border-bottom: none;
    }

    .pill-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 9px;
        font-weight: 800;
        text-align: center;
        background: #DCFCE7;
        color: #15803D;
        text-transform: uppercase;
    }

    /* Right side boxes */
    .stats-boxes-stack {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .stat-box-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .stat-box-card-blue {
        border-color: #BFDBFE;
    }

    .stat-box-label {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-box-value {
        font-size: 18px;
        font-weight: 800;
        margin-top: 4px;
    }

    .stat-box-value-blue {
        color: #2563EB;
    }

    .stat-box-value-dark {
        color: #1E293B;
    }

    .method-row {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 4px;
        font-weight: 800;
        color: #15803D;
        font-size: 15px;
    }
</style>

<div class="dashboard-container">
    
    <!-- Top Header Area -->
    <div class="dashboard-header">
        <div class="title-area">
            <h1>Selamat Datang, Kasir</h1>
            <p>Pantau ringkasan penjualan Apotek Pakis Medika Utama hari ini.</p>
        </div>
    </div>

    <!-- Top Widgets Grid (4 columns) -->
    <div class="widgets-grid">
        <!-- Card 1: Total Penjualan Rekap -->
        <div class="widget-card">
            <div class="widget-top-row">
                <div class="icon-wrapper icon-blue">
                    <i data-lucide="banknote" size="18"></i>
                </div>
                <span class="widget-badge badge-trend">Rekap</span>
            </div>
            <div class="widget-middle">
                <span class="widget-label">Total Penjualan</span>
                <div class="widget-value">Rp {{ number_format($data['kasir_omzet'], 0, ',', '.') }}</div>
            </div>
            <div class="widget-footer">
                Dari {{ $data['kasir_transactions'] }} transaksi berhasil
            </div>
        </div>

        <!-- Card 2: Jumlah Transaksi -->
        <div class="widget-card">
            <div class="widget-top-row">
                <div class="icon-wrapper icon-green">
                    <i data-lucide="check-square" size="18"></i>
                </div>
                <span class="widget-badge badge-trend">Total</span>
            </div>
            <div class="widget-middle">
                <span class="widget-label">Total Transaksi</span>
                <div class="widget-value">{{ $data['kasir_transactions'] }}</div>
            </div>
            <div class="widget-footer">
                Rata-rata Rp {{ number_format($data['kasir_average'], 0, ',', '.') }} / transaksi
            </div>
        </div>

        <!-- Card 3: Stok Obat Menipis -->
        <div class="widget-card">
            <div class="widget-top-row">
                <div class="icon-wrapper icon-gray">
                    <i data-lucide="box" size="18"></i>
                </div>
                <span class="widget-badge badge-danger" style="font-weight: 800;">Low Stock</span>
            </div>
            <div class="widget-middle">
                <span class="widget-label">Stok Obat Menipis</span>
                <div class="widget-value">{{ $data['low_stock_count'] }} Item</div>
            </div>
            <div class="widget-footer">
                Perlu re-stock segera
            </div>
        </div>

        <!-- Card 4: Rekap Arus Kas -->
        <div class="widget-card">
            <div class="widget-top-row">
                <div class="icon-wrapper icon-blue">
                    <i data-lucide="landmark" size="18"></i>
                </div>
                <span class="widget-badge badge-trend">Rekap</span>
            </div>
            <div class="widget-middle" style="margin-top: 0.5rem;">
                <span class="widget-label">Rekap Arus Kas</span>
                
                <div class="cashflow-row">
                    <span class="cashflow-label-in">MASUK</span>
                    <span style="color: #059669;">Rp {{ number_format($data['kasir_cash_in'], 0, ',', '.') }}</span>
                </div>
                <div class="cashflow-row" style="margin-top: 3px;">
                    <span class="cashflow-label-out">KELUAR</span>
                    <span style="color: #DC2626;">Rp {{ number_format($data['kasir_cash_out'], 0, ',', '.') }}</span>
                </div>
            </div>
            <div class="widget-footer">
                Semua Kategori Kas Masuk/Keluar
            </div>
        </div>
    </div>

    <!-- Laporan Penjualan section header -->
    <div class="laporan-header-row">
        <div class="laporan-title-area">
            <h2>Laporan Rekap Penjualan</h2>
            <p>Ringkasan performa seluruh riwayat transaksi penjualan.</p>
        </div>
        <a href="{{ route('sales.history') }}" class="btn-laporan-lengkap">
            <i data-lucide="file-text" size="14"></i>
            Lihat Riwayat Lengkap
        </a>
    </div>

    <!-- Bottom Split Grid layout -->
    <div class="main-grid">
        <!-- Left: 5 Transaksi Terakhir -->
        <div class="main-card">
            <h3 class="main-card-title">5 Transaksi Terakhir</h3>
            
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Tanggal & Waktu</th>
                        <th>Total</th>
                        <th>Metode</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['kasir_recent'] as $recent)
                    <tr>
                        <td style="color: #64748B; font-weight: 700;">{{ $recent->time }}</td>
                        <td style="font-weight: 800; color: #1E293B;">{{ $recent->total }}</td>
                        <td>
                            <span class="pill-badge" style="background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1;">
                                {{ $recent->payment_method }}
                            </span>
                        </td>
                        <td>
                            <span class="pill-badge">{{ $recent->status }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Right: Stats Boxes stack -->
        <div class="stats-boxes-stack">
            <!-- Box 1: Total Omzet -->
            <div class="stat-box-card stat-box-card-blue">
                <span class="stat-box-label">Total Omzet</span>
                <div class="stat-box-value stat-box-value-blue">
                    Rp {{ number_format($data['kasir_omzet'], 0, ',', '.') }}
                </div>
            </div>

            <!-- Box 2: Total Transaksi -->
            <div class="stat-box-card">
                <span class="stat-box-label">Total Transaksi</span>
                <div class="stat-box-value stat-box-value-dark">
                    {{ $data['kasir_transactions'] }} Transaksi
                </div>
            </div>

            <!-- Box 3: Metode Terpopuler -->
            <div class="stat-box-card">
                <span class="stat-box-label">Metode Terpopuler</span>
                <div class="method-row" style="color: #0F62FE;">
                    <i data-lucide="{{ $data['kasir_popular_icon'] }}" size="16"></i>
                    <span>{{ $data['kasir_popular_method'] }}</span>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
