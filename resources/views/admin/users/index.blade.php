@extends('layouts.app')

@section('title', 'Kelola User')

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

    /* Metrics Cards */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
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

    .user-avatar-pill {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #E0E8FF;
        color: #0F62FE;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
    }

    .role-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        text-align: center;
    }

    .badge-admin { background: #E0E8FF; color: #0F62FE; }
    .badge-apoteker { background: #FAF5FF; color: #8B5CF6; }
    .badge-kasir { background: #ECFDF5; color: #10B981; }

    .action-icon-btn {
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

    .action-icon-btn:hover {
        background: #EFF6FF;
        border-color: #BFDBFE;
        color: #0F62FE;
    }

    .action-delete-btn {
        background: #FFF5F5;
        border: 1px solid #FEB2B2;
        color: #E53E3E;
    }

    .action-delete-btn:hover {
        background: #FED7D7;
        border-color: #FC8181;
        color: #C53030;
    }

    /* Alert Success/Error styles */
    .alert-success-custom {
        background: #ECFDF5;
        border: 1px solid #A7F3D0;
        color: #065F46;
        padding: 12px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .alert-error-custom {
        background: #FEF2F2;
        border: 1px solid #FCA5A5;
        color: #991B1B;
        padding: 12px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

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
        <span class="active">Kelola User</span>
    </div>

    <!-- Header Area -->
    <div class="header-area">
        <div class="title-area">
            <h1>Kelola User</h1>
            <p>Kelola hak akses, roles, dan kredensial akun pengguna sistem apotek.</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-action-primary">
            <i data-lucide="user-plus" size="16"></i>
            Tambah User Baru
        </a>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert-success-custom">
            <i data-lucide="check-circle" size="16"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert-error-custom">
            <i data-lucide="alert-circle" size="16"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <!-- Metrics Cards Grid -->
    <div class="metrics-grid">
        <div class="metric-card" style="border-left: 4px solid #0F62FE;">
            <div class="metric-label">Total User</div>
            <div class="metric-val" style="color: #0F62FE;">{{ $metrics['total_users'] }}</div>
        </div>
        <div class="metric-card" style="border-left: 4px solid #3B82F6;">
            <div class="metric-label">Admin Gudang</div>
            <div class="metric-val" style="color: #3B82F6;">{{ $metrics['total_admin'] }}</div>
        </div>
        <div class="metric-card" style="border-left: 4px solid #8B5CF6;">
            <div class="metric-label">Apoteker</div>
            <div class="metric-val" style="color: #8B5CF6;">{{ $metrics['total_apoteker'] }}</div>
        </div>
        <div class="metric-card" style="border-left: 4px solid #10B981;">
            <div class="metric-label">Kasir / Sales</div>
            <div class="metric-val" style="color: #10B981;">{{ $metrics['total_kasir'] }}</div>
        </div>
    </div>

    <!-- Main Data Table Card -->
    <div class="table-container-card">
        <!-- Gorgeous Table Actions & Local Search Row -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem; padding-bottom: 1rem; border-bottom: 1px solid #F1F5F9; gap: 1rem; flex-wrap: wrap;">
            <!-- Beautiful Local Search Input -->
            <div class="local-search-wrapper" style="position: relative; width: 280px;">
                <i data-lucide="search" size="14" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #64748B;"></i>
                <input type="text" id="localSearchInput" placeholder="Cari nama, email, atau role..." style="width: 100%; padding: 8px 12px 8px 40px !important; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 12.5px; font-weight: 600; outline: none; background: #F8FAFC; box-sizing: border-box; transition: all 0.2s; height: 34px;">
                <button id="clearSearchBtn" type="button" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: #94A3B8; display: none; padding: 2px; align-items: center; justify-content: center;"><i data-lucide="x" size="14"></i></button>
            </div>
            
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 10px; font-weight: 800; color: #94A3B8; text-transform: uppercase; letter-spacing: 0.5px;">Role Filter:</span>
                    <select id="roleFilter" style="background: #F8FAFC; border: 1px solid #CBD5E1; border-radius: 4px; padding: 6px 12px; font-size: 12px; font-weight: 700; color: #475569; outline: none; cursor: pointer; height: 34px;">
                        <option value="all">Semua Role</option>
                        <option value="Admin Gudang">Admin Gudang</option>
                        <option value="Apoteker">Apoteker</option>
                        <option value="Kasir">Kasir</option>
                    </select>
                </div>
            </div>
        </div>

        <table class="data-table" id="usersTable">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th style="width: 50px; text-align: center;">Profil</th>
                    <th>Nama Pengguna</th>
                    <th>Alamat Email</th>
                    <th style="width: 180px; text-align: center;">Role / Hak Akses</th>
                    <th style="width: 200px;">Tanggal Terdaftar</th>
                    <th style="width: 120px; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    @php
                        // Determine display values
                        $avatarLetters = strtoupper(substr($user->name, 0, 2));
                        
                        if ($user->role === 'admin_gudang') {
                            $roleLabel = 'Admin Gudang';
                            $roleClass = 'badge-admin';
                        } elseif ($user->role === 'apoteker') {
                            $roleLabel = 'Apoteker';
                            $roleClass = 'badge-apoteker';
                        } else {
                            $roleLabel = 'Kasir';
                            $roleClass = 'badge-kasir';
                        }
                    @endphp
                    <tr>
                        <td style="color: #64748B; font-weight: 600;">{{ $loop->iteration }}</td>
                        <td>
                            <div class="user-avatar-pill" style="
                                @if($user->role === 'admin_gudang') background: #E0E8FF; color: #0F62FE;
                                @elseif($user->role === 'apoteker') background: #FAF5FF; color: #8B5CF6;
                                @else background: #ECFDF5; color: #10B981;
                                @endif
                            ">
                                {{ $avatarLetters }}
                            </div>
                        </td>
                        <td style="font-weight: 700; color: #1E293B;">
                            {{ $user->name }}
                            @if(auth()->id() === $user->id)
                                <span style="font-size: 10px; font-weight: 700; background: #EEF2F6; color: #64748B; padding: 2px 6px; border-radius: 4px; margin-left: 6px;">Saya</span>
                            @endif
                        </td>
                        <td style="font-weight: 600; color: #475569;">{{ $user->email }}</td>
                        <td style="text-align: center;">
                            <span class="role-badge {{ $roleClass }}">{{ $roleLabel }}</span>
                        </td>
                        <td style="color: #64748B; font-weight: 500;">{{ $user->created_at->format('d M Y H:i') }}</td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                                <a href="{{ route('users.edit', $user->id) }}" class="action-icon-btn" title="Ubah">
                                    <i data-lucide="pencil" size="16"></i>
                                </a>
                                @if(auth()->id() !== $user->id)
                                    <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="delete-user-form" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="action-icon-btn action-delete-btn btn-delete-user" title="Hapus">
                                            <i data-lucide="trash-2" size="16"></i>
                                        </button>
                                    </form>
                                @else
                                    <button type="button" class="action-icon-btn" disabled style="opacity: 0.4; cursor: not-allowed;" title="Tidak dapat menghapus diri sendiri">
                                        <i data-lucide="trash-2" size="16"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: #64748B; font-weight: 600;">
                            Tidak ada data user terdaftar.
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
        const allRows = Array.from(document.querySelectorAll("#usersTable tbody tr"));

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
            const roleFilter = document.getElementById("roleFilter");
            const targetRole = roleFilter ? roleFilter.value : "all";
            const searchQuery = document.getElementById("localSearchInput").value.toLowerCase().trim();
            const clearBtn = document.getElementById("clearSearchBtn");
            if (clearBtn) {
                clearBtn.style.display = searchQuery ? "flex" : "none";
            }

            allRows.forEach(row => {
                if (row.cells.length <= 1) return;
                
                const rowName = (row.cells[2].textContent || "").toLowerCase();
                const rowEmail = (row.cells[3].textContent || "").toLowerCase();
                const rowRole = (row.cells[4].textContent || "").trim(); // Admin Gudang, Apoteker, Kasir

                // 1. Role Filter
                let matchesRole = (targetRole === "all" || rowRole === targetRole);

                // 2. Search Filter
                let matchesSearch = true;
                if (searchQuery !== "") {
                    matchesSearch = rowName.includes(searchQuery) || rowEmail.includes(searchQuery) || rowRole.toLowerCase().includes(searchQuery);
                }

                if (matchesRole && matchesSearch) {
                    row.setAttribute("data-filtered", "true");
                } else {
                    row.setAttribute("data-filtered", "false");
                }
            });
            currentPage = 1;
            updatePagination();
        }

        const roleFilter = document.getElementById("roleFilter");
        if (roleFilter) {
            roleFilter.addEventListener("change", applyFilters);
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

        // SweetAlert Delete User Confirmation
        document.querySelectorAll('.btn-delete-user').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-user-form');
                Swal.fire({
                    title: 'Apakah anda yakin?',
                    text: "User ini akan dihapus secara permanen!",
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
@endsection
