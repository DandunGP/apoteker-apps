@extends('layouts.app')

@section('title', 'Kelola Supplier')

@section('content')

<style>
    /* Sharp corners (radius 2px for buttons/cards), HSL clinical blue palettes */
    .suppliers-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .breadcrumb {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        display: flex;
        align-items: center;
        gap: 4px;
        margin: 0 0 1rem 0;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .breadcrumb a {
        color: #64748B;
        text-decoration: none;
    }

    .breadcrumb span.active {
        color: #0F62FE;
    }

    .header-area {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .title-area h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .title-area p {
        font-size: 13px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .btn-tambah-supplier {
        background: #0056B3;
        color: white;
        border: none;
        border-radius: 4px;
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

    .btn-tambah-supplier:hover {
        background: #004494;
    }

    /* Filters Bar Row */
    .filters-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
    }

    .main-filter-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .filter-inputs {
        display: flex;
        gap: 12px;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .filter-label {
        font-size: 9px;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-select {
        background: #EEF2F6;
        border: none;
        border-radius: 4px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 700;
        color: #1E293B;
        outline: none;
        cursor: pointer;
        min-width: 140px;
    }

    .total-supplier-badge {
        display: flex;
        align-items: center;
        gap: 1rem;
        border-left: 1px solid #E2E8F0;
        padding-left: 1.5rem;
    }

    .total-sup-label {
        font-size: 10px;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
    }

    .total-sup-val {
        font-size: 20px;
        font-weight: 800;
        color: #1E293B;
    }

    .total-sup-val span {
        font-size: 12px;
        font-weight: 600;
        color: #64748B;
    }

    .evaluasi-card {
        background: #0B3E9C;
        border-radius: 4px;
        padding: 1.25rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .eval-content {
        z-index: 2;
    }

    .eval-title {
        font-size: 9px;
        font-weight: 800;
        color: #93C5FD;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .eval-date {
        font-size: 20px;
        font-weight: 800;
        margin: 4px 0;
    }

    .eval-desc {
        font-size: 11px;
        color: #DBEAFE;
        font-weight: 500;
    }

    .eval-icon {
        color: rgba(255, 255, 255, 0.1);
        z-index: 1;
    }

    /* Suppliers Table */
    .table-container {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.5rem;
    }

    .suppliers-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .suppliers-table th {
        text-align: left;
        color: #64748B;
        font-weight: 700;
        padding: 12px;
        border-bottom: 2px solid #E2E8F0;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .suppliers-table td {
        padding: 16px 12px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
        color: #334155;
    }

    .suppliers-table tr:last-child td {
        border-bottom: none;
    }

    .supplier-name-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .prefix-badge {
        width: 32px;
        height: 32px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 800;
    }

    .prefix-pt { background: #E0E8FF; color: #0F62FE; }
    .prefix-cv { background: #F1F5F9; color: #475569; }
    .prefix-pb { background: #E0F2FE; color: #0284C7; }
    .prefix-default { background: #FEF3C7; color: #D97706; }

    .sup-name-text {
        font-weight: 800;
        color: #0B3E9C;
        font-size: 14px;
        text-decoration: none;
    }

    .sup-name-text:hover {
        text-decoration: underline;
    }

    /* Status Badges with Dots */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 800;
    }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }

    .status-aktif { background: #DCFCE7; color: #16A34A; }
    .status-aktif::before { background: #16A34A; }

    .status-non-aktif { background: #FEE2E2; color: #EF4444; }
    .status-non-aktif::before { background: #EF4444; }

    /* Action Buttons */
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

    /* Bottom 3 Grid Row */
    .bottom-metrics-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }

    .bottom-metric-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.25rem;
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .metric-icon-box {
        width: 46px;
        height: 46px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .icon-box-blue { background: #E0E8FF; color: #0F62FE; }
    .icon-box-green { background: #DCFCE7; color: #16A34A; }
    .icon-box-gray { background: #F1F5F9; color: #475569; }

    .metric-content-box {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .metric-content-title {
        font-size: 13px;
        font-weight: 800;
        color: #1E293B;
    }

    .metric-content-val {
        font-size: 11px;
        color: #64748B;
        margin-top: 2px;
        font-weight: 600;
    }

    .metric-content-sub {
        font-size: 10px;
        font-weight: 800;
        margin-top: 10px;
        padding-top: 8px;
        border-top: 1px solid #F1F5F9;
    }

    .metric-blue-text { color: #0F62FE; }
    .metric-green-text { color: #16A34A; }
    .metric-gray-text { color: #475569; }

    /* Custom progress horizontal bar */
    .compliance-bar-track {
        width: 100%;
        height: 4px;
        background: #EEF2F6;
        border-radius: 2px;
        margin-top: 10px;
        overflow: hidden;
    }

    .compliance-bar-fill {
        height: 100%;
        background: #16A34A;
        border-radius: 2px;
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
</style>

<div class="suppliers-container">
    
    <!-- Top Breadcrumb & Title Area -->
    <div>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
            <span>&gt;</span>
            <span class="active">Kelola Supplier</span>
        </div>
        
        <div class="header-area">
            <div class="title-area">
                <h1>Kelola Supplier</h1>
                <p>Manajemen daftar pemasok obat dan alat kesehatan.</p>
            </div>
            <a href="{{ route('suppliers.create') }}" class="btn-tambah-supplier">
                <i data-lucide="plus" size="16"></i>
                <span>Tambah Supplier</span>
            </a>
        </div>
    </div>

    <!-- Filters & Evaluasi Row -->
    <div class="filters-grid">
        <!-- Filters Card -->
        <div class="main-filter-card">
            <div class="filter-inputs" style="align-items: flex-end;">
                <!-- Beautiful Local Search Input -->
                <div class="filter-group">
                    <span class="filter-label">Cari Supplier</span>
                    <div class="local-search-wrapper" style="position: relative; width: 220px;">
                        <i data-lucide="search" size="14" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748B;"></i>
                        <input type="text" id="localSearchInput" placeholder="Cari nama/kontak..." style="width: 100%; padding: 8px 12px 8px 40px !important; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 12px; font-weight: 600; outline: none; background: #EEF2F6; box-sizing: border-box; transition: all 0.2s; height: 34px;">
                        <button id="clearSearchBtn" type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; display: none; padding: 2px; align-items: center; justify-content: center;"><i data-lucide="x" size="14"></i></button>
                    </div>
                </div>

                <div class="filter-group">
                    <span class="filter-label">Status</span>
                    <select class="filter-select" id="statusFilter" style="height: 34px;">
                        <option value="all">Semua Status</option>
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>
                <div class="filter-group">
                    <span class="filter-label">Urutkan</span>
                    <select class="filter-select" id="sortFilter" style="height: 34px;">
                        <option value="nama_asc" {{ $sort === 'nama_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                        <option value="nama_desc" {{ $sort === 'nama_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                    </select>
                </div>
            </div>
            
            <div class="total-supplier-badge">
                <div>
                    <span class="total-sup-label">Total Supplier</span>
                    <div class="total-sup-val">{{ $metrics['total_supplier'] }} <span>Entitas</span></div>
                </div>
                <div class="prefix-badge prefix-pt" style="width: 36px; height: 36px;">
                    <i data-lucide="users" size="18"></i>
                </div>
            </div>
        </div>

        <!-- Evaluasi Card -->
        <div class="evaluasi-card">
            <div class="eval-content">
                <span class="eval-title">Evaluasi Terakhir</span>
                <div class="eval-date">12 Okt 2023</div>
                <div class="eval-desc">Sertifikasi GDAK Terverifikasi</div>
            </div>
            <div class="eval-icon">
                <i data-lucide="check-circle" size="48"></i>
            </div>
        </div>
    </div>

    <!-- Suppliers Table Container -->
    <div class="table-container">
        <table class="suppliers-table" id="suppliersTable">
            <thead>
                <tr>
                    <th>Nama Supplier</th>
                    <th>Kontak Person</th>
                    <th>No. Telepon</th>
                    <th>Alamat</th>
                    <th>Status</th>
                    <th style="width: 80px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($suppliers as $supplier)
                    @php
                        $prefix = strtolower($supplier->prefix);
                        if ($prefix === 'pt') {
                            $prefixClass = 'prefix-pt';
                        } elseif ($prefix === 'cv') {
                            $prefixClass = 'prefix-cv';
                        } elseif ($prefix === 'pb' || $prefix === 'pbf') {
                            $prefixClass = 'prefix-pb';
                        } else {
                            $prefixClass = 'prefix-default';
                        }
                    @endphp
                    <tr data-status="{{ $supplier->status }}">
                        <td>
                            <div class="supplier-name-cell">
                                <div class="prefix-badge {{ $prefixClass }}">{{ $supplier->prefix }}</div>
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="sup-name-text">{{ $supplier->clean_name }}</a>
                            </div>
                        </td>
                        <td style="font-weight: 600;">{{ $supplier->kontak_person }}</td>
                        <td style="font-weight: 600; color: #475569;">{{ $supplier->no_telepon }}</td>
                        <td style="max-width: 250px; text-overflow: ellipsis; overflow: hidden; white-space: nowrap;">{{ $supplier->alamat }}</td>
                        <td>
                            <span class="status-badge {{ $supplier->status === 'Aktif' ? 'status-aktif' : 'status-non-aktif' }}">
                                {{ $supplier->status }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 12px; justify-content: center;">
                                <a href="{{ route('suppliers.edit', $supplier->id) }}" class="action-icon-btn action-edit" title="Ubah">
                                    <i data-lucide="pencil" size="16"></i>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="delete-supplier-form" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="action-icon-btn action-delete btn-delete-sup" title="Hapus">
                                        <i data-lucide="trash-2" size="16"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #64748B; font-weight: 600;">
                            Belum ada data supplier terdaftar.
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

    <!-- Bottom 3 Info Grid Row -->
    <div class="bottom-metrics-row">
        <!-- 1. PBF Teraktif -->
        <div class="bottom-metric-card">
            <div class="metric-icon-box icon-box-blue">
                <i data-lucide="warehouse" size="20"></i>
            </div>
            <div class="metric-content-box">
                <span class="metric-content-title">PBF Teraktif</span>
                <span class="metric-content-val">PT. Kimia Farma Trading</span>
                <span class="metric-content-sub metric-blue-text">85% dari total pesanan masuk</span>
            </div>
        </div>

        <!-- 2. Kepatuhan -->
        <div class="bottom-metric-card" style="display: block;">
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div class="metric-icon-box icon-box-green">
                    <i data-lucide="shield-check" size="20"></i>
                </div>
                <div class="metric-content-box">
                    <span class="metric-content-title">Kepatuhan</span>
                    <span class="metric-content-val">98% Dokumen Lengkap</span>
                </div>
            </div>
            <div class="compliance-bar-track">
                <div class="compliance-bar-fill" style="width: 98%;"></div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 10px; font-weight: 800; color: #16A34A; margin-top: 8px; border-top: 1px solid #F1F5F9; padding-top: 8px;">
                <span>Sangat Baik (Top Tier)</span>
            </div>
        </div>

        <!-- 3. Update Terakhir -->
        <div class="bottom-metric-card">
            <div class="metric-icon-box icon-box-gray">
                <i data-lucide="refresh-cw" size="20"></i>
            </div>
            <div class="metric-content-box">
                <span class="metric-content-title">Update Terakhir</span>
                <span class="metric-content-val">Sistem Supplier</span>
                <span class="metric-content-sub metric-gray-text">Sinkronisasi: 2 jam yang lalu</span>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Pagination State
        let currentPage = 1;
        let rowsPerPage = 10;
        const tableRows = document.querySelectorAll("#suppliersTable tbody tr");

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
            const allRows = Array.from(document.querySelectorAll("#suppliersTable tbody tr"));
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
                const allRows = Array.from(document.querySelectorAll("#suppliersTable tbody tr")).filter(row => row.cells.length > 1 && row.getAttribute("data-filtered") !== "false");
                const totalPages = Math.ceil(allRows.length / rowsPerPage) || 1;
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                }
            });
        }

        function applyFilters() {
            const statusFilter = document.getElementById("statusFilter");
            const targetStatus = statusFilter.value;
            const searchQuery = document.getElementById("localSearchInput").value.toLowerCase().trim();
            const clearBtn = document.getElementById("clearSearchBtn");
            if (clearBtn) {
                clearBtn.style.display = searchQuery ? "flex" : "none";
            }

            tableRows.forEach(row => {
                if (row.cells.length <= 1) return;
                
                const rowStatus = row.getAttribute("data-status");
                const rowName = (row.cells[0].textContent || "").toLowerCase();
                const rowContact = (row.cells[1].textContent || "").toLowerCase();
                const rowPhone = (row.cells[2].textContent || "").toLowerCase();

                // 1. Status Filter
                let matchesStatus = (targetStatus === "all" || rowStatus === targetStatus);

                // 2. Search Filter
                let matchesSearch = true;
                if (searchQuery !== "") {
                    matchesSearch = rowName.includes(searchQuery) || rowContact.includes(searchQuery) || rowPhone.includes(searchQuery);
                }

                if (matchesStatus && matchesSearch) {
                    row.setAttribute("data-filtered", "true");
                } else {
                    row.setAttribute("data-filtered", "false");
                }
            });
            currentPage = 1;
            updatePagination();
        }

        const statusFilter = document.getElementById("statusFilter");
        statusFilter.addEventListener("change", applyFilters);

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

        applyFilters();

        // Sorting Option Refresh
        const sortFilter = document.getElementById("sortFilter");
        sortFilter.addEventListener("change", function() {
            window.location.href = "{{ route('suppliers.index') }}?sort=" + this.value;
        });

        // SweetAlert Delete Supplier Confirmation
        document.querySelectorAll('.btn-delete-sup').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-supplier-form');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "Data supplier ini akan dihapus secara permanen!",
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
