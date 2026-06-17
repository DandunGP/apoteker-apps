@extends('layouts.app')

@section('title', 'Riwayat Penjualan')

@section('content')

<style>
    .history-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Outfit', sans-serif;
    }

    /* Title & Filter Header Row */
    .history-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .title-area h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .title-area p {
        font-size: 13.5px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .header-filters-form {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-label-small {
        font-size: 9.5px;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filter-input-date {
        background: white;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        outline: none;
        cursor: pointer;
    }

    .filter-select-status {
        background: white;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 8px 12px;
        font-size: 12.5px;
        font-weight: 700;
        color: #475569;
        outline: none;
        cursor: pointer;
        min-width: 120px;
    }

    /* Main Table block card */
    .table-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
    }

    .history-table th {
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

    .history-table td {
        padding: 16px 20px;
        font-size: 13.5px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
    }

    .history-table tbody tr:last-child td {
        border-bottom: none;
    }

    .receipt-number-link {
        font-weight: 800;
        color: #0F62FE;
        text-decoration: none;
    }

    .receipt-number-link:hover {
        text-decoration: underline;
    }

    .pill-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 9px;
        font-weight: 800;
        text-align: center;
        text-transform: uppercase;
    }

    .pill-completed { background: #DCFCE7; color: #15803D; }
    .pill-refunded { background: #FEE2E2; color: #EF4444; }

    /* Outlined Reprint Button */
    .btn-reprint {
        background: white;
        border: 1px solid #0F62FE;
        border-radius: 4px;
        padding: 6px 14px;
        font-size: 12px;
        font-weight: 800;
        color: #0F62FE;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-reprint:hover {
        background: #EFF6FF;
    }

    /* Table Footer styling */
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

    /* Bottom metrics grid */
    .metrics-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .metric-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
    }

    /* Card 1: Deep blue gradient theme */
    .metric-card-blue {
        background: linear-gradient(135deg, #0F62FE 0%, #004494 100%);
        border: none;
        color: white;
    }

    .metric-card-soft-blue {
        background: #EFF6FF;
        border-color: #BFDBFE;
    }

    .metric-card-left {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .metric-label-small {
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .metric-card-blue .metric-label-small { color: #BFDBFE; }
    .metric-card-soft-blue .metric-label-small { color: #1E3A8A; }

    .metric-val-big {
        font-size: 24px;
        font-weight: 800;
    }

    .metric-card-blue .metric-val-big { color: white; }
    .metric-card-soft-blue .metric-val-big { color: #1E293B; }

    .metric-icon-decor {
        color: rgba(15, 98, 254, 0.15);
    }

    .metric-card-blue .metric-icon-decor {
        color: rgba(255, 255, 255, 0.15);
    }
</style>

<div class="history-container">
    
    <!-- Top Breadcrumb & Header Area -->
    <div>
        <!-- Top Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
            <span>&gt;</span>
            <span class="active">Riwayat Penjualan</span>
        </div>

        <!-- Title & Filter Header Row -->
        <div class="history-header">
            <div class="title-area">
                <h1>Riwayat Penjualan</h1>
                <p>Review and manage past medication transactions.</p>
            </div>
            
            <!-- Filters Form -->
            <form action="{{ route('sales.history') }}" method="GET" id="historyFilterForm" class="header-filters-form">
                <div class="filter-group">
                    <span class="filter-label-small">Date Filter</span>
                    <input type="date" name="date" class="filter-input-date" value="{{ $date }}" onchange="document.getElementById('historyFilterForm').submit();">
                </div>

                <div class="filter-group">
                    <span class="filter-label-small">Status</span>
                    <select name="status" class="filter-select-status" onchange="document.getElementById('historyFilterForm').submit();">
                        <option value="all" {{ $status == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="completed" {{ $status == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="refunded" {{ $status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>

                <div class="filter-group">
                    <span class="filter-label-small">Metode Bayar</span>
                    <select name="payment_method" class="filter-select-status" onchange="document.getElementById('historyFilterForm').submit();">
                        <option value="all" {{ $paymentMethod == 'all' ? 'selected' : '' }}>Semua Metode</option>
                        <option value="tunai" {{ $paymentMethod == 'tunai' ? 'selected' : '' }}>TUNAI</option>
                        <option value="qris" {{ $paymentMethod == 'qris' ? 'selected' : '' }}>QRIS / NON-TUNAI</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Table block card -->
    <div class="table-card">
        <table class="history-table">
            <thead>
                <tr>
                    <th style="width: 15%;">Receipt Number</th>
                    <th style="width: 20%;">Date & Time</th>
                    <th style="width: 10%;">Items</th>
                    <th style="width: 15%;">Total Amount</th>
                    <th style="width: 15%;">Metode</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 10%;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paginatedSales as $sale)
                    <tr>
                        <!-- 1. Receipt Number (Link) -->
                        <td>
                            <a href="#" onclick="previewInvoice('{{ route('sales.print', $sale->id) }}'); return false;" class="receipt-number-link">
                                #{{ $sale->invoice_number }}
                            </a>
                        </td>

                        <!-- 2. Date & Time -->
                        <td style="color: #475569; font-weight: 600;">
                            {{ $sale->time_formatted }}
                        </td>

                        <!-- 3. Items -->
                        <td style="font-weight: 700; color: #1E293B;">
                            {{ $sale->items_count }}
                        </td>

                        <!-- 4. Total Amount -->
                        <td style="font-weight: 800; color: #1E293B;">
                            {{ $sale->total_formatted }}
                        </td>

                        <!-- 5. Payment Method -->
                        <td>
                            <span class="pill-badge" style="background: #F1F5F9; color: #475569; border: 1px solid #CBD5E1;">
                                {{ $sale->payment_method_label }}
                            </span>
                        </td>

                        <!-- 5. Status Pill badge -->
                        <td>
                            @if($sale->status === 'COMPLETED')
                                <span class="pill-badge pill-completed">Completed</span>
                            @else
                                <span class="pill-badge pill-refunded">Refunded</span>
                            @endif
                        </td>

                        <!-- 6. Actions (Reprint & Refund) -->
                        <td>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <button type="button" class="btn-reprint" onclick="previewInvoice('{{ route('sales.print', $sale->id) }}')" title="Cetak Ulang Struk">
                                    <i data-lucide="printer" size="14"></i>
                                    Reprint
                                </button>
                                
                                @if($sale->status === 'COMPLETED')
                                    <button type="button" class="btn-reprint" style="border-color: #EF4444; color: #EF4444;" onclick="confirmRefund({{ $sale->id }}, '{{ $sale->invoice_number }}')" title="Refund Transaksi">
                                        <i data-lucide="refresh-ccw" size="14"></i>
                                        Refund
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 3rem; color: #94A3B8;">
                            <i data-lucide="history" size="32" style="margin-bottom: 0.5rem; display: block; margin-left: auto; margin-right: auto; opacity: 0.5;"></i>
                            Belum ada riwayat transaksi penjualan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Table Pagination Footer -->
        @if($paginatedSales->total() > 0)
            <div class="table-footer">
                <div class="footer-desc">
                    Showing <strong>{{ $paginatedSales->firstItem() }}-{{ $paginatedSales->lastItem() }}</strong> of <strong>{{ $paginatedSales->total() }}</strong> transactions
                </div>
                
                <div class="custom-pagination">
                    <!-- Previous page link -->
                    @if($paginatedSales->onFirstPage())
                        <span class="page-btn disabled">&lt;</span>
                    @else
                        <a href="{{ $paginatedSales->previousPageUrl() }}" class="page-btn">&lt;</a>
                    @endif

                    <!-- Page link items -->
                    @for($i = 1; $i <= $paginatedSales->lastPage(); $i++)
                        @if($i == $paginatedSales->currentPage())
                            <span class="page-btn active">{{ $i }}</span>
                        @else
                            <a href="{{ $paginatedSales->url($i) }}" class="page-btn">{{ $i }}</a>
                        @endif
                    @endfor

                    <!-- Next page link -->
                    @if($paginatedSales->hasMorePages())
                        <a href="{{ $paginatedSales->nextPageUrl() }}" class="page-btn">&gt;</a>
                    @else
                        <span class="page-btn disabled">&gt;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <!-- Bottom Metrics Grid (3 beautiful Cards) -->
    <div class="metrics-grid">
        <!-- Card 1: Total Sales -->
        <div class="metric-card metric-card-blue">
            <div class="metric-card-left">
                <span class="metric-label-small">Total Penjualan — {{ $stats['stats_date_label'] }}</span>
                <span class="metric-val-big">{{ $stats['total_sales_today'] }}</span>
            </div>
            <div class="metric-icon-decor">
                <i data-lucide="banknote" size="44"></i>
            </div>
        </div>

        <!-- Card 2: Total Transactions -->
        <div class="metric-card metric-card-soft-blue">
            <div class="metric-card-left">
                <span class="metric-label-small">Total Transaksi — {{ $stats['stats_date_label'] }}</span>
                <span class="metric-val-big">{{ $stats['total_orders_today'] }}</span>
            </div>
            <div class="metric-icon-decor" style="color: rgba(15, 98, 254, 0.12);">
                <i data-lucide="file-text" size="44"></i>
            </div>
        </div>

        <!-- Card 3: Avg Basket Value -->
        <div class="metric-card metric-card-soft-blue">
            <div class="metric-card-left">
                <span class="metric-label-small">Rata-rata Transaksi — {{ $stats['stats_date_label'] }}</span>
                <span class="metric-val-big">{{ $stats['avg_basket_value'] }}</span>
            </div>
            <div class="metric-icon-decor" style="color: rgba(15, 98, 254, 0.12);">
                <i data-lucide="trending-up" size="44"></i>
            </div>
        </div>
    </div>

</div>

<!-- Print Preview Modal -->
<div id="printModal" class="modal-backdrop">
    <div class="modal-content" style="max-width: 400px; padding: 0;">
        <div style="padding: 1.5rem; border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 700;">Preview Struk</h3>
            <button onclick="closeModal()" style="background: none; border: none; color: var(--text-muted); cursor: pointer;">
                <i data-lucide="x" size="20"></i>
            </button>
        </div>
        <div style="padding: 1rem; background: #F8FAFC;">
            <iframe id="printFrame" style="width: 100%; height: 450px; border: 1px solid #E2E8F0; border-radius: 12px; background: white;"></iframe>
        </div>
        <div style="padding: 1.5rem; display: flex; gap: 12px;">
            <button onclick="closeModal()" class="btn btn-outline" style="flex: 1; justify-content: center;">Batal</button>
            <button onclick="doPrint()" class="btn btn-primary" style="flex: 1; justify-content: center; background: #0F62FE;">
                <i data-lucide="printer" size="18" style="margin-right: 8px;"></i>
                Cetak Struk
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function previewInvoice(url) {
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

    window.onclick = function(event) {
        const modal = document.getElementById('printModal');
        if (event.target == modal) {
            closeModal();
        }
    }

    async function confirmRefund(id, invoiceNumber) {
        const { isConfirmed } = await Swal.fire({
            title: 'Refund Transaksi?',
            text: `Apakah Anda yakin ingin melakukan refund untuk transaksi #${invoiceNumber}? Semua stok obat dalam transaksi ini akan dikembalikan ke inventaris.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#EF4444',
            cancelButtonColor: '#64748B',
            confirmButtonText: 'Ya, Refund',
            cancelButtonText: 'Batal'
        });

        if (!isConfirmed) return;

        try {
            const response = await fetch(`/kasir/sales/${id}/refund`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            });

            const result = await response.json();
            if (result.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Refund Berhasil',
                    text: result.message,
                    confirmButtonColor: '#10B981'
                });
                location.reload();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Refund Gagal',
                    text: result.message
                });
            }
        } catch(err) {
            Swal.fire({
                icon: 'error',
                title: 'Opps!',
                text: 'Terjadi kesalahan sistem.'
            });
        }
    }
</script>
@endpush
