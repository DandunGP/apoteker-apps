<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Apotek</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    @stack('styles')
    <style>
        /* Custom aesthetics according to the redesigned dashboard screenshot */
        .sidebar {
            background-color: #1e293b;
            color: #f8fafc;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar-header {
            padding: 1.5rem 1.5rem 1rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }
        .sidebar-header .logo-text {
            font-size: 20px;
            font-weight: 800;
            color: #ffffff;
            letter-spacing: -0.5px;
        }
        .sidebar-header .logo-sub {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 1rem;
            flex: 1;
            overflow-y: auto;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #cbd5e1;
            font-size: 13px;
            font-weight: 600;
            border-radius: 6px;
            text-decoration: none;
            transition: all 0.2s;
        }
        .nav-item:hover {
            background: rgba(255, 255, 255, 0.05);
            color: #ffffff;
        }
        .nav-item.active {
            background: rgba(16, 185, 129, 0.15) !important;
            color: #10b981 !important;
        }
        .nav-item.active i {
            color: #10b981 !important;
        }
        .sidebar-action {
            padding: 1rem 1.5rem 2rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .btn-cari-obat {
            background: #0056B3;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 12px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
            text-align: center;
        }
        .btn-cari-obat:hover {
            background: #004494;
        }
        .navbar {
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .navbar-brand {
            font-size: 20px;
            font-weight: 800;
            color: #0B3E9C;
        }
        .search-box {
            position: relative;
            width: 320px;
        }
        .search-box input {
            width: 100%;
            background: #EEF2F6;
            border: none;
            border-radius: 20px;
            padding: 8px 16px 8px 36px;
            font-size: 13px;
            color: #1e293b;
            outline: none;
        }
        .search-box input::placeholder {
            color: #94a3b8;
        }
        .search-box i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }
        /* Hide default DataTables elements since we use beautiful custom search and pagination */
        .dataTables_filter, 
        .dataTables_paginate, 
        .dataTables_info, 
        .dataTables_length,
        .dt-header,
        .dt-footer {
            display: none !important;
        }

        /* Breadcrumb Global Styles */
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
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            @if(auth()->user()->role == 'kasir')
                <div class="logo-text">Kasir Panel</div>
                <div class="logo-sub">Pharmacy Management</div>
            @else
                <div class="logo-text">Apotek Pakis</div>
                <div class="logo-sub">Medika Utama</div>
            @endif
        </div>

        <nav class="nav-menu">
            @if(auth()->user()->role == 'admin_gudang')
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-grid" size="18"></i>
                    Dashboard
                </a>
                <a href="{{ route('users.index') }}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i data-lucide="users" size="18"></i>
                    Kelola User
                </a>
                <a href="{{ route('medicines.index') }}" class="nav-item {{ request()->routeIs('medicines.*') && !request()->routeIs('batches.*') && !request()->routeIs('adjustments.*') ? 'active' : '' }}">
                    <i data-lucide="briefcase-medical" size="18"></i>
                    Kelola Data Obat
                </a>
                <a href="{{ route('suppliers.index') }}" class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <i data-lucide="truck" size="18"></i>
                    Kelola Supplier
                </a>
                <a href="{{ route('batches.index') }}" class="nav-item {{ request()->routeIs('batches.*') ? 'active' : '' }}">
                    <i data-lucide="shopping-cart" size="18"></i>
                    Obat Masuk
                </a>
                <a href="{{ route('adjustments.index') }}" class="nav-item {{ request()->routeIs('adjustments.*') ? 'active' : '' }}">
                    <i data-lucide="clipboard" size="18"></i>
                    Stock Opname
                </a>
                <a href="{{ route('admin.monitoring.stock') }}" class="nav-item {{ request()->routeIs('admin.monitoring.stock') ? 'active' : '' }}">
                    <i data-lucide="eye" size="18"></i>
                    Monitoring Stok
                </a>
                <a href="{{ route('admin.monitoring.expiry') }}" class="nav-item {{ request()->routeIs('admin.monitoring.expiry') ? 'active' : '' }}">
                    <i data-lucide="calendar" size="18"></i>
                    Monitoring Kadaluwarsa
                </a>
                <a href="{{ route('admin.retur-pemusnahan.index') }}" class="nav-item {{ request()->routeIs('admin.retur-pemusnahan.*') ? 'active' : '' }}">
                    <i data-lucide="truck" size="18"></i>
                    Retur & Pemusnahan
                </a>
                <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3" size="18"></i>
                    Laporan
                </a>
            @else
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard" size="18"></i>
                    Dashboard
                </a>
                @if(auth()->user()->role == 'apoteker')
                    <a href="{{ route('admin.monitoring.stock') }}" class="nav-item {{ request()->routeIs('admin.monitoring.stock') ? 'active' : '' }}">
                        <i data-lucide="eye" size="18"></i>
                        Monitoring Stok
                    </a>
                    <a href="{{ route('admin.monitoring.expiry') }}" class="nav-item {{ request()->routeIs('admin.monitoring.expiry') ? 'active' : '' }}">
                        <i data-lucide="calendar" size="18"></i>
                        Monitoring Kadaluwarsa
                    </a>
                    <a href="{{ route('apoteker.validasi.index') }}" class="nav-item {{ request()->routeIs('apoteker.validasi.*') ? 'active' : '' }}">
                        <i data-lucide="clipboard-check" size="18"></i>
                        Validasi Obat Masuk
                    </a>
                    <a href="{{ route('adjustments.history') }}" class="nav-item {{ request()->routeIs('adjustments.history') ? 'active' : '' }}">
                        <i data-lucide="history" size="18"></i>
                        Riwayat Persediaan Obat
                    </a>
                    <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                        <i data-lucide="bar-chart-3" size="18"></i>
                        Lihat Laporan
                    </a>
                @endif
                @if(auth()->user()->role == 'kasir')
                    <a href="{{ route('kasir.search') }}" class="nav-item {{ request()->routeIs('kasir.search') ? 'active' : '' }}">
                        <i data-lucide="search" size="18"></i>
                        Cari Data Obat
                    </a>
                    <a href="{{ route('pos.index') }}" class="nav-item {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                        <i data-lucide="monitor" size="18"></i>
                        Transaksi Penjualan
                    </a>
                    <a href="{{ route('sales.history') }}" class="nav-item {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                        <i data-lucide="history" size="18"></i>
                        Riwayat Penjualan
                    </a>
                    <a href="{{ route('kasir.cashflow') }}" class="nav-item {{ request()->routeIs('kasir.cashflow*') ? 'active' : '' }}">
                        <i data-lucide="bar-chart-3" size="18"></i>
                        Laporan Arus Kas
                    </a>
                @endif
            @endif

            <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                @csrf
            </form>
            <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" style="margin-top: auto; margin-bottom: 0;">
                <i data-lucide="log-out" size="18"></i>
                Keluar
            </a>
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="navbar">
            <div class="navbar-brand">
                @php
                    $navRole = auth()->user()->role;
                    $navIcons = [
                        'admin_gudang' => 'layout-grid',
                        'apoteker'     => 'clipboard-check',
                        'kasir'        => 'monitor',
                    ];
                    $navLabels = [
                        'admin_gudang' => 'Dashboard Admin Gudang',
                        'apoteker'     => 'Panel Apoteker',
                        'kasir'        => 'Kasir Portal',
                    ];
                    $navIcon  = $navIcons[$navRole]  ?? 'layout-grid';
                    $navLabel = $navLabels[$navRole] ?? ucwords(str_replace('_', ' ', $navRole));
                @endphp
                <i data-lucide="{{ $navIcon }}" size="20" style="vertical-align: middle; margin-right: 8px; color: #0B3E9C;"></i>
                {{ $navLabel }}
            </div>
            
            <div class="search-box">
                <i data-lucide="search" size="16"></i>
                <input type="text" placeholder="Cari data...">
            </div>

            <div class="navbar-actions">
                <i data-lucide="bell" size="20" class="nav-icon"></i>
                <i data-lucide="help-circle" size="20" class="nav-icon"></i>
                
                <div class="user-profile">
                    <div class="user-info">
                        <span class="name">{{ auth()->user()->name }}</span>
                        <span class="role">{{ str_replace('_', ' ', auth()->user()->role) }}</span>
                    </div>
                    <!-- Mock avatar image based on design -->
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=E0E8FF&color=0F62FE" alt="Avatar" class="user-avatar">
                </div>
            </div>
        </header>

        <main class="content-area">
            @yield('content')
        </main>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    
    <script>
        $(document).ready(function() {
            var table = null;
            if ($('.data-table.use-datatable').length) {
                table = $('.data-table.use-datatable').DataTable({
                    "pageLength": 10,
                    "language": {
                        "search": "Cari:",
                        "lengthMenu": "Tampilkan _MENU_ data",
                        "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                        "paginate": {
                            "first": "Pertama",
                            "last": "Terakhir",
                            "next": "Lanjut",
                            "previous": "Balik"
                        }
                    },
                    "dom": '<"dt-header"f>t<"dt-footer"lip>'
                });
            }

            // Auto-search via URL parameter on page load
            const urlParams = new URLSearchParams(window.location.search);
            const searchParam = urlParams.get('search');
            if (searchParam) {
                $('.search-box input').val(searchParam);
                if (table && $('.data-table.use-datatable').length) {
                    table.search(searchParam).draw();
                } else if ($('.item-card').length) {
                    filterPOSCards(searchParam);
                } else if ($('#localSearchInput').length) {
                    var localInput = document.getElementById('localSearchInput');
                    localInput.value = searchParam;
                    var event = new Event('input', { bubbles: true, cancelable: true });
                    localInput.dispatchEvent(event);
                }
            }

            // Real-time live filtering if we are already on a page containing list items
            $('.search-box input').on('keyup input', function(e) {
                var value = $(this).val();
                
                // If a local search input exists, sync and trigger
                if ($('#localSearchInput').length) {
                    var localInput = document.getElementById('localSearchInput');
                    localInput.value = value;
                    var event = new Event('input', { bubbles: true, cancelable: true });
                    localInput.dispatchEvent(event);
                }

                // If DataTable is present on the page
                if (table && $('.data-table.use-datatable').length) {
                    table.search(value).draw();
                }
                
                // If we are on POS page
                if ($('.item-card').length) {
                    filterPOSCards(value);
                }
            });

            // Bi-directional sync: if local input changes, update navbar search
            if ($('#localSearchInput').length) {
                $('#localSearchInput').on('input', function() {
                    $('.search-box input').val($(this).val());
                });
            }

            // Redirect search on Enter key if there are no list elements on the current page
            $('.search-box input').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    var query = $(this).val().trim();
                    if (!$('.data-table').length && !$('.item-card').length && !$('#localSearchInput').length && query.length > 0) {
                        var userRole = "{{ auth()->user()->role }}";
                        var redirectUrl = "/dashboard";
                        if (userRole === "admin_gudang" || userRole === "admin") {
                            redirectUrl = "{{ route('medicines.index') }}";
                        } else if (userRole === "apoteker") {
                            redirectUrl = "{{ route('apoteker.stock') }}";
                        } else if (userRole === "kasir") {
                            redirectUrl = "{{ route('pos.index') }}";
                        }
                        window.location.href = redirectUrl + "?search=" + encodeURIComponent(query);
                    }
                }
            });

            function filterPOSCards(query) {
                var value = query.toLowerCase();
                $('.item-card').filter(function() {
                    $(this).toggle(
                        $(this).find('.item-title').text().toLowerCase().indexOf(value) > -1 || 
                        $(this).find('.item-badge').text().toLowerCase().indexOf(value) > -1 ||
                        $(this).find('.item-details').text().toLowerCase().indexOf(value) > -1
                    );
                });
            }

            lucide.createIcons();
        });

        // Global SweetAlert handler for session messages
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                confirmButtonColor: '#4F46E5'
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Opps!',
                text: "{{ session('error') }}",
                confirmButtonColor: '#4F46E5'
            });
        @endif
    </script>
    @stack('scripts')
</body>
</html>
