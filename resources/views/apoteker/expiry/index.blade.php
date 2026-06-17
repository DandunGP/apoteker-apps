@extends('layouts.app')

@section('title', 'Alert Expiry')

@section('content')

<style>
    .breadcrumb {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        display: flex;
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
        margin-bottom: 1.5rem;
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

    /* Metric Cards */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .metric-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.25rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .metric-label {
        color: #64748B;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-val {
        font-size: 28px;
        font-weight: 800;
        margin-top: 0.5rem;
    }

    /* Table Container Styles */
    .table-container-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.5rem;
    }

    .data-table th {
        text-align: left;
        padding: 12px 16px;
        font-size: 11px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        border-bottom: 2px solid #E2E8F0;
        letter-spacing: 0.5px;
        background: #F8FAFC;
    }

    .data-table td {
        padding: 14px 16px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #E2E8F0;
        vertical-align: middle;
    }

    .data-table tbody tr:hover {
        background-color: #F8FAFC;
    }

    .batch-badge {
        font-size: 11px;
        font-weight: 800;
        background: #E0E8FF;
        color: #0F62FE;
        padding: 3px 8px;
        border-radius: 4px;
        border: 1px solid #C6D4FF;
        text-transform: uppercase;
    }

    .status-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        text-align: center;
    }

    .pill-safe { background: #ECFDF5; color: #10B981; }
    .pill-warning { background: #FEF3C7; color: #D97706; }
    .pill-danger { background: #FEE2E2; color: #EF4444; }

    /* Pagination Controls */
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

<div>
    <!-- Top Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
        <span>&gt;</span>
        <span class="active">Alert Expiry</span>
    </div>

    <!-- Header Area -->
    <div class="header-area">
        <div class="title-area">
            <h1>Alert Expiry (FEFO)</h1>
            <p>Monitor obat berdasarkan tanggal kadaluwarsa terdekat untuk mencegah kerugian.</p>
        </div>
    </div>

    <!-- Metrics Cards Grid -->
    <div class="metrics-grid">
        <div class="metric-card" style="border-left: 4px solid #EF4444;">
            <div class="metric-label">Sudah Kadaluwarsa</div>
            <div class="metric-val" style="color: #EF4444;">{{ $expiredCount }}</div>
        </div>
        <div class="metric-card" style="border-left: 4px solid #D97706;">
            <div class="metric-label">Stok (Kadaluwarsa &lt; 3 Bulan)</div>
            <div class="metric-val" style="color: #D97706;">{{ $nearExpiryCount }}</div>
        </div>
        <div class="metric-card" style="border-left: 4px solid #10B981;">
            <div class="metric-label">Stok (Kadaluwarsa &gt; 3 Bulan)</div>
            <div class="metric-val" style="color: #10B981;">{{ $safeCount }}</div>
        </div>
    </div>

    <!-- Main Data Table Card -->
    <div class="table-container-card">
        <!-- Gorgeous Table Actions & Local Search Row -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid #F1F5F9; gap: 1rem; flex-wrap: wrap;">
            <!-- Beautiful Local Search Input -->
            <div class="local-search-wrapper" style="position: relative; width: 280px;">
                <i data-lucide="search" size="14" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748B;"></i>
                <input type="text" id="localSearchInput" placeholder="Cari batch atau nama obat..." style="width: 100%; padding: 8px 12px 8px 40px !important; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 12.5px; font-weight: 600; outline: none; background: #F8FAFC; box-sizing: border-box; transition: all 0.2s; height: 34px;">
                <button id="clearSearchBtn" type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; display: none; padding: 2px; align-items: center; justify-content: center;"><i data-lucide="x" size="14"></i></button>
            </div>
            
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 10px; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Status:</span>
                    <select id="statusFilter" style="background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 4px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: #475569; outline: none; cursor: pointer; height: 34px;">
                        <option value="all">Semua Status</option>
                        <option value="Expired">Expired</option>
                        <option value="Critical">Critical</option>
                        <option value="Safe">Safe</option>
                    </select>
                </div>
            </div>
        </div>

        <table class="data-table" id="expiryTable">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 150px;">No. Batch</th>
                    <th>Nama Obat</th>
                    <th style="width: 130px; text-align: center;">Stok Sisa</th>
                    <th style="width: 200px;">Tgl Kadaluwarsa</th>
                    <th style="width: 150px; text-align: center;">Sisa Hari</th>
                    <th style="width: 130px; text-align: center;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                    @php
                        if ($batch->diff_days < 0) {
                            $statusText = 'Expired';
                            $statusPill = 'pill-danger';
                        } elseif ($batch->diff_days < 90) {
                            $statusText = 'Critical';
                            $statusPill = 'pill-warning';
                        } else {
                            $statusText = 'Safe';
                            $statusPill = 'pill-safe';
                        }
                    @endphp
                    <tr>
                        <td style="color: #64748B; font-weight: 600;">{{ $loop->iteration }}</td>
                        <td><span class="batch-badge">{{ $batch->no_batch }}</span></td>
                        <td style="font-weight: 700; color: #1E293B;">{{ $batch->medicine->nama }}</td>
                        <td style="text-align: center; font-weight: 700;">{{ $batch->stok_sisa }}</td>
                        <td style="font-weight: 600; color: #475569;">{{ $batch->expiry_formatted }}</td>
                        <td style="text-align: center; font-weight: 700; color: {{ $batch->diff_days < 0 ? '#EF4444' : '#475569' }};">
                            @if($batch->diff_days < 0)
                                Lampau
                            @else
                                {{ $batch->diff_days }} Hari
                            @endif
                        </td>
                        <td style="text-align: center;">
                            <span class="status-pill {{ $statusPill }}">{{ $statusText }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: #64748B; font-weight: 600;">
                            Tidak ada batch obat aktif terdaftar.
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
</div>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentPage = 1;
        let rowsPerPage = 10;
        const allRows = Array.from(document.querySelectorAll("#expiryTable tbody tr"));

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
            const visibleRows = allRows.filter(row => row.cells.length > 1 && row.getAttribute("data-filtered") !== "false");
            const totalRows = visibleRows.length;
            const totalPages = Math.ceil(totalRows / rowsPerPage) || 1;

            if (currentPage > totalPages) {
                currentPage = totalPages;
            }

            allRows.forEach(row => {
                if (row.cells.length > 1) {
                    row.style.display = "none";
                }
            });

            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = Math.min(startIndex + rowsPerPage, totalRows);

            for (let i = startIndex; i < endIndex; i++) {
                if (visibleRows[i]) {
                    visibleRows[i].style.display = "";
                }
            }

            const infoEl = document.getElementById("paginationInfo");
            if (infoEl) {
                if (totalRows === 0) {
                    infoEl.textContent = "Menampilkan 0 sampai 0 dari 0 data";
                } else {
                    infoEl.textContent = `Menampilkan ${startIndex + 1} sampai ${endIndex} dari ${totalRows} data`;
                }
            }

            const btnPrev = document.getElementById("btnPrevPage");
            const btnNext = document.getElementById("btnNextPage");
            if (btnPrev) btnPrev.disabled = (currentPage === 1);
            if (btnNext) btnNext.disabled = (currentPage === totalPages);

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
                const allVisible = allRows.filter(row => row.cells.length > 1 && row.getAttribute("data-filtered") !== "false");
                const totalPages = Math.ceil(allVisible.length / rowsPerPage) || 1;
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                }
            });
        }

        // Live Filter & Local Search Implementation
        function applyFilters() {
            const statusFilter = document.getElementById("statusFilter");
            const targetStatus = statusFilter ? statusFilter.value : "all";
            const searchQuery = document.getElementById("localSearchInput").value.toLowerCase().trim();
            const clearBtn = document.getElementById("clearSearchBtn");
            if (clearBtn) {
                clearBtn.style.display = searchQuery ? "flex" : "none";
            }

            allRows.forEach(row => {
                if (row.cells.length <= 1) return;
                
                const rowBatch = (row.cells[1].textContent || "").toLowerCase();
                const rowName = (row.cells[2].textContent || "").toLowerCase();
                const rowStatus = (row.cells[6].textContent || "").trim(); // Expired, Critical, Safe

                // 1. Status Filter
                let matchesStatus = (targetStatus === "all" || rowStatus.includes(targetStatus));

                // 2. Search Filter
                let matchesSearch = true;
                if (searchQuery !== "") {
                    matchesSearch = rowBatch.includes(searchQuery) || rowName.includes(searchQuery);
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
        if (statusFilter) {
            statusFilter.addEventListener("change", applyFilters);
        }

        const searchInput = document.getElementById("localSearchInput");
        if (searchInput) {
            searchInput.addEventListener("input", applyFilters);
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
    });
</script>
@endpush
@endsection
