@extends('layouts.app')

@section('title', 'Cari Data Obat')

@section('content')

<style>
    .search-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Outfit', sans-serif;
    }

    /* Top Beautiful Blue Gradient Banner */
    .banner-card {
        background: linear-gradient(135deg, #0F62FE 0%, #004494 100%);
        border-radius: 6px;
        padding: 2.25rem;
        color: white;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 12px rgba(15, 98, 254, 0.15);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Banner left content */
    .banner-left {
        max-width: 60%;
        z-index: 2;
    }

    .banner-title {
        font-size: 24px;
        font-weight: 800;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .banner-desc {
        font-size: 13px;
        color: #E2E8F0;
        line-height: 1.45;
        margin: 8px 0 1.5rem 0;
        font-weight: 500;
    }

    /* Banner Search Box inside it */
    .banner-search-row {
        display: flex;
        background: white;
        border-radius: 4px;
        padding: 4px;
        max-width: 500px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }

    .banner-search-input-wrapper {
        display: flex;
        align-items: center;
        flex-grow: 1;
        padding: 0 12px;
        gap: 8px;
    }

    .banner-search-icon {
        color: #64748B;
    }

    .banner-search-input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 13.5px;
        font-weight: 600;
        color: #1E293B;
    }

    .banner-search-btn {
        background: #4ADE80; /* Neon light green matching mockup */
        color: #064E3B;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background 0.2s;
    }

    .banner-search-btn:hover {
        background: #22C55E;
    }

    /* Right banner decorative element */
    .banner-right-decor {
        position: absolute;
        right: -20px;
        top: -20px;
        bottom: -20px;
        width: 35%;
        background-image: url('https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?q=80&w=600&auto=format&fit=crop');
        background-size: cover;
        background-position: center;
        opacity: 0.15;
        z-index: 1;
        mask-image: linear-gradient(to left, rgba(0,0,0,1) 50%, rgba(0,0,0,0));
        -webkit-mask-image: linear-gradient(to left, rgba(0,0,0,1) 50%, rgba(0,0,0,0));
    }

    /* Filter Toolbar */
    .filter-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid #E2E8F0;
    }

    .filter-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .filter-label {
        font-size: 11px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-select {
        background: white;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        outline: none;
    }

    .filter-right {
        font-size: 12.5px;
        color: #64748B;
        font-weight: 500;
    }

    /* Main Table Container */
    .table-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .search-table {
        width: 100%;
        border-collapse: collapse;
    }

    .search-table th {
        background: #F8FAFC;
        text-align: left;
        color: #475569;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 14px 20px;
        border-bottom: 1px solid #E2E8F0;
        letter-spacing: 0.5px;
    }

    .search-table td {
        padding: 16px 20px;
        font-size: 13.5px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
    }

    .search-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Info Column styling */
    .medicine-info-col {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .medicine-title {
        font-weight: 800;
        color: #1E293B;
        font-size: 14px;
        text-decoration: none;
    }

    .medicine-batch-text {
        font-size: 10.5px;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
    }

    .medicine-status-alert {
        font-size: 10px;
        font-weight: 800;
        color: #EF4444;
        text-transform: uppercase;
        margin-top: 1px;
    }

    /* Pill Badges for Stock levels */
    .stock-pill {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 9.5px;
        font-weight: 800;
        text-align: center;
    }

    .stock-pill-success { background: #ECFDF5; color: #10B981; }
    .stock-pill-danger { background: #FEF2F2; color: #EF4444; }

    /* Price tag styling */
    .price-text {
        font-weight: 800;
        color: #0F62FE;
    }

    /* Action buttons: active vs blocked */
    .btn-action-cart {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #EFF6FF;
        color: #0F62FE;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-action-cart:hover {
        background: #0F62FE;
        color: white;
        transform: scale(1.05);
    }

    .btn-action-blocked {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #FEE2E2;
        color: #EF4444;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: not-allowed;
    }

    /* Table Footer with custom layout */
    .table-footer {
        padding: 1.25rem 1.5rem;
        background: #F8FAFC;
        border-top: 1px solid #E2E8F0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .footer-desc {
        font-size: 12.5px;
        color: #64748B;
        font-weight: 600;
    }

    /* Custom pagination blocks */
    .custom-pagination {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .page-btn {
        background: white;
        border: 1px solid #CBD5E1;
        width: 30px;
        height: 30px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.2s;
    }

    .page-btn:hover {
        background: #F8FAFC;
        border-color: #94A3B8;
    }

    .page-btn.active {
        background: #0F62FE;
        border-color: #0F62FE;
        color: white;
    }

    .page-btn.disabled {
        background: #F1F5F9;
        border-color: #E2E8F0;
        color: #94A3B8;
        cursor: not-allowed;
    }
</style>

<div class="search-container">
    
    <!-- Top Beautiful Blue Gradient Banner -->
    <div class="banner-card">
        <div class="banner-left">
            <h1 class="banner-title">Cari Data Obat</h1>
            <p class="banner-desc">Cari informasi ketersediaan obat berdasarkan nama, kategori, atau indikasi medis dengan cepat dan akurat.</p>
            
            <!-- Global Search Form -->
            <form action="{{ route('kasir.search') }}" method="GET">
                <!-- Keep category & stock status filters if active -->
                <input type="hidden" name="category" value="{{ $selectedCategory }}">
                <input type="hidden" name="stock_status" value="{{ $selectedStock }}">

                <div class="banner-search-row">
                    <div class="banner-search-input-wrapper">
                        <i data-lucide="plus-circle" size="18" class="banner-search-icon"></i>
                        <input type="text" name="q" class="banner-search-input" placeholder="Masukkan nama obat atau kandungan aktif..." value="{{ $q }}" autocomplete="off">
                    </div>
                    <button type="submit" class="banner-search-btn">
                        <i data-lucide="search" size="14"></i>
                        Cari
                    </button>
                </div>
            </form>
        </div>
        <div class="banner-right-decor"></div>
    </div>

    <!-- Toolbar Filters -->
    <div class="filter-toolbar">
        <form action="{{ route('kasir.search') }}" method="GET" id="filterForm" class="filter-left">
            <input type="hidden" name="q" value="{{ $q }}">

            <span class="filter-label">Filter:</span>
            
            <!-- Category Filter dropdown -->
            <select name="category" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat }}" {{ $selectedCategory == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
            </select>

            <!-- Stock Status Filter dropdown -->
            <select name="stock_status" class="filter-select" onchange="document.getElementById('filterForm').submit();">
                <option value="">Status Stok</option>
                <option value="aman" {{ $selectedStock == 'aman' ? 'selected' : '' }}>Stok Aman</option>
                <option value="kritis" {{ $selectedStock == 'kritis' ? 'selected' : '' }}>Stok Kritis</option>
                <option value="habis" {{ $selectedStock == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                <option value="hampir_ed" {{ $selectedStock == 'hampir_ed' ? 'selected' : '' }}>Mendekati Kadaluwarsa</option>
            </select>
        </form>

        <div class="filter-right">
            Menampilkan <strong>{{ $paginatedMedicines->total() }}</strong> hasil pencarian
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="table-card">
        <table class="search-table">
            <thead>
                <tr>
                    <th style="width: 35%;">Informasi Obat</th>
                    <th style="width: 20%;">Kategori</th>
                    <th style="width: 15%;">Stok</th>
                    <th style="width: 15%;">Harga Satuan</th>
                    <th style="width: 15%;">Tgl. Kedaluwarsa</th>
                    <th style="width: 10%; text-align: center;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paginatedMedicines as $med)
                    <tr>
                        <!-- 1. Informasi Obat -->
                        <td>
                            <div class="medicine-info-col">
                                <span class="medicine-title">{{ $med->nama }}</span>
                                <span class="medicine-batch-text">Batch: {{ $med->batch_no }}</span>
                                
                                @if($med->expiry_status === 'EXPIRING_SOON')
                                    <span class="medicine-status-alert">EXPIRING SOON</span>
                                @elseif($med->expiry_status === 'EXPIRED')
                                    <span class="medicine-status-alert">EXPIRED / KADALUWARSA</span>
                                @endif
                            </div>
                        </td>

                        <!-- 2. Kategori -->
                        <td style="color: #475569; font-weight: 600;">
                            {{ $med->kategori }}
                        </td>

                        <!-- 3. Stok level badge -->
                        <td>
                            @if($med->total_stock <= $med->min_stok)
                                <span class="stock-pill stock-pill-danger">{{ $med->total_stock }} {{ $med->satuan ?? 'Unit' }}</span>
                            @else
                                <span class="stock-pill stock-pill-success">{{ $med->total_stock }} {{ $med->satuan ?? 'Unit' }}</span>
                            @endif
                        </td>

                        <!-- 4. Harga Satuan -->
                        <td>
                            <span class="price-text">Rp {{ number_format($med->harga, 0, ',', '.') }}</span>
                        </td>

                        <!-- 5. Tanggal Kadaluwarsa -->
                        <td style="color: #475569; font-weight: 600;">
                            @if($med->expiry_date)
                                @if($med->expiry_status === 'AMAN')
                                    <i data-lucide="calendar" size="14" style="vertical-align: middle; color: #64748B; margin-right: 4px;"></i>
                                    {{ \Carbon\Carbon::parse($med->expiry_date)->format('d M Y') }}
                                @else
                                    <span style="color: #EF4444; font-weight: 700;">
                                        <i data-lucide="alert-circle" size="14" style="vertical-align: middle; color: #EF4444; margin-right: 4px;"></i>
                                        {{ \Carbon\Carbon::parse($med->expiry_date)->format('d M Y') }}
                                    </span>
                                @endif
                            @else
                                <span style="color: #94A3B8;">N/A</span>
                            @endif
                        </td>

                        <!-- 6. Aksi (Add to Cart vs Blocked) -->
                        <td style="text-align: center;">
                            @if($med->expiry_status === 'EXPIRED' || $med->expiry_status === 'NO_STOCK')
                                <button class="btn-action-blocked" title="Stok Habis / Kadaluwarsa tidak dapat dijual">
                                    <i data-lucide="ban" size="14"></i>
                                </button>
                            @else
                                <button class="btn-action-cart" title="Tambahkan ke POS" 
                                    onclick="addToLocalStorageCart({{ $med->id }}, '{{ addslashes($med->nama) }}', {{ $med->harga }}, {{ $med->total_stock }}, '{{ $med->kategori }}', '{{ $med->batch_no }}')">
                                    <i data-lucide="shopping-cart" size="14"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #94A3B8;">
                            <i data-lucide="search" size="32" style="margin-bottom: 0.5rem; display: block; margin-left: auto; margin-right: auto; opacity: 0.5;"></i>
                            Data obat tidak ditemukan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Table Pagination Footer -->
        @if($paginatedMedicines->total() > 0)
            <div class="table-footer">
                <div class="footer-desc">
                    Menampilkan <strong>{{ $paginatedMedicines->firstItem() }}-{{ $paginatedMedicines->lastItem() }}</strong> dari <strong>{{ $paginatedMedicines->total() }}</strong> obat
                </div>
                
                <div class="custom-pagination">
                    <!-- Previous page link -->
                    @if($paginatedMedicines->onFirstPage())
                        <span class="page-btn disabled">&lt;</span>
                    @else
                        <a href="{{ $paginatedMedicines->previousPageUrl() }}" class="page-btn">&lt;</a>
                    @endif

                    <!-- Page link items -->
                    @for($i = 1; $i <= $paginatedMedicines->lastPage(); $i++)
                        @if($i == $paginatedMedicines->currentPage())
                            <span class="page-btn active">{{ $i }}</span>
                        @else
                            <a href="{{ $paginatedMedicines->url($i) }}" class="page-btn">{{ $i }}</a>
                        @endif
                    @endfor

                    <!-- Next page link -->
                    @if($paginatedMedicines->hasMorePages())
                        <a href="{{ $paginatedMedicines->nextPageUrl() }}" class="page-btn">&gt;</a>
                    @else
                        <span class="page-btn disabled">&gt;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

</div>

@endsection

@push('scripts')
<script>
    function addToLocalStorageCart(id, name, price, maxStock, category, batchCode) {
        let cart = [];
        try {
            cart = JSON.parse(localStorage.getItem('pos_cart') || '[]');
        } catch(e) {
            cart = [];
        }

        const existing = cart.find(item => item.id === id && item.type === 'medicine');
        if (existing) {
            if (existing.quantity < maxStock) {
                existing.quantity += 1;
                Swal.fire({
                    icon: 'success',
                    title: 'Ditambahkan ke POS',
                    text: `${name} sudah masuk di keranjang POS.`,
                    showCancelButton: true,
                    confirmButtonColor: '#0F62FE',
                    cancelButtonColor: '#10B981',
                    confirmButtonText: 'Buka POS/Kasir',
                    cancelButtonText: 'Lanjut Belanja'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('pos.index') }}";
                    }
                });
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stok Terbatas',
                    text: `Maaf, stok obat hanya tersedia ${maxStock} ${category}.`
                });
            }
        } else {
            cart.push({
                id: id,
                type: 'medicine',
                nama: name,
                harga: price,
                quantity: 1,
                max: maxStock,
                kode: batchCode || 'BATCH-AUTO'
            });
            
            Swal.fire({
                icon: 'success',
                title: 'Ditambahkan ke POS',
                text: `${name} berhasil dimasukkan ke keranjang POS.`,
                showCancelButton: true,
                confirmButtonColor: '#0F62FE',
                cancelButtonColor: '#10B981',
                confirmButtonText: 'Buka POS/Kasir',
                cancelButtonText: 'Lanjut Belanja'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('pos.index') }}";
                }
            });
        }
        
        localStorage.setItem('pos_cart', JSON.stringify(cart));
    }
</script>
@endpush
