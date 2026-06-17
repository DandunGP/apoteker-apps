@extends('layouts.app')

@section('title', 'Dashboard Apoteker')

@section('content')

<style>
    /* Premium clinical-theme corporate dashboard */
    .dashboard-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Outfit', sans-serif;
    }

    /* Header Section styling */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .title-area h1 {
        font-size: 26px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .title-area p {
        font-size: 13.5px;
        color: #64748B;
        margin: 6px 0 0 0;
        font-weight: 500;
    }

    .date-badge {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        border-radius: 4px;
        padding: 10px 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #1E3A8A;
    }

    .date-badge-label {
        font-size: 9px;
        font-weight: 800;
        color: #60A5FA;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: block;
    }

    .date-badge-value {
        font-size: 13.5px;
        font-weight: 800;
        color: #1E3A8A;
        margin-top: 2px;
    }

    /* Top Grid Widgets */
    .widgets-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr;
        gap: 1.5rem;
    }

    /* Card standard styling */
    .widget-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        padding: 1.5rem;
        position: relative;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    /* Widget 1: Validasi Tertunda Card (Deep Blue) */
    .widget-card-blue {
        background: linear-gradient(135deg, #0B579E 0%, #083E75 100%);
        border: none;
        color: white;
    }

    .widget-blue-header {
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .widget-blue-icon {
        background: rgba(255, 255, 255, 0.15);
        color: white;
        width: 38px;
        height: 38px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .widget-blue-title {
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }

    .widget-blue-desc {
        font-size: 12.5px;
        color: #E2E8F0;
        line-height: 1.4;
        margin-top: 6px;
        font-weight: 500;
    }

    .widget-blue-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-top: 1.5rem;
    }

    .widget-blue-value {
        font-size: 32px;
        font-weight: 800;
    }

    .widget-blue-value span {
        font-size: 13px;
        font-weight: 600;
        color: #E2E8F0;
        margin-left: 4px;
    }

    .btn-widget-white {
        background: white;
        color: #0B579E;
        border: none;
        border-radius: 4px;
        padding: 10px 18px;
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: background 0.2s;
    }

    .btn-widget-white:hover {
        background: #F8FAFC;
    }

    /* Red and Green Widget styles */
    .widget-top-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .widget-label-badge {
        font-size: 9px;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 12px;
        text-transform: uppercase;
    }

    .badge-red { background: #FEF2F2; color: #EF4444; }
    .badge-green { background: #ECFDF5; color: #10B981; }

    .widget-middle {
        margin: 1.25rem 0;
    }

    .widget-title {
        font-size: 15px;
        font-weight: 800;
        color: #1E293B;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .widget-desc {
        font-size: 12px;
        color: #64748B;
        font-weight: 500;
        line-height: 1.4;
        margin-top: 6px;
    }

    /* Progress bar */
    .widget-progress-bar {
        height: 6px;
        background: #F1F5F9;
        border-radius: 3px;
        overflow: hidden;
        margin-top: 10px;
    }

    .widget-progress-fill {
        height: 100%;
        background: #EF4444;
        border-radius: 3px;
    }

    .widget-progress-text {
        font-size: 10.5px;
        color: #EF4444;
        font-weight: 800;
        margin-top: 6px;
        display: block;
    }

    /* Avatar group styling */
    .avatar-group {
        display: flex;
        align-items: center;
    }

    .avatar-circle {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        border: 2px solid white;
        background: #E2E8F0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 9px;
        font-weight: 800;
        color: #475569;
        margin-left: -6px;
    }

    .avatar-circle:first-child {
        margin-left: 0;
    }

    .widget-link-all {
        font-size: 11.5px;
        font-weight: 800;
        color: #0F62FE;
        text-decoration: none;
    }

    .widget-link-all:hover {
        text-decoration: underline;
    }

    /* Split Main Grid layout */
    .main-grid {
        display: grid;
        grid-template-columns: 2.5fr 1.5fr;
        gap: 1.5rem;
    }

    .main-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
    }

    .main-card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid #F1F5F9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .main-card-title {
        font-size: 15px;
        font-weight: 800;
        color: #1E293B;
    }

    .btn-filter-action {
        background: white;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 700;
        color: #475569;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Table styles */
    .monitoring-table {
        width: 100%;
        border-collapse: collapse;
    }

    .monitoring-table th {
        background: #F8FAFC;
        text-align: left;
        color: #64748B;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 12px 20px;
        border-bottom: 1px solid #E2E8F0;
        letter-spacing: 0.5px;
    }

    .monitoring-table td {
        padding: 14px 20px;
        font-size: 13px;
        border-bottom: 1px solid #F1F5F9;
        vertical-align: middle;
    }

    .monitoring-table tbody tr:last-child td {
        border-bottom: none;
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

    .pill-warning { background: #FEF3C7; color: #D97706; }
    .pill-danger { background: #FEE2E2; color: #EF4444; }
    .pill-success { background: #D1FAE5; color: #059669; }
    .pill-dark { background: #F3F4F6; color: #374151; }

    .main-card-footer {
        padding: 1.25rem;
        background: #F8FAFC;
        border-top: 1px solid #F1F5F9;
        text-align: center;
    }

    .footer-link {
        color: #0F62FE;
        font-size: 12.5px;
        font-weight: 800;
        text-decoration: none;
    }

    .footer-link:hover {
        text-decoration: underline;
    }

    /* Timeline Persediaan styles */
    .timeline-container {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        position: relative;
    }

    /* Vertical line guide */
    .timeline-container::before {
        content: '';
        position: absolute;
        width: 2px;
        background: #E2E8F0;
        top: 2.25rem;
        bottom: 2.25rem;
        left: 2.25rem;
        z-index: 1;
    }

    .timeline-item {
        display: flex;
        gap: 1rem;
        position: relative;
        z-index: 2;
    }

    .timeline-icon-box {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        box-shadow: 0 0 0 1px #CBD5E1;
        flex-shrink: 0;
    }

    .timeline-icon-green { background: #D1FAE5; color: #059669; box-shadow: 0 0 0 1px #A7F3D0; }
    .timeline-icon-blue { background: #DBEAFE; color: #1D4ED8; box-shadow: 0 0 0 1px #BFDBFE; }
    .timeline-icon-red { background: #FEE2E2; color: #DC2626; box-shadow: 0 0 0 1px #FCA5A5; }

    .timeline-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding-top: 2px;
    }

    .timeline-meta {
        font-size: 9.5px;
        font-weight: 800;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .timeline-title {
        font-size: 13px;
        font-weight: 800;
        color: #1E293B;
    }

    .timeline-desc {
        font-size: 12px;
        color: #64748B;
        font-weight: 500;
        line-height: 1.4;
    }
</style>

<div class="dashboard-container">
    
    <!-- Top Header Area -->
    <div class="dashboard-header">
        <div class="title-area">
            <h1>Dashboard Apoteker</h1>
            <p>Monitoring operasional dan validasi inventaris harian.</p>
        </div>
        <div class="date-badge">
            <i data-lucide="calendar" size="18" style="color: #3B82F6;"></i>
            <div>
                <span class="date-badge-label">Tanggal Sekarang</span>
                <span class="date-badge-value">{{ now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>
    </div>

    <!-- Top Widgets Grid -->
    <div class="widgets-grid">
        <!-- Widget 1: Validasi Tertunda (Deep Blue) -->
        <div class="widget-card widget-card-blue">
            <div class="widget-blue-header">
                <div class="widget-blue-icon">
                    <i data-lucide="clipboard-check" size="20"></i>
                </div>
                <div>
                    <h3 class="widget-blue-title">Validasi Tertunda</h3>
                    <p class="widget-blue-desc">Terdapat batch obat masuk yang memerlukan tanda tangan digital Apoteker.</p>
                </div>
            </div>
            <div class="widget-blue-bottom">
                <div class="widget-blue-value">
                    {{ $data['pending_validation_count'] ?? 0 }} <span style="font-size: 14px; font-weight: 700; color: #BFDBFE;">Faktur</span>
                </div>
                <a href="{{ route('apoteker.validasi.index') }}" class="btn-widget-white" style="text-decoration: none;">
                    Validasi Sekarang
                </a>
            </div>
        </div>

        <!-- Widget 2: Stok Menipis -->
        <div class="widget-card">
            <div class="widget-top-row">
                <div style="background: #FEF2F2; color: #EF4444; width: 34px; height: 34px; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="alert-triangle" size="18"></i>
                </div>
                <span class="widget-label-badge badge-red">Kritis</span>
            </div>
            <div class="widget-middle">
                <div class="widget-title">Stok Menipis</div>
                <div class="widget-desc">
                    <strong>{{ $data['low_stock_count'] }} item</strong> di bawah batas minimum stok aman.
                </div>
                <div class="widget-progress-bar">
                    <div class="widget-progress-fill" style="width: 85%;"></div>
                </div>
                <span class="widget-progress-text">85% Tingkat Peringatan Tercapai</span>
            </div>
        </div>

        <!-- Widget 3: Mendekati Kadaluwarsa -->
        <div class="widget-card">
            <div class="widget-top-row">
                <div style="background: #ECFDF5; color: #10B981; width: 34px; height: 34px; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="hourglass" size="18"></i>
                </div>
                <span class="widget-label-badge badge-green">Urgent</span>
            </div>
            <div class="widget-middle">
                <div class="widget-title">Mendekati Kadaluwarsa</div>
                <div class="widget-desc">
                    <strong>{{ $data['near_expiry_count'] }} item</strong> akan kadaluwarsa dalam < 3 bulan.
                </div>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 0.5rem;">
                <div class="avatar-group">
                    <div class="avatar-circle" style="background: #BFDBFE; color: #1E3A8A;">AM</div>
                    <div class="avatar-circle" style="background: #DDD6FE; color: #5B21B6;">CB</div>
                    <div class="avatar-circle" style="background: #E2E8F0; color: #475569;">+{{ $data['near_expiry_count'] }}</div>
                </div>
                <a href="{{ route('admin.monitoring.expiry') }}" class="widget-link-all">
                    Lihat Semua
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Split Main Grid layout -->
    <div class="main-grid">
        
        <!-- Column Left: Monitoring Stok & Kadaluwarsa -->
        <div class="main-card">
            <div class="main-card-header">
                <span class="main-card-title">Monitoring Stok & Kadaluwarsa</span>
                <button class="btn-filter-action" onclick="Swal.fire({title: 'Filter Laporan', text: 'Semua filter stok kritis & kadaluwarsa telah diterapkan secara otomatis untuk kenyamanan Anda.', icon: 'success', confirmButtonColor: '#0B3E9C'});">
                    <i data-lucide="filter" size="14"></i>
                    Filter
                </button>
            </div>
            <div style="flex-grow: 1;">
                <table class="monitoring-table">
                    <thead>
                        <tr>
                            <th>Nama Obat</th>
                            <th>Batch ID</th>
                            <th>Stok Sisa</th>
                            <th>Tgl. Kadaluwarsa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['recent_movements_detailed'] as $item)
                        <tr>
                            <td style="font-weight: 700; color: #1E293B;">{{ $item->nama }}</td>
                            <td style="color: #64748B; font-weight: 600;">#{{ $item->no_batch }}</td>
                            <td style="font-weight: 600; color: {{ $item->class === 'pill-danger' ? '#EF4444' : '#1E293B' }}">{{ $item->stok_sisa }}</td>
                            <td style="color: #64748B; font-weight: 600;">{{ $item->tanggal_kadaluwarsa }}</td>
                            <td>
                                <span class="pill-badge {{ $item->class }}">{{ $item->status }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94A3B8; padding: 2rem; font-weight: 600;">
                                Tidak ada data obat aktif dalam monitoring kadaluwarsa saat ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="main-card-footer">
                <a href="{{ route('admin.monitoring.stock') }}" class="footer-link">
                    Lihat Laporan Stok Detail
                </a>
            </div>
        </div>

        <!-- Column Right: Riwayat Persediaan Timeline -->
        <div class="main-card">
            <div class="main-card-header">
                <span class="main-card-title">Riwayat Persediaan</span>
            </div>
            <div class="timeline-container">
                @forelse($data['timeline_adjustments'] as $adjustment)
                <!-- Dynamic Timeline Item -->
                <div class="timeline-item">
                    <div class="timeline-icon-box {{ $adjustment->icon_class }}">
                        <i data-lucide="{{ $adjustment->icon }}" size="13"></i>
                    </div>
                    <div class="timeline-content">
                        <span class="timeline-meta">{{ $adjustment->time }}</span>
                        <span class="timeline-title">{{ $adjustment->title }}</span>
                        <p class="timeline-desc">{{ $adjustment->desc }}</p>
                    </div>
                </div>
                @empty
                <div style="text-align: center; color: #94A3B8; padding: 2rem; font-weight: 600; font-size: 12px;">
                    Belum ada riwayat persediaan atau penyesuaian stok baru-baru ini.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

@endsection
