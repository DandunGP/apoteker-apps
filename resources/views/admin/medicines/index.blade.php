@extends('layouts.app')

@section('title', 'Kelola Data Obat')

@section('content')

<style>
    /* Sharp corners (radius 2px for buttons/badges), premium HSL colors, Outfit typography style */
    .medicines-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .medicines-title-area h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .medicines-title-area p {
        font-size: 13px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .btn-tambah-obat {
        background: #0056B3;
        color: white;
        border: none;
        border-radius: 4px; /* Sharp corporate look */
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-tambah-obat:hover {
        background: #004494;
    }

    /* KPI Grid of 4 */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 1.5rem;
    }

    .kpi-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 100px;
    }

    .kpi-title {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-value {
        font-size: 26px;
        font-weight: 800;
        line-height: 1;
        margin-top: 8px;
    }

    .kpi-blue { color: #0B3E9C; }
    .kpi-red { color: #EF4444; }
    .kpi-green { color: #16A34A; }
    .kpi-dark { color: #1E293B; }

    /* Interactive Filters & Actions Row */
    .table-actions-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 8px 12px;
    }

    .tab-filters {
        display: flex;
        gap: 6px;
    }

    .tab-btn {
        background: transparent;
        border: none;
        color: #64748B;
        font-size: 12px;
        font-weight: 700;
        padding: 6px 14px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .tab-btn:hover {
        background: #F1F5F9;
        color: #1E293B;
    }

    .tab-btn.active {
        background: #EBF2FC;
        color: #0F62FE;
    }

    .action-buttons-group {
        display: flex;
        gap: 8px;
    }

    .btn-action-outline {
        background: white;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        color: #475569;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-action-outline:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
        color: #1E293B;
    }

    /* Medicine Listing Table Container */
    .table-container {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .data-table-custom {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .data-table-custom th {
        text-align: left;
        color: #64748B;
        font-weight: 700;
        padding: 12px;
        border-bottom: 2px solid #E2E8F0;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .data-table-custom td {
        padding: 14px 12px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
    }

    .data-table-custom tr:last-child td {
        border-bottom: none;
    }

    .med-code-label {
        font-family: monospace;
        font-size: 12px;
        font-weight: 600;
        color: #475569;
    }

    .med-name-link {
        font-weight: 800;
        color: #0B3E9C;
        text-decoration: none;
        font-size: 14px;
    }

    .med-name-link:hover {
        text-decoration: underline;
    }

    /* Colorful Kategori Badges */
    .category-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        text-align: center;
    }

    .badge-obat-keras { background: #FEE2E2; color: #DC2626; }
    .badge-obat-bebas { background: #E0E8FF; color: #0F62FE; }
    .badge-obat-terbatas { background: #FEF3C7; color: #D97706; }
    .badge-suplemen { background: #DCFCE7; color: #16A34A; }
    .badge-alkes { background: #F1F5F9; color: #475569; }
    .badge-default { background: #FEF3C7; color: #D97706; }

    /* Slide-down Advanced Filter Panel Styles */
    .advanced-filter-panel {
        background: #F8FAFC;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: none;
        animation: slideDown 0.2s ease-out;
    }

    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-8px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .filter-grid-4 {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label {
        font-size: 9px;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-select {
        background: white;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 12px;
        font-weight: 700;
        color: #1E293B;
        outline: none;
        cursor: pointer;
    }

    /* Pagination Premium Styles */
    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        padding-top: 1rem;
        border-top: 1px solid #E2E8F0;
    }

    .pagination-info {
        font-size: 11px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pagination-info-wrapper {
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .rows-per-page-wrapper {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .rows-label {
        font-size: 10px;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .rows-select {
        background: white;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 4px 8px;
        font-size: 11px;
        font-weight: 700;
        color: #475569;
        outline: none;
        cursor: pointer;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .pag-btn {
        background: white;
        border: 1px solid #CBD5E1;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pag-btn:hover:not(:disabled) {
        background: #F8FAFC;
        border-color: #94A3B8;
        color: #0F62FE;
    }

    .pag-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pag-numbers {
        display: flex;
        gap: 4px;
    }

    .pag-number {
        background: white;
        border: 1px solid #CBD5E1;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .pag-number:hover {
        background: #F8FAFC;
        color: #0F62FE;
    }

    .pag-number.active {
        background: #0F62FE;
        border-color: #0F62FE;
        color: white;
    }

    /* Inline Stock Indicator & Progress Bar */
    .stock-indicator-wrapper {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 140px;
    }

    .stock-value-label {
        font-weight: 800;
        font-size: 13px;
        width: 32px;
    }

    .stock-value-label.low {
        color: #EF4444;
    }

    .stock-progress-track {
        flex: 1;
        height: 6px;
        background: #EEF2F6;
        border-radius: 3px;
        overflow: hidden;
    }

    .stock-progress-fill {
        height: 100%;
        border-radius: 3px;
    }

    .fill-success { background: #16A34A; }
    .fill-warning { background: #EAB308; }
    .fill-danger { background: #EF4444; }

    /* Action Buttons styling */
    .action-icon-btn {
        background: none;
        border: none;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .action-icon-btn:hover {
        background: #F1F5F9;
    }

    .action-edit { color: #0F62FE; }
    .action-delete { color: #EF4444; }

    /* Info Banner Box at bottom */
    .info-management-box {
        background: #EBF2FC;
        border: 1px solid #CFE2FE;
        border-radius: 4px;
        padding: 1.25rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .info-icon-wrapper {
        color: #0F62FE;
        display: flex;
        align-items: center;
        justify-content: center;
        padding-top: 2px;
    }

    .info-content h4 {
        font-size: 14px;
        font-weight: 800;
        color: #0B3E9C;
        margin: 0 0 4px 0;
    }

    .info-content p {
        font-size: 11.5px;
        color: #475569;
        margin: 0;
        line-height: 1.5;
        font-weight: 500;
    }
</style>

<!-- Top Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
    <span>&gt;</span>
    <span class="active">Kelola Data Obat</span>
</div>

<div class="medicines-header">
    <div class="medicines-title-area">
        <h1>Kelola Data Obat</h1>
        <p>Pusat manajemen inventaris obat dan alat kesehatan.</p>
    </div>
    <a href="{{ route('medicines.create') }}" class="btn-tambah-obat">
        <i data-lucide="plus" size="16"></i>
        <span>Tambah Obat</span>
    </a>
</div>

<!-- Dynamic KPI Row -->
<div class="kpi-grid">
    <div class="kpi-card">
        <span class="kpi-title">Total SKU</span>
        <div class="kpi-value kpi-blue">{{ number_format($metrics['total_sku'], 0, ',', '.') }}</div>
    </div>
    <div class="kpi-card">
        <span class="kpi-title">Stok Rendah</span>
        <div class="kpi-value kpi-red">{{ $metrics['stok_rendah'] }}</div>
    </div>
    <div class="kpi-card">
        <span class="kpi-title">Akan Kadaluwarsa</span>
        <div class="kpi-value kpi-green">{{ $metrics['akan_kadaluwarsa'] }}</div>
    </div>
    <div class="kpi-card">
        <span class="kpi-title">Kategori</span>
        <div class="kpi-value kpi-dark">{{ $metrics['total_kategori'] }}</div>
    </div>
</div>

<!-- Tabs & Actions Row -->
<div class="table-actions-row">
    <div class="tab-filters">
        <button class="tab-btn active" data-category="all">Semua</button>
        <button class="tab-btn" data-category="obat keras">Obat Keras</button>
        <button class="tab-btn" data-category="obat bebas">Obat Bebas</button>
        <button class="tab-btn" data-category="terbatas">Obat Terbatas</button>
        <button class="tab-btn" data-category="suplemen">Suplemen</button>
        <button class="tab-btn" data-category="alkes">Alat Kesehatan</button>
    </div>
    <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <!-- Beautiful Local Search Input -->
        <div class="local-search-wrapper" style="position: relative; width: 220px;">
            <i data-lucide="search" size="14" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748B;"></i>
            <input type="text" id="localSearchInput" placeholder="Cari obat..." style="width: 100%; padding: 8px 12px 8px 40px !important; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 12px; font-weight: 600; outline: none; background: #F8FAFC; box-sizing: border-box; transition: all 0.2s; height: 34px;">
            <button id="clearSearchBtn" type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; display: none; padding: 2px; align-items: center; justify-content: center;"><i data-lucide="x" size="14"></i></button>
        </div>
        
        <div class="action-buttons-group">
            <button type="button" class="btn-action-outline" id="btnToggleFilter" style="height: 34px;">
                <i data-lucide="filter" size="14"></i>
                <span>Filter</span>
            </button>
            <button type="button" class="btn-action-outline" onclick="openExportModal()" style="height: 34px;">
                <i data-lucide="download" size="14"></i>
                <span>Export</span>
            </button>
        </div>
    </div>
</div>

<!-- Slide-down Advanced Filter Panel -->
<div class="advanced-filter-panel" id="advancedFilterPanel">
    <div class="filter-grid-4">
        <div class="filter-group">
            <span class="filter-label">Kategori</span>
            <select class="filter-select" id="advCatFilter">
                <option value="all">Semua Kategori</option>
                <option value="obat keras">Obat Keras</option>
                <option value="obat bebas">Obat Bebas</option>
                <option value="terbatas">Obat Terbatas</option>
                <option value="suplemen">Suplemen</option>
                <option value="alkes">Alat Kesehatan</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Tingkat Stok</span>
            <select class="filter-select" id="advStockFilter">
                <option value="all">Semua Tingkat Stok</option>
                <option value="low">Stok Rendah (&lt; Min)</option>
                <option value="safe">Stok Aman (&gt;= Min)</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Satuan</span>
            <select class="filter-select" id="advUnitFilter">
                <option value="all">Semua Satuan</option>
                <option value="strip">Strip</option>
                <option value="botol">Botol</option>
                <option value="box">Box</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-label">Urutkan</span>
            <select class="filter-select" id="advSortFilter">
                <option value="name_asc">Nama (A-Z)</option>
                <option value="name_desc">Nama (Z-A)</option>
                <option value="stock_desc">Stok Terbanyak</option>
                <option value="stock_asc">Stok Tersedikit</option>
            </select>
        </div>
    </div>
</div>

<!-- Medicines Table Container -->
<div class="table-container">
    <table class="data-table-custom" id="medicinesTable">
        <thead>
            <tr>
                <th>ID Obat</th>
                <th>Nama Obat</th>
                <th>Kategori</th>
                <th>Stok</th>
                <th>Satuan</th>
                <th style="width: 80px; text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($medicines as $medicine)
                @php
                    // Dynamic stock percentage and progress colors
                    $stockVal = $medicine->total_stock;
                    $minStock = $medicine->min_stok;
                    
                    if ($stockVal <= 0) {
                        $percent = 0;
                        $fillColor = 'fill-danger';
                    } elseif ($stockVal <= $minStock) {
                        $percent = 20;
                        $fillColor = 'fill-danger';
                    } elseif ($stockVal <= $minStock * 2) {
                        $percent = 50;
                        $fillColor = 'fill-warning';
                    } else {
                        $percent = 85;
                        $fillColor = 'fill-success';
                    }
                    
                    // Assign accurate badges
                    $katLower = strtolower($medicine->kategori);
                    if (str_contains($katLower, 'keras')) {
                        $badgeClass = 'badge-obat-keras';
                    } elseif (str_contains($katLower, 'bebas')) {
                        $badgeClass = 'badge-obat-bebas';
                    } elseif (str_contains($katLower, 'terbatas')) {
                        $badgeClass = 'badge-obat-terbatas';
                    } elseif (str_contains($katLower, 'suplemen')) {
                        $badgeClass = 'badge-suplemen';
                    } elseif (str_contains($katLower, 'alkes') || str_contains($katLower, 'alat') || str_contains($katLower, 'kesehatan')) {
                        $badgeClass = 'badge-alkes';
                    } else {
                        $badgeClass = 'badge-default';
                    }
                @endphp
                <tr data-cat="{{ $katLower }}" data-stock="{{ $stockVal }}" data-min="{{ $minStock }}" data-unit="{{ strtolower($medicine->satuan) }}" data-name="{{ strtolower($medicine->nama) }}">
                    <td class="med-code-label">{{ $medicine->kode }}</td>
                    <td>
                        <a href="{{ route('medicines.edit', $medicine->id) }}" class="med-name-link">{{ $medicine->nama }}</a>
                    </td>
                    <td>
                        <span class="category-badge {{ $badgeClass }}">{{ $medicine->kategori }}</span>
                    </td>
                    <td>
                        <div class="stock-indicator-wrapper">
                            <span class="stock-value-label {{ $stockVal <= $minStock ? 'low' : '' }}">
                                {{ number_format($stockVal, 0, ',', '.') }}
                            </span>
                            <div class="stock-progress-track">
                                <div class="stock-progress-fill {{ $fillColor }}" style="width: {{ $percent }}%;"></div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight: 600; color: #475569;">{{ $medicine->satuan }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 12px; justify-content: center;">
                            <a href="{{ route('medicines.edit', $medicine->id) }}" class="action-icon-btn action-edit" title="Ubah">
                                <i data-lucide="pencil" size="16"></i>
                            </a>
                            <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST" class="delete-form" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="action-icon-btn action-delete btn-delete-med" title="Hapus">
                                    <i data-lucide="trash-2" size="16"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 3rem; color: #64748B; font-weight: 600;">
                        Belum ada data obat terdaftar.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination Controls -->
    <div class="pagination-container" id="tablePagination">
        <div class="pagination-info-wrapper">
            <div class="pagination-info" id="paginationInfo">Menampilkan 1 sampai 10 dari 10 data</div>
            <div class="rows-per-page-wrapper">
                <span class="rows-label">Tampilkan:</span>
                <select class="rows-select" id="rowsPerPageSelect">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        <div class="pagination-controls" id="paginationControls">
            <button class="pag-btn" id="btnPrevPage">&laquo; Prev</button>
            <div class="pag-numbers" id="paginationNumbers"></div>
            <button class="pag-btn" id="btnNextPage">Next &raquo;</button>
        </div>
    </div>
</div>

<!-- Bottom Info Card -->
<div class="info-management-box">
    <div class="info-icon-wrapper">
        <i data-lucide="info" size="20"></i>
    </div>
    <div class="info-content">
        <h4>Informasi Manajemen Data</h4>
        <p>Semua data obat disinkronkan secara real-time dengan sistem Point of Sale (POS) depan. Pastikan kategori dan satuan benar sebelum menyimpan untuk menjaga akurasi laporan stok opname bulanan.</p>
    </div>
</div>

<!-- Print Preview Modal -->
<div id="printModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; justify-content: center; align-items: center; padding: 2rem;">
    <div style="background: white; width: 100%; max-width: 900px; height: 90vh; border-radius: 16px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <!-- Modal Header -->
        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center; background: #F8FAFC;">
            <h3 style="font-size: 16px; font-weight: 700; display: flex; align-items: center; gap: 8px; margin: 0;">
                <i data-lucide="file-text" size="18" style="color: #0F62FE;"></i>
                Preview Laporan Data Obat
            </h3>
            <button onclick="closePrintModal()" style="background: none; border: none; cursor: pointer; color: #64748B; padding: 4px; display: flex; align-items: center; justify-content: center; border-radius: 6px; transition: background 0.2s;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='transparent'">
                <i data-lucide="x" size="20"></i>
            </button>
        </div>
        
        <!-- Modal Body (Iframe) -->
        <div style="flex: 1; position: relative; background: #f3f4f6;">
            <iframe id="printFrame" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            <div id="printLoader" style="position: absolute; inset: 0; background: white; display: flex; justify-content: center; align-items: center;">
                <i data-lucide="loader-2" size="24" class="spin" style="color: #0F62FE; animation: spin 1s linear infinite;"></i>
            </div>
        </div>

        <!-- Modal Footer -->
        <div style="padding: 1rem 1.5rem; border-top: 1px solid #E2E8F0; background: white; display: flex; justify-content: flex-end; gap: 1rem;">
            <button onclick="closePrintModal()" class="btn-action-outline" style="padding: 8px 16px; height: auto;">
                Tutup
            </button>
            <button onclick="executePrint()" class="btn-tambah-obat" style="display: flex; align-items: center; gap: 8px; padding: 8px 16px; height: auto; text-decoration: none; border: none; font-size: 13px; font-weight: 600; cursor: pointer;">
                <i data-lucide="printer" size="18"></i>
                Cetak Laporan Sekarang
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>

@endsection

@push('scripts')
<script>
    function openExportModal() {
        const modal = document.getElementById('printModal');
        const frame = document.getElementById('printFrame');
        const loader = document.getElementById('printLoader');
        
        modal.style.display = 'flex';
        loader.style.display = 'flex';
        
        frame.src = "{{ route('medicines.export') }}";
        
        frame.onload = function() {
            loader.style.display = 'none';
        };
    }

    function closePrintModal() {
        const modal = document.getElementById('printModal');
        const frame = document.getElementById('printFrame');
        
        modal.style.display = 'none';
        frame.src = '';
    }

    function executePrint() {
        const frame = document.getElementById('printFrame');
        if (frame.contentWindow) {
            frame.contentWindow.focus();
            frame.contentWindow.print();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        const tabButtons = document.querySelectorAll(".tab-btn");
        const tableRows = document.querySelectorAll("#medicinesTable tbody tr");

        // Toggle Advanced Filter Panel
        const btnToggleFilter = document.getElementById("btnToggleFilter");
        const advancedFilterPanel = document.getElementById("advancedFilterPanel");

        if (btnToggleFilter && advancedFilterPanel) {
            btnToggleFilter.addEventListener("click", function() {
                const isHidden = window.getComputedStyle(advancedFilterPanel).display === "none";
                advancedFilterPanel.style.display = isHidden ? "block" : "none";
                btnToggleFilter.classList.toggle("active", isHidden);
            });
        }

        // Pagination State
        // Pagination State
        let currentPage = 1;
        let rowsPerPage = 10;

        const rowsSelect = document.getElementById("rowsPerPageSelect");
        if (rowsSelect) {
            rowsPerPage = parseInt(rowsSelect.value);
            rowsSelect.addEventListener("change", function() {
                rowsPerPage = parseInt(this.value);
                currentPage = 1;
                updatePagination();
            });
        }

        function updatePagination() {
            const allRows = Array.from(document.querySelectorAll("#medicinesTable tbody tr"));
            const visibleRows = allRows.filter(row => row.cells.length > 1 && row.getAttribute("data-filtered") !== "false");
            
            const totalRows = visibleRows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            // Hide all rows
            allRows.forEach(row => {
                if (row.cells.length > 1) {
                    row.style.display = "none";
                }
            });

            // Show page rows
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, totalRows);

            for (let i = startIndex; i < endIndex; i++) {
                if (visibleRows[i]) {
                    visibleRows[i].style.display = "";
                }
            }

            // Update Info text
            const infoEl = document.getElementById("paginationInfo");
            if (infoEl) {
                if (totalRows === 0) {
                    infoEl.textContent = "Menampilkan 0 sampai 0 dari 0 data";
                } else {
                    infoEl.textContent = `Menampilkan ${startIndex + 1} sampai ${endIndex} dari ${totalRows} data`;
                }
            }

            // Update prev/next button states
            const btnPrev = document.getElementById("btnPrevPage");
            const btnNext = document.getElementById("btnNextPage");
            if (btnPrev) btnPrev.disabled = (currentPage === 1);
            if (btnNext) btnNext.disabled = (currentPage === totalPages);

            // Render numbers
            const numbersContainer = document.getElementById("paginationNumbers");
            if (numbersContainer) {
                numbersContainer.innerHTML = "";
                
                const maxVisiblePages = 5;
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
                
                if (endPage - startPage + 1 < maxVisiblePages) {
                    startPage = Math.max(1, endPage - maxVisiblePages + 1);
                }

                for (let page = startPage; page <= endPage; page++) {
                    const btn = document.createElement("button");
                    btn.className = `pag-number ${page === currentPage ? 'active' : ''}`;
                    btn.textContent = page;
                    btn.addEventListener("click", function() {
                        currentPage = page;
                        updatePagination();
                    });
                    numbersContainer.appendChild(btn);
                }
            }
        }

        // Bind Prev/Next button clicks
        const btnPrev = document.getElementById("btnPrevPage");
        const btnNext = document.getElementById("btnNextPage");
        if (btnPrev) {
            btnPrev.addEventListener("click", function() {
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                }
            });
        }
        if (btnNext) {
            btnNext.addEventListener("click", function() {
                const allRows = Array.from(document.querySelectorAll("#medicinesTable tbody tr")).filter(row => row.cells.length > 1 && row.getAttribute("data-filtered") !== "false");
                const totalPages = Math.ceil(allRows.length / rowsPerPage) || 1;
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                }
            });
        }

        // Global Reactive Filter Handler
        function applyFilters() {
            const activeTab = document.querySelector(".tab-btn.active");
            let catVal = activeTab ? activeTab.getAttribute("data-category") : "all";

            const advCat = document.getElementById("advCatFilter").value;
            if (advCat !== "all") {
                catVal = advCat;
            }

            const stockFilter = document.getElementById("advStockFilter").value;
            const unitFilter = document.getElementById("advUnitFilter").value;
            const searchQuery = document.getElementById("localSearchInput").value.toLowerCase().trim();
            const clearBtn = document.getElementById("clearSearchBtn");
            if (clearBtn) {
                clearBtn.style.display = searchQuery ? "flex" : "none";
            }

            tableRows.forEach(row => {
                if (row.cells.length <= 1) return;

                const rowCat = row.getAttribute("data-cat") || '';
                const rowStock = parseInt(row.getAttribute("data-stock") || '0');
                const rowMin = parseInt(row.getAttribute("data-min") || '0');
                const rowUnit = row.getAttribute("data-unit") || '';
                const rowName = row.getAttribute("data-name") || '';
                const rowCode = (row.cells[0].textContent || '').toLowerCase();

                // 1. Kategori Filter
                let matchesCat = false;
                if (catVal === "all") {
                    matchesCat = true;
                } else if (catVal === "alkes") {
                    matchesCat = rowCat.includes("alkes") || rowCat.includes("alat") || rowCat.includes("kesehatan");
                } else {
                    matchesCat = rowCat.includes(catVal);
                }

                // 2. Stok Filter
                let matchesStock = false;
                if (stockFilter === "all") {
                    matchesStock = true;
                } else if (stockFilter === "low") {
                    matchesStock = rowStock <= rowMin;
                } else if (stockFilter === "safe") {
                    matchesStock = rowStock > rowMin;
                }

                // 3. Unit Filter
                let matchesUnit = false;
                if (unitFilter === "all") {
                    matchesUnit = true;
                } else {
                    matchesUnit = rowUnit === unitFilter;
                }

                // 4. Search Filter
                let matchesSearch = true;
                if (searchQuery !== "") {
                    matchesSearch = rowName.includes(searchQuery) || rowCode.includes(searchQuery);
                }

                if (matchesCat && matchesStock && matchesUnit && matchesSearch) {
                    row.setAttribute("data-filtered", "true");
                } else {
                    row.setAttribute("data-filtered", "false");
                }
            });

            currentPage = 1;
            applySorting();
        }

        // Dynamic Sorting Handler
        function applySorting() {
            const sortVal = document.getElementById("advSortFilter").value;
            const tbody = document.querySelector("#medicinesTable tbody");
            const rowsArray = Array.from(tbody.querySelectorAll("tr"));

            if (rowsArray.length <= 1) return;

            rowsArray.sort((a, b) => {
                if (a.cells.length <= 1 || b.cells.length <= 1) return 0;

                if (sortVal === "name_asc") {
                    return a.getAttribute("data-name").localeCompare(b.getAttribute("data-name"));
                } else if (sortVal === "name_desc") {
                    return b.getAttribute("data-name").localeCompare(a.getAttribute("data-name"));
                } else if (sortVal === "stock_desc") {
                    return parseInt(b.getAttribute("data-stock")) - parseInt(a.getAttribute("data-stock"));
                } else if (sortVal === "stock_asc") {
                    return parseInt(a.getAttribute("data-stock")) - parseInt(b.getAttribute("data-stock"));
                }
                return 0;
            });

            rowsArray.forEach(row => tbody.appendChild(row));
            updatePagination();
        }

        // Initial setup
        applyFilters();

        // Event listeners for tabs
        tabButtons.forEach(button => {
            button.addEventListener("click", function() {
                tabButtons.forEach(btn => btn.classList.remove("active"));
                this.classList.add("active");
                
                // Sync the advanced category filter value
                const catVal = this.getAttribute("data-category");
                const advSelect = document.getElementById("advCatFilter");
                if (advSelect) {
                    if (["obat keras", "obat bebas", "terbatas", "suplemen", "alkes"].includes(catVal)) {
                        advSelect.value = catVal;
                    } else {
                        advSelect.value = "all";
                    }
                }

                applyFilters();
            });
        });

        // Event listeners for advanced filters inputs
        const filterSelects = ["advCatFilter", "advStockFilter", "advUnitFilter", "advSortFilter"];
        filterSelects.forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener("change", function() {
                    // If category select changes, sync it back to tab active states
                    if (id === "advCatFilter") {
                        const selectedVal = this.value;
                        tabButtons.forEach(btn => {
                            if (btn.getAttribute("data-category") === selectedVal) {
                                btn.classList.add("active");
                            } else {
                                btn.classList.remove("active");
                            }
                        });
                    }
                    applyFilters();
                });
            }
        });

        // Search Input Handlers
        const searchInput = document.getElementById("localSearchInput");
        if (searchInput) {
            searchInput.addEventListener("input", function() {
                applyFilters();
            });
        }

        const clearSearchBtn = document.getElementById("clearSearchBtn");
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener("click", function() {
                searchInput.value = "";
                applyFilters();
                searchInput.focus();
            });
        }

        // Delete Alert SweetAlert
        document.querySelectorAll('.btn-delete-med').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-form');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data obat ini akan dihapus secara permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#EF4444',
                    cancelButtonColor: '#64748B',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endpush
