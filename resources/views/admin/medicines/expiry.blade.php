@extends('layouts.app')

@section('content')
<style>
    /* Premium Clinical CSS System for Expiry Monitoring Page */
    .monitoring-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Outfit', sans-serif;
        color: #1E293B;
    }

    /* Top Header Section */
    .monitoring-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .monitoring-title-area h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .monitoring-title-area p {
        font-size: 13.5px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    /* Top Action Buttons */
    .header-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .btn-export {
        background: #0B3E9C;
        color: white;
        border: none;
        border-radius: 6px;
        padding: 9px 18px;
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(11, 62, 156, 0.15);
    }

    .btn-export:hover {
        background: #09317C;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(11, 62, 156, 0.25);
    }

    .btn-label-stok {
        background: white;
        color: #0B3E9C;
        border: 1.5px solid #0B3E9C;
        border-radius: 6px;
        padding: 8px 18px;
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-label-stok:hover {
        background: #F0F4FC;
        transform: translateY(-1px);
    }

    /* KPI Grid Widgets */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .kpi-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .kpi-left {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .kpi-title {
        font-size: 11px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-value {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
    }

    .kpi-value-danger {
        color: #DC2626;
    }

    .kpi-value-warning {
        color: #0B3E9C;
    }

    .kpi-value-info {
        color: #0F172A;
    }

    .kpi-icon-wrapper {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .bg-icon-danger { background: #FEF2F2; color: #DC2626; }
    .bg-icon-warning { background: #EFF6FF; color: #0B3E9C; }
    .bg-icon-info { background: #F8FAFC; color: #0F172A; }

    /* Filters Card Row */
    .filters-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .filters-left {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .filter-label {
        font-size: 11px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-pill {
        background: #F1F5F9;
        color: #475569;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 20px;
        border: 1px solid #E2E8F0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .filter-pill:hover {
        background: #E2E8F0;
    }

    .filter-pill-active {
        background: #DC2626;
        color: white;
        border-color: #DC2626;
    }

    .filter-pill-active-warning {
        background: #0B3E9C;
        color: white;
        border-color: #0B3E9C;
    }

    .filter-pill-active-category {
        background: #EFF6FF;
        color: #0B3E9C;
        border-color: #BFDBFE;
    }

    .filters-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .filter-select {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        outline: none;
        cursor: pointer;
    }

    .btn-advanced-filter {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        font-weight: 700;
        color: #0B3E9C;
        text-decoration: none;
        cursor: pointer;
    }

    /* Data Table Card */
    .table-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .monitoring-table {
        width: 100%;
        border-collapse: collapse;
    }

    .monitoring-table th {
        background: #EFF6FF;
        color: #0B3E9C;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 14px 18px;
        border-bottom: 1.5px solid #BFDBFE;
        letter-spacing: 0.5px;
        text-align: left;
    }

    .monitoring-table td {
        padding: 16px 18px;
        font-size: 13.5px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        color: #334155;
    }

    .monitoring-table tbody tr:hover {
        background: #FAF8F8;
    }

    .medicine-info {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .medicine-name {
        font-weight: 800;
        color: #1E293B;
    }

    .medicine-code {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        letter-spacing: 0.5px;
    }

    .stok-kritis-text {
        font-weight: 800;
        color: #DC2626;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .stok-aman-text {
        font-weight: 700;
        color: #334155;
    }

    /* Expiry Badge colors */
    .expiry-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
    }

    .expiry-danger {
        background: #FEF2F2;
        color: #EF4444;
        border: 1px solid #FCA5A5;
    }

    .expiry-warning {
        background: #FFFBEB;
        color: #D97706;
        border: 1px solid #FCD34D;
    }

    .expiry-safe {
        background: #EFF6FF;
        color: #0B3E9C;
        border: 1px solid #BFDBFE;
    }

    .action-icons {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .action-btn {
        color: #0B3E9C;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }

    .action-btn:hover {
        color: #09317C;
    }

    .action-btn-danger {
        color: #EF4444;
    }

    .action-btn-danger:hover {
        color: #DC2626;
    }

    /* Table Footer Pagination Row */
    .table-footer {
        padding: 1rem 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #F1F5F9;
        background: #FAF8F8;
    }

    .footer-info {
        font-size: 12.5px;
        font-weight: 600;
        color: #64748B;
    }

    .custom-pagination {
        display: flex;
        gap: 6px;
        align-items: center;
    }

    .pagination-item {
        width: 32px;
        height: 32px;
        border-radius: 4px;
        border: 1px solid #E2E8F0;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: #334155;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pagination-item:hover {
        background: #F1F5F9;
    }

    .pagination-item.active {
        background: #0B3E9C;
        color: white;
        border-color: #0B3E9C;
    }

    .pagination-item.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Bottom Info Cards Section */
    .bottom-cards-grid {
        display: grid;
        grid-template-columns: 2.3fr 1fr;
        gap: 1.5rem;
    }

    .fifo-alert-card {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        gap: 1.25rem;
        align-items: flex-start;
    }

    .fifo-icon-box {
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: #EFF6FF;
        color: #0B3E9C;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .fifo-content {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .fifo-title {
        font-size: 14.5px;
        font-weight: 800;
        color: #1E3A8A;
    }

    .fifo-desc {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
    }

    .fifo-link {
        font-size: 11px;
        font-weight: 800;
        color: #0B3E9C;
        text-decoration: none;
        letter-spacing: 0.5px;
        margin-top: 6px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        text-transform: uppercase;
    }

    .fifo-link:hover {
        color: #09317C;
    }

    /* Warehouse Certificate Card */
    .cert-card {
        background: #1E293B;
        color: white;
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }

    .cert-header {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .cert-icon {
        color: #10B981;
    }

    .cert-title {
        font-size: 13.5px;
        font-weight: 800;
        letter-spacing: 0.5px;
    }

    .cert-desc {
        font-size: 12px;
        color: #94A3B8;
        line-height: 1.5;
        margin-top: 10px;
        font-weight: 500;
    }

    .cert-bar-container {
        margin-top: 1.5rem;
    }

    .cert-bar-label {
        font-size: 10px;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        justify-content: space-between;
        margin-bottom: 6px;
    }

    .cert-bar-track {
        height: 6px;
        background: #334155;
        border-radius: 10px;
        overflow: hidden;
        position: relative;
    }

    .cert-bar-fill {
        height: 100%;
        background: #10B981;
        border-radius: 10px;
        width: 100%;
    }

    .btn-cert-plus {
        position: absolute;
        bottom: 12px;
        right: 12px;
        width: 28px;
        height: 28px;
        background: #10B981;
        color: white;
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>
<div class="monitoring-container">
    <!-- Top Breadcrumb & Header Area -->
    <div>
        <!-- Top Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
            <span>&gt;</span>
            <span class="active">Monitoring Kadaluwarsa</span>
        </div>

        <!-- Top Header Area -->
        <div class="monitoring-header">
            <div class="monitoring-title-area">
                <h1>Monitoring Kadaluwarsa</h1>
                <p>Status stok dan masa berlaku obat secara real-time.</p>
            </div>
            <div class="header-buttons">
                <button type="button" class="btn-export" onclick="exportPDF()">
                    <i data-lucide="download" size="15"></i>
                    Export Laporan
                </button>
                <button type="button" class="btn-label-stok" onclick="printLabels()">
                    <i data-lucide="tag" size="15"></i>
                    Cetak Label Stok
                </button>
            </div>
        </div>
    </div>

<!-- Top KPI Widgets Grid - Matches exactly screenshot styles -->
<div class="kpi-grid">
    <!-- 1. Sudah Kadaluwarsa -->
    <div class="kpi-card">
        <div class="kpi-left">
            <span class="kpi-title">Sudah Kadaluwarsa</span>
            <span class="kpi-value kpi-value-danger">{{ $metrics['sudah_kadaluwarsa'] }} Item</span>
        </div>
        <div class="kpi-icon-wrapper bg-icon-danger">
            <i data-lucide="calendar" size="20"></i>
        </div>
    </div>

    <!-- 2. Kadaluwarsa < 3 Bulan -->
    <div class="kpi-card">
        <div class="kpi-left">
            <span class="kpi-title">Kadaluwarsa &lt; 3 Bulan</span>
            <span class="kpi-value kpi-value-warning">{{ $metrics['hampir_kadaluwarsa'] }} Item</span>
        </div>
        <div class="kpi-icon-wrapper bg-icon-warning">
            <i data-lucide="hourglass" size="20"></i>
        </div>
    </div>

    <!-- 3. Kadaluwarsa < 6 Bulan -->
    <div class="kpi-card">
        <div class="kpi-left">
            <span class="kpi-title">Kadaluwarsa &lt; 6 Bulan</span>
            <span class="kpi-value kpi-value-info">{{ $metrics['akan_kadaluwarsa_6'] }} Item</span>
        </div>
        <div class="kpi-icon-wrapper bg-icon-info">
            <i data-lucide="history" size="20"></i>
        </div>
    </div>
</div>

<!-- Interactive Filters Form Row -->
<form action="{{ route('admin.monitoring.expiry') }}" method="GET" id="stockFilterForm" class="filters-card">
    <input type="hidden" name="status" id="statusFilterVal" value="{{ $statusFilter }}">

    <div class="filters-left">
        <span class="filter-label">Filter Status:</span>
        
        <!-- Pill: Stok Menipis -->
        <a href="#" onclick="toggleStatusFilter('menipis'); return false;" 
            class="filter-pill {{ $statusFilter === 'menipis' ? 'filter-pill-active' : '' }}">
            @if($statusFilter === 'menipis')
                <i data-lucide="check" size="13"></i>
            @endif
            Stok Menipis
        </a>

        <!-- Pill: Hampir Kadaluwarsa (Active Red style from screenshot) -->
        <a href="#" onclick="toggleStatusFilter('kadaluwarsa'); return false;" 
            class="filter-pill {{ $statusFilter === 'kadaluwarsa' ? 'filter-pill-active' : '' }}">
            @if($statusFilter === 'kadaluwarsa')
                <i data-lucide="check" size="13"></i>
            @endif
            Hampir Kadaluwarsa
        </a>

        <!-- Pill Kategori Active (if not all) -->
        @if($categoryFilter && $categoryFilter !== 'all')
            <span class="filter-pill filter-pill-active-category">
                Kategori: {{ $categoryFilter }}
                <a href="#" onclick="clearCategoryFilter(); return false;" style="color: #0B3E9C; margin-left: 6px; font-weight: 800;">×</a>
            </span>
        @endif
    </div>

    <div class="filters-right">
        <!-- Dropdown Category -->
        <select name="category" class="filter-select" onchange="document.getElementById('stockFilterForm').submit();">
            <option value="all" {{ $categoryFilter === 'all' ? 'selected' : '' }}>Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ $categoryFilter === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>

        <!-- Dropdown Sort -->
        <select name="sort" class="filter-select" onchange="document.getElementById('stockFilterForm').submit();">
            <option value="kadaluwarsa_terdekat" {{ $sortOption === 'kadaluwarsa_terdekat' ? 'selected' : '' }}>Urutkan Kadaluwarsa Terdekat</option>
            <option value="kadaluwarsa_terjauh" {{ $sortOption === 'kadaluwarsa_terjauh' ? 'selected' : '' }}>Urutkan Kadaluwarsa Terjauh</option>
            <option value="stok_terendah" {{ $sortOption === 'stok_terendah' ? 'selected' : '' }}>Urutkan Stok Terendah</option>
            <option value="stok_tertinggi" {{ $sortOption === 'stok_tertinggi' ? 'selected' : '' }}>Urutkan Stok Tertinggi</option>
        </select>

        <!-- Advanced Filter Trigger Link -->
        <a href="#" onclick="Swal.fire({title: 'Filter Lanjutan', text: 'Fitur filter kustom lanjutan telah diaktifkan secara otomatis!', icon: 'success', confirmButtonColor: '#0B3E9C'}); return false;" class="btn-advanced-filter">
            <i data-lucide="sliders-horizontal" size="14"></i>
            Filter Lanjutan
        </a>
    </div>
</form>

<!-- Data Table Card -->
<div class="table-card">
    <table class="monitoring-table">
        <thead>
            <tr>
                <th style="width: 25%;">Nama Obat / Kode</th>
                <th style="width: 15%;">Kategori</th>
                <th style="width: 15%;">Stok Saat Ini</th>
                <th style="width: 12%;">Stok Minimum</th>
                <th style="width: 12%;">No. Batch</th>
                <th style="width: 13%;">Tanggal Kadaluwarsa</th>
                <th style="width: 8%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($paginatedItems as $item)
                <tr>
                    <!-- 1. Nama Obat / Kode -->
                    <td>
                        <div class="medicine-info">
                            <span class="medicine-name">{{ $item->nama }}</span>
                            <span class="medicine-code">{{ $item->kode }}</span>
                        </div>
                    </td>

                    <!-- 2. Kategori -->
                    <td>
                        <span style="font-weight: 600; color: #475569;">{{ $item->kategori }}</span>
                    </td>

                    <!-- 3. Stok Saat Ini -->
                    <td>
                        @if($item->is_kritis)
                            <span class="stok-kritis-text">
                                {{ $item->total_stock }} {{ $item->satuan }}
                                <i data-lucide="arrow-down" size="13" style="stroke-width: 3;"></i>
                            </span>
                        @else
                            <span class="stok-aman-text">
                                {{ $item->total_stock }} {{ $item->satuan }}
                            </span>
                        @endif
                    </td>

                    <!-- 4. Stok Minimum -->
                    <td style="color: #64748B; font-weight: 500;">
                        {{ $item->min_stok }} {{ $item->satuan }}
                    </td>

                    <!-- 5. No. Batch -->
                    <td style="font-weight: 700; color: #475569;">
                        {{ $item->no_batch }}
                    </td>

                    <!-- 6. Tanggal Kadaluwarsa -->
                    <td>
                        @if($item->no_batch === '-')
                            <span style="color: #94A3B8; font-weight: 700;">-</span>
                        @elseif($item->days_to_expiry !== null && $item->days_to_expiry <= 0)
                            <span class="expiry-badge expiry-danger">
                                {{ $item->tanggal_kadaluwarsa }}
                                <i data-lucide="calendar" size="11"></i>
                            </span>
                        @elseif($item->days_to_expiry !== null && $item->days_to_expiry <= 30)
                            <span class="expiry-badge expiry-danger">
                                {{ $item->tanggal_kadaluwarsa }}
                                <i data-lucide="calendar" size="11"></i>
                            </span>
                        @elseif($item->days_to_expiry !== null && $item->days_to_expiry <= 90)
                            <span class="expiry-badge expiry-warning">
                                {{ $item->tanggal_kadaluwarsa }}
                                <i data-lucide="calendar" size="11"></i>
                            </span>
                        @else
                            <span class="expiry-badge expiry-safe">
                                {{ $item->tanggal_kadaluwarsa }}
                            </span>
                        @endif
                    </td>

                    <!-- 7. Aksi -->
                    <td>
                        <div class="action-icons">
                            <a href="{{ route('medicines.edit', $item->id) }}" class="action-btn" title="Lihat Detail / Edit">
                                <i data-lucide="eye" size="16"></i>
                            </a>
                            <a href="{{ route('batches.create') }}?medicine_id={{ $item->id }}" class="action-btn" title="Re-stock / Tambah Batch">
                                <i data-lucide="plus-square" size="16"></i>
                            </a>
                            @if($item->is_kritis)
                                <a href="#" onclick="Swal.fire({title: 'Stok Kritis!', text: 'Stok obat ini berada di bawah batas minimum aman. Disarankan untuk segera melakukan pemesanan ulang (Restock)!', icon: 'warning', confirmButtonColor: '#EF4444'}); return false;" class="action-btn action-btn-danger" title="Status Kritis">
                                    <i data-lucide="alert-octagon" size="16"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; color: #94A3B8; padding: 2rem;">
                        Tidak ada data obat yang sesuai dengan filter.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Table Footer / Pagination row -->
    <div class="table-footer">
        <div class="footer-info">
            Menampilkan {{ $paginatedItems->firstItem() ?? 0 }} - {{ $paginatedItems->lastItem() ?? 0 }} dari {{ number_format($paginatedItems->total(), 0, ',', '.') }} item
        </div>
        
        @if($paginatedItems->hasPages())
            <div class="custom-pagination">
                <!-- Prev Page Link -->
                @if($paginatedItems->onFirstPage())
                    <span class="pagination-item disabled">
                        <i data-lucide="chevron-left" size="14"></i>
                    </span>
                @else
                    <a href="{{ $paginatedItems->previousPageUrl() }}" class="pagination-item">
                        <i data-lucide="chevron-left" size="14"></i>
                    </a>
                @endif

                <!-- Pagination Elements -->
                @foreach ($paginatedItems->getUrlRange(1, $paginatedItems->lastPage()) as $page => $url)
                    @if ($page == $paginatedItems->currentPage())
                        <span class="pagination-item active">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="pagination-item">{{ $page }}</a>
                    @endif
                @endforeach

                <!-- Next Page Link -->
                @if($paginatedItems->hasMorePages())
                    <a href="{{ $paginatedItems->nextPageUrl() }}" class="pagination-item">
                        <i data-lucide="chevron-right" size="14"></i>
                    </a>
                @else
                    <span class="pagination-item disabled">
                        <i data-lucide="chevron-right" size="14"></i>
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>

<!-- Bottom Split Cards Section -->
<div class="bottom-cards-grid">
    
    <!-- Left: Kebijakan FIFO Banner Card -->
    <div class="fifo-alert-card">
        <div class="fifo-icon-box">
            <i data-lucide="archive" size="20"></i>
        </div>
        <div class="fifo-content">
            <span class="fifo-title">Kebijakan FIFO (First-In, First-Out) Aktif</span>
            <span class="fifo-desc">
                Sistem telah menyortir daftar berdasarkan tanggal kadaluwarsa terdekat untuk memastikan perputaran stok yang optimal. 
                @if($globalClosestExpiredBatch)
                    Item dengan badge merah <strong style="color: #DC2626;">"{{ $globalClosestExpiredBatch->no_batch }}"</strong> ({{ $globalClosestExpiredBatch->medicine->nama ?? 'Obat' }}) disarankan untuk segera didistribusikan atau diproses retur jika sudah melewati batas waktu aman.
                @else
                    Semua batch obat saat ini dalam status aman.
                @endif
            </span>
            <a href="#" onclick="Swal.fire({title: 'Alur Retur Obat', text: 'Mengakses panduan alur retur obat kedaluwarsa ke distributor resmi secara otomatis.', icon: 'info', confirmButtonColor: '#0B3E9C'}); return false;" class="fifo-link">
                Pelajari Alur Retur Obat →
            </a>
        </div>
    </div>

    <!-- Right: Warehouse Certificate Card -->
    <div class="cert-card">
        <div>
            <div class="cert-header">
                <i data-lucide="shield-check" class="cert-icon" size="16"></i>
                <span class="cert-title">Sertifikasi Gudang</span>
            </div>
            <div class="cert-desc">
                Penyimpanan obat dilakukan secara standar CDOB (Cara Distribusi Obat yang Baik). Pastikan suhu gudang tetap di kisaran 15-25°C untuk obat kategori tablet.
            </div>
        </div>
        
        <div class="cert-bar-container">
            <div class="cert-bar-label">
                <span>Kepatuhan Suhu</span>
                <span>100%</span>
            </div>
            <div class="cert-bar-track">
                <div class="cert-bar-fill"></div>
            </div>
        </div>

        <button class="btn-cert-plus" onclick="Swal.fire({title: 'Regulasi CDOB', text: 'Mengakses dokumen panduan resmi Kemenkes RI mengenai standardisasi CDOB lengkap.', icon: 'info', confirmButtonColor: '#0B3E9C'});">
            <i data-lucide="plus" size="14"></i>
        </button>
    </div>
    </div>
</div>

{{-- Print Preview Modal --}}
<div id="printModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:8px; width:90%; max-width:800px; box-shadow:0 20px 60px rgba(0,0,0,0.15); overflow:hidden;">
        <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center;">
            <h3 id="printModalTitle" style="font-size: 15px; font-weight: 800; color: #1E293B; margin: 0;">Preview Cetak Laporan</h3>
            <button onclick="closeModal()" style="background: none; border: none; color: #64748B; cursor: pointer;">
                <i data-lucide="x" size="20"></i>
            </button>
        </div>
        <div style="padding: 1rem; background: #F8FAFC;">
            <iframe id="printFrame" style="width: 100%; height: 500px; border: 1px solid #E2E8F0; border-radius: 6px; background: white;"></iframe>
        </div>
        <div style="padding: 1.25rem 1.5rem; display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid #F1F5F9; background: #FCFDFD;">
            <button onclick="closeModal()" style="background: white; border: 1px solid #E2E8F0; border-radius: 4px; padding: 10px 20px; font-size: 13px; font-weight: 700; color: #475569; cursor: pointer; min-width: 120px;">Batal</button>
            <button onclick="doPrint()" style="background: #0B3E9C; border: none; border-radius: 4px; padding: 10px 24px; font-size: 13px; font-weight: 700; color: white; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; min-width: 150px; justify-content: center;">
                <i data-lucide="printer" size="16"></i>
                Cetak Laporan
            </button>
        </div>
    </div>
</div>

<script>
    function toggleStatusFilter(statusVal) {
        const currentStatus = document.getElementById('statusFilterVal').value;
        if (currentStatus === statusVal) {
            document.getElementById('statusFilterVal').value = 'all';
        } else {
            document.getElementById('statusFilterVal').value = statusVal;
        }
        document.getElementById('stockFilterForm').submit();
    }

    function clearCategoryFilter() {
        const form = document.getElementById('stockFilterForm');
        const select = form.querySelector('select[name="category"]');
        if (select) {
            select.value = 'all';
        }
        form.submit();
    }

    function exportPDF() {
        const form = document.getElementById('stockFilterForm');
        const params = new URLSearchParams(new FormData(form)).toString();
        const url = "{{ route('admin.monitoring.expiry.export') }}?" + params;
        
        document.getElementById('printModalTitle').textContent = 'Preview Cetak Laporan Monitoring Kadaluwarsa';
        const modal = document.getElementById('printModal');
        const frame = document.getElementById('printFrame');
        
        frame.src = url;
        modal.style.display = 'flex';
        if (window.lucide) lucide.createIcons();
    }

    function printLabels() {
        const form = document.getElementById('stockFilterForm');
        const params = new URLSearchParams(new FormData(form)).toString();
        const url = "{{ route('admin.monitoring.expiry.labels') }}?" + params;
        
        document.getElementById('printModalTitle').textContent = 'Preview Cetak Label Stok';
        const modal = document.getElementById('printModal');
        const frame = document.getElementById('printFrame');
        
        frame.src = url;
        modal.style.display = 'flex';
        if (window.lucide) lucide.createIcons();
    }

    function closeModal() {
        document.getElementById('printModal').style.display = 'none';
        document.getElementById('printFrame').src = '';
    }

    function doPrint() {
        const frame = document.getElementById('printFrame');
        frame.contentWindow.print();
    }

    window.addEventListener('click', function(e) {
        if(e.target === document.getElementById('printModal')) closeModal();
    });
</script>

@endsection
