@extends('layouts.app')

@section('title', 'Obat Masuk')

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

    .btn-action-primary {
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

    .btn-action-primary:hover {
        background: #004494;
    }

    /* Table & Container Styles */
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

    /* Badges & Status */
    .monospace-badge {
        font-family: 'Courier New', Courier, monospace;
        font-size: 12px;
        font-weight: 700;
        background: #F1F5F9;
        color: #475569;
        padding: 4px 8px;
        border-radius: 4px;
        border: 1px solid #E2E8F0;
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

    .faktur-badge-lunas { background: #ECFDF5; color: #10B981; }
    .faktur-badge-tempo { background: #FEF3C7; color: #D97706; }
    .faktur-badge-titipan { background: #F3E8FF; color: #9333EA; }

    .btn-print-action {
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        color: #475569;
        border-radius: 4px;
        padding: 6px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-print-action:hover {
        background: #EFF6FF;
        border-color: #BFDBFE;
        color: #0F62FE;
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

<div>
    <!-- Top Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
        <span>&gt;</span>
        <span class="active">Obat Masuk</span>
    </div>

    <!-- Header Area -->
    <div class="header-area">
        <div class="title-area">
            <h1>Obat Masuk</h1>
            <p>Log riwayat penerimaan dan kedatangan stok obat (Faktur & Batch).</p>
        </div>
        <a href="{{ route('batches.create') }}" class="btn-action-primary">
            <i data-lucide="plus" size="16"></i>
            Input Obat Masuk Baru
        </a>
    </div>

    <!-- Main Data Table Card -->
    <div class="table-container-card">
        <!-- Gorgeous Table Actions & Local Search Row -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid #F1F5F9; gap: 1rem; flex-wrap: wrap;">
            <!-- Beautiful Local Search Input -->
            <div class="local-search-wrapper" style="position: relative; width: 280px;">
                <i data-lucide="search" size="14" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748B;"></i>
                <input type="text" id="localSearchInput" placeholder="Cari nomor faktur/batch/obat..." style="width: 100%; padding: 8px 12px 8px 40px !important; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 12.5px; font-weight: 600; outline: none; background: #F8FAFC; box-sizing: border-box; transition: all 0.2s; height: 34px;">
                <button id="clearSearchBtn" type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; display: none; padding: 2px; align-items: center; justify-content: center;"><i data-lucide="x" size="14"></i></button>
            </div>
            
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 10px; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Tipe Faktur:</span>
                    <select id="typeFilter" style="background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 4px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: #475569; outline: none; cursor: pointer; height: 34px;">
                        <option value="all">Semua Tipe</option>
                        <option value="Lunas">Lunas</option>
                        <option value="Tempo">Tempo</option>
                        <option value="Titipan">Titipan</option>
                    </select>
                </div>
            </div>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 180px;">No. Faktur</th>
                    <th style="width: 120px;">No. Batch</th>
                    <th>Daftar Obat</th>
                    <th style="width: 130px; text-align: center;">Jenis Faktur</th>
                    <th style="width: 130px;">Tgl Masuk</th>
                    <th style="width: 120px; text-align: center;">Total Stok</th>
                    <th style="width: 130px; text-align: center;">Status</th>
                    <th style="width: 60px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($batches as $batch)
                    <tr>
                        <td style="color: #64748B; font-weight: 600;">{{ $loop->iteration }}</td>
                        <td>
                            <span class="monospace-badge">{{ $batch->no_faktur }}</span>
                        </td>
                        <td>
                            <span class="batch-badge">{{ $batch->no_batch }}</span>
                        </td>
                        <td>
                            <div style="font-weight: 700; color: #1E293B; font-size: 13.5px;">
                                {{ $batch->medicines->take(2)->implode(', ') }}
                                @if($batch->medicines->count() > 2)
                                    <span style="color: #64748B; font-size: 11px; font-weight: normal;"> (+{{ $batch->medicines->count() - 2 }} lainnya)</span>
                                @endif
                            </div>
                            <small style="color: #64748B; font-weight: 500; font-size: 11px;">{{ $batch->items_count }} Item Obat</small>
                        </td>
                        <td style="text-align: center;">
                            @if($batch->tipe_faktur == 'Lunas')
                                <span class="status-pill faktur-badge-lunas">Lunas</span>
                            @elseif($batch->tipe_faktur == 'Tempo')
                                <span class="status-pill faktur-badge-tempo" title="Jatuh Tempo: {{ $batch->tanggal_jatuh_tempo ? \Carbon\Carbon::parse($batch->tanggal_jatuh_tempo)->format('d M Y') : '-' }}">Tempo</span>
                            @else
                                <span class="status-pill faktur-badge-titipan">Titipan</span>
                            @endif
                        </td>
                        <td style="font-weight: 600; color: #475569;">{{ $batch->tanggal_masuk }}</td>
                        <td style="text-align: center; font-weight: 800; font-size: 15px; color: #0F62FE;">{{ number_format($batch->total_stok, 0, ',', '.') }}</td>
                        <td style="text-align: center;">
                            <span class="status-pill" style="background: {{ $batch->status_bg }}; color: {{ $batch->status_color }};">
                                {{ $batch->expiry_status }}
                            </span>
                        </td>
                        <td style="text-align: center;">
                            <button onclick="printInvoice({{ $batch->id }})" class="btn-print-action" title="Cetak Faktur">
                                <i data-lucide="printer" size="16"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem; color: #64748B; font-weight: 600;">
                        Belum ada riwayat obat masuk terdaftar.
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

<!-- Print Preview Modal -->
<div id="printModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 100; justify-content: center; align-items: center; padding: 2rem;">
    <div style="background: white; width: 100%; max-width: 900px; height: 90vh; border-radius: 4px; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); border: 1px solid #CBD5E1;">
        <!-- Modal Header -->
        <div style="padding: 1rem 1.5rem; border-bottom: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center; background: #F8FAFC;">
            <h3 style="font-size: 14px; font-weight: 800; color: #1E293B; display: flex; align-items: center; gap: 8px; text-transform: uppercase; letter-spacing: 0.5px;">
                <i data-lucide="file-text" size="18" style="color: #0F62FE;"></i>
                Pratinjau Faktur Masuk
            </h3>
            <button onclick="closePrintModal()" style="background: none; border: none; cursor: pointer; color: #64748B; padding: 4px; display: flex; align-items: center; justify-content: center; border-radius: 4px; transition: background 0.2s;" onmouseover="this.style.background='#E2E8F0'" onmouseout="this.style.background='transparent'">
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
        <div style="padding: 1rem 1.5rem; border-top: 1px solid #E2E8F0; background: white; display: flex; justify-content: flex-end; gap: 12px;">
            <button onclick="closePrintModal()" class="btn-print-action" style="padding: 8px 20px; font-weight: 700; font-size: 13px;">
                Tutup
            </button>
            <button onclick="executePrint()" class="btn-action-primary" style="display: flex; align-items: center; gap: 8px; font-size: 13px;">
                <i data-lucide="printer" size="18"></i>
                Cetak Faktur Sekarang
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes spin { 100% { transform: rotate(360deg); } }
</style>

@push('scripts')
<script>
    function printInvoice(batchId) {
        const modal = document.getElementById('printModal');
        const frame = document.getElementById('printFrame');
        const loader = document.getElementById('printLoader');
        
        modal.style.display = 'flex';
        loader.style.display = 'flex';
        
        // Load the print view into the iframe
        frame.src = `/admin/batches/${batchId}/print`;
        
        frame.onload = function() {
            loader.style.display = 'none';
        };
    }

    function closePrintModal() {
        const modal = document.getElementById('printModal');
        const frame = document.getElementById('printFrame');
        
        modal.style.display = 'none';
        frame.src = ''; // Clear iframe
    }

    function executePrint() {
        const frame = document.getElementById('printFrame');
        if (frame.contentWindow) {
            frame.contentWindow.focus();
            frame.contentWindow.print();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        let currentPage = 1;
        let rowsPerPage = 10;
        const allRows = Array.from(document.querySelectorAll(".data-table tbody tr"));

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
            const typeFilter = document.getElementById("typeFilter");
            const targetType = typeFilter ? typeFilter.value : "all";
            const searchQuery = document.getElementById("localSearchInput").value.toLowerCase().trim();
            const clearBtn = document.getElementById("clearSearchBtn");
            if (clearBtn) {
                clearBtn.style.display = searchQuery ? "flex" : "none";
            }

            allRows.forEach(row => {
                if (row.cells.length <= 1) return;
                
                const rowInvoice = (row.cells[1].textContent || "").toLowerCase();
                const rowBatch = (row.cells[2].textContent || "").toLowerCase();
                const rowMedicines = (row.cells[3].textContent || "").toLowerCase();
                const rowType = (row.cells[4].textContent || "").trim(); // Lunas, Tempo, Titipan

                // 1. Type Filter
                let matchesType = (targetType === "all" || rowType.includes(targetType));

                // 2. Search Filter
                let matchesSearch = true;
                if (searchQuery !== "") {
                    matchesSearch = rowInvoice.includes(searchQuery) || rowBatch.includes(searchQuery) || rowMedicines.includes(searchQuery);
                }

                if (matchesType && matchesSearch) {
                    row.setAttribute("data-filtered", "true");
                } else {
                    row.setAttribute("data-filtered", "false");
                }
            });
            currentPage = 1;
            updatePagination();
        }

        const typeFilter = document.getElementById("typeFilter");
        if (typeFilter) {
            typeFilter.addEventListener("change", applyFilters);
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
