@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@section('content')
<!-- Top Breadcrumb -->
<div class="breadcrumb no-print">
    <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
    <span>&gt;</span>
    <span class="active">Laporan Penjualan</span>
</div>

<div class="no-print" style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: end;">
    <div>
        <h1 style="font-size: 24px; font-weight: 700;">Laporan Penjualan</h1>
        <p style="color: var(--text-muted); font-size: 14px;">Rekapitulasi transaksi penjualan berdasarkan periode.</p>
    </div>
    <div>
        <button onclick="handlePrint()" class="btn btn-outline">
            <i data-lucide="printer" size="18" style="margin-right: 8px;"></i>
            Cetak Laporan
        </button>
    </div>
</div>

<!-- Modern Web View (no-print) -->
<div class="no-print">
    <div class="rounded-card" style="margin-bottom: 2rem; padding: 1.5rem; background: white;">
        <form action="{{ route('apoteker.report') }}" method="GET" style="display: flex; gap: 1rem; align-items: end;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 8px; color: var(--text-muted); text-transform: uppercase;">Tanggal Mulai</label>
                <input type="date" name="start_date" value="{{ $startDate }}" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 12px; font-family: inherit;">
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 12px; font-weight: 700; margin-bottom: 8px; color: var(--text-muted); text-transform: uppercase;">Tanggal Selesai</label>
                <input type="date" name="end_date" value="{{ $endDate }}" style="width: 100%; padding: 0.75rem 1rem; border: 1.5px solid #E2E8F0; border-radius: 12px; font-family: inherit;">
            </div>
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 1.5rem; height: 49px; border-radius: 12px;">
                <i data-lucide="filter" size="18" style="margin-right: 8px;"></i>
                Tampilkan Laporan
            </button>
        </form>
    </div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="rounded-card" style="background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); color: white;">
            <div style="opacity: 0.8; font-size: 13px; font-weight: 600;">Total Omzet</div>
            <div style="font-size: 24px; font-weight: 700; margin-top: 0.5rem;">{{ $stats['total_revenue_formatted'] }}</div>
        </div>
        <div class="rounded-card">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Total Transaksi</div>
            <div style="font-size: 24px; font-weight: 700; margin-top: 0.5rem; color: var(--primary);">{{ $stats['total_transactions'] }}</div>
        </div>
        <div class="rounded-card">
            <div style="color: var(--text-muted); font-size: 13px; font-weight: 600;">Obat Terjual</div>
            <div style="font-size: 24px; font-weight: 700; margin-top: 0.5rem; color: var(--primary);">{{ $stats['total_items'] }} <span style="font-size: 14px; font-weight: 400; color: var(--text-muted);">Items</span></div>
        </div>
    </div>

    <div class="rounded-card">
        <div style="padding: 1.25rem; border-bottom: 1px solid #F1F5F9; font-weight: 700; color: var(--text-dark);">
            History Transaksi
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Invoice</th>
                    <th>Kasir</th>
                    <th>Detail Barang</th>
                    <th>Total</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td style="color: var(--text-muted);">{{ $loop->iteration }}</td>
                        <td><span class="badge badge-primary">{{ $sale->invoice_number }}</span></td>
                        <td style="font-weight: 500;">{{ $sale->user->name }}</td>
                        <td>
                            <ul style="list-style: none; padding: 0; margin: 0; font-size: 12px;">
                                @foreach($sale->details as $detail)
                                    <li>{{ $detail->medicine->nama ?? $detail->service->nama }} (x{{ $detail->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td style="font-weight: 700;">{{ $sale->total_formatted }}</td>
                        <td style="font-size: 12px; color: var(--text-muted);">{{ $sale->time_formatted }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- FORMAL PRINT TEMPLATE (print-only) -->
<div class="print-only" style="display: none; width: 100%;">
    <div style="text-align: center; margin-bottom: 0.5rem; border-bottom: 2px solid #000; padding-bottom: 10px;">
        <h1 style="font-size: 18px; font-weight: 800; margin: 0; text-transform: uppercase;">{{ config('app.pharmacy_name') }}</h1>
        <p style="font-size: 11px; margin: 2px 0;">No. Surat Izin Apotek : {{ config('app.pharmacy_sia') }} | Telp. {{ config('app.pharmacy_phone') }}</p>
        <p style="font-size: 11px; margin: 1px 0;">{{ config('app.pharmacy_address') }} | Email : {{ config('app.pharmacy_email') }}</p>
    </div>
    <div style="text-align: center; margin-bottom: 1rem; margin-top: 10px;">
        <h2 style="font-size: 14px; font-weight: 800; text-decoration: underline; margin-bottom: 3px;">LAPORAN REKAP DATA PENJUALAN OBAT</h2>
        <p style="font-size: 11px; font-weight: 700;">PERIODE {{ strtoupper(\Carbon\Carbon::parse($startDate)->isoFormat('D MMMM Y')) }} - {{ strtoupper(\Carbon\Carbon::parse($endDate)->isoFormat('D MMMM Y')) }}</p>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
        <thead>
            <tr style="background: #eee;">
                <th style="border: 1px solid #000; padding: 4px;">No</th>
                <th style="border: 1px solid #000; padding: 4px;">Tanggal</th>
                <th style="border: 1px solid #000; padding: 4px;">Kasir</th>
                <th style="border: 1px solid #000; padding: 4px;">No. Faktur</th>
                <th style="border: 1px solid #000; padding: 4px;">Nama Barang (Qty)</th>
                <th style="border: 1px solid #000; padding: 4px;">Jenis</th>
                <th style="border: 1px solid #000; padding: 4px; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $index => $sale)
                <tr>
                    <td style="border: 1px solid #000; padding: 4px; text-align: center;">{{ $index + 1 }}</td>
                    <td style="border: 1px solid #000; padding: 4px;">{{ $sale->date_formatted }}</td>
                    <td style="border: 1px solid #000; padding: 4px;">{{ $sale->user->name }}</td>
                    <td style="border: 1px solid #000; padding: 4px;">{{ $sale->invoice_number }}</td>
                    <td style="border: 1px solid #000; padding: 4px;">
                        @foreach($sale->details as $detail)
                            {{ $detail->medicine->nama ?? $detail->service->nama }} ({{ $detail->quantity }}), 
                        @endforeach
                    </td>
                    <td style="border: 1px solid #000; padding: 4px;">TUNAI</td>
                    <td style="border: 1px solid #000; padding: 4px; text-align: right;">{{ $sale->total_print }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="font-weight: 800;">
                <td colspan="6" style="border: 1px solid #000; padding: 4px; text-align: right;">Total Penjualan :</td>
                <td style="border: 1px solid #000; padding: 4px; text-align: right;">{{ $stats['total_revenue_print'] }}</td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
        <div style="width: 200px; text-align: center; font-size: 10px;">
            <p style="margin-bottom: 2.5rem;">Apoteker Penanggung Jawab,</p>
            <p style="font-weight: 800; text-decoration: underline;">{{ auth()->user()->name }}</p>
            <p>SIA. 912381924</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function handlePrint() {
        // Store original title
        const originalTitle = document.title;
        // Set title to empty to hide browser header
        document.title = "";
        
        window.print();
        
        // Restore title after print dialog closes
        setTimeout(() => {
            document.title = originalTitle;
        }, 100);
    }
</script>
@endpush

<style>
@media print {
    @page { 
        size: landscape; 
        margin: 0; 
    }
    html, body {
        height: 100%;
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }
    /* Hide all layout UI but keep the structure for children */
    .sidebar, .navbar, .no-print, .sidebar-header, .sidebar-footer { 
        display: none !important; 
    }
    .main-wrapper { 
        margin: 0 !important; 
        padding: 0 !important; 
        width: 100% !important;
    }
    .content-area { 
        padding: 0 !important; 
        margin: 0 !important;
    }
    .print-only { 
        display: block !important; 
        padding: 1.5cm !important;
    }
}
</style>
@endsection
