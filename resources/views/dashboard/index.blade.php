@extends('layouts.app')

@section('title', 'Dashboard Admin Gudang')

@section('content')

<style>
    /* Sharp corners (radius 2px for cards/buttons), sleek aesthetic colors matching the design */
    .dashboard-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    /* KPI Grid of 4 */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
    }

    .kpi-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px; /* Sharp corporate radius */
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        min-height: 120px;
        position: relative;
    }

    .kpi-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .kpi-title {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .kpi-icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 4px;
    }

    .kpi-icon-blue { background: #E0E8FF; color: #0F62FE; }
    .kpi-icon-red { background: #FEE2E2; color: #EF4444; }
    .kpi-icon-dark { background: #F1F5F9; color: #334155; }
    .kpi-icon-green { background: #DCFCE7; color: #16A34A; }

    .kpi-value {
        font-size: 28px;
        font-weight: 800;
        color: #1E293B;
        margin: 0.5rem 0 0.25rem;
    }

    .kpi-footer {
        display: flex;
        align-items: center;
        gap: 4px;
        font-size: 11px;
        font-weight: 700;
    }

    .kpi-trend-up { color: #16A34A; }
    .kpi-trend-alert { color: #DC2626; }
    .kpi-trend-muted { color: #64748B; }

    /* Akses Cepat Quick Actions */
    .akses-cepat-container {
        background: #EBF2FC;
        border: 1px solid #CFE2FE;
        border-radius: 4px;
        padding: 1.25rem;
    }

    .akses-cepat-title {
        font-size: 15px;
        font-weight: 800;
        color: #0B3E9C;
        margin-bottom: 1rem;
    }

    .akses-cepat-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
    }

    .akses-cepat-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .akses-cepat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(15, 98, 254, 0.08);
        border-color: #0F62FE;
    }

    .akses-cepat-icon {
        width: 42px;
        height: 42px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .akses-cepat-icon-blue { background: #E0E8FF; color: #0F62FE; }
    .akses-cepat-icon-green { background: #DCFCE7; color: #16A34A; }
    .akses-cepat-icon-dark { background: #F1F5F9; color: #1E293B; }

    .akses-cepat-label {
        font-size: 13px;
        font-weight: 700;
        color: #1E293B;
    }

    /* Main Content Layout Columns */
    .dashboard-layout {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        align-items: start;
    }

    .main-column {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .rounded-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.5rem;
    }

    .card-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .card-header-title {
        font-size: 15px;
        font-weight: 800;
        color: #1E293B;
    }

    .link-lihat-semua {
        color: #0F62FE;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
    }

    /* Aktivitas Barang Table */
    .activity-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px;
    }

    .activity-table th {
        background: #F8FAFC;
        text-align: left;
        color: #64748B;
        font-weight: 700;
        padding: 10px 12px;
        border-bottom: 1px solid #E2E8F0;
    }

    .activity-table td {
        padding: 12px;
        border-bottom: 1px solid #F1F5F9;
        color: #334155;
    }

    .activity-table tr:last-child td {
        border-bottom: none;
    }

    .badge-status {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 10px;
        font-weight: 800;
        text-align: center;
    }

    .badge-success { background: #DCFCE7; color: #16A34A; }
    .badge-primary { background: #E0E8FF; color: #0F62FE; }

    /* Trend Chart Section */
    .chart-legend {
        display: flex;
        gap: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    /* Right Sidebar Alerts */
    .side-column {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .alert-panel {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.25rem;
    }

    .alert-panel-header {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 800;
        margin-bottom: 1rem;
    }

    .alert-panel-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .alert-panel-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .alert-med-name {
        font-size: 12px;
        font-weight: 700;
        color: #1E293B;
    }

    .alert-med-desc {
        font-size: 10px;
        color: #EF4444;
        font-weight: 700;
        margin-top: 2px;
    }

    .btn-pesan-obat {
        color: #0F62FE;
        font-size: 11px;
        font-weight: 800;
        background: none;
        border: none;
        cursor: pointer;
        text-decoration: none;
    }
    .btn-pesan-obat:hover {
        text-decoration: underline;
    }

    .badge-remaining-days {
        background: #FEE2E2;
        color: #EF4444;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 8px;
        border-radius: 4px;
    }

    .alert-panel-footer {
        display: block;
        text-align: center;
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        text-decoration: none;
        margin-top: 1rem;
        padding-top: 0.75rem;
        border-top: 1px solid #F1F5F9;
    }

    /* Tips Banner */
    .tips-banner {
        background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%);
        border-radius: 4px;
        padding: 1.5rem;
        color: white;
        position: relative;
        overflow: hidden;
        min-height: 140px;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    /* Abstract pill designs in background */
    .tips-banner::after {
        content: '';
        position: absolute;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(15, 98, 254, 0.15) 0%, rgba(15, 98, 254, 0) 70%);
        top: -20px;
        right: -20px;
        pointer-events: none;
    }

    .tips-title {
        font-size: 14px;
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 4px;
        z-index: 2;
    }

    .tips-desc {
        font-size: 11px;
        color: #cbd5e1;
        font-weight: 500;
        line-height: 1.4;
        z-index: 2;
    }
</style>

<div class="dashboard-container">
    
    <!-- Top KPI Row -->
    <div class="kpi-grid">
        <!-- 1. Total SKU Obat -->
        <div class="kpi-card">
            <div class="kpi-card-header">
                <span class="kpi-title">Total SKU Obat</span>
                <div class="kpi-icon-wrapper kpi-icon-blue">
                    <i data-lucide="briefcase-medical" size="16"></i>
                </div>
            </div>
            <div class="kpi-value">{{ number_format($data['total_sku'], 0, ',', '.') }}</div>
            <div class="kpi-footer kpi-trend-up">
                <i data-lucide="trending-up" size="12"></i>
                <span>+12 bulan ini</span>
            </div>
        </div>

        <!-- 2. Stok Menipis -->
        <div class="kpi-card">
            <div class="kpi-card-header">
                <span class="kpi-title">Stok Menipis</span>
                <div class="kpi-icon-wrapper kpi-icon-red">
                    <i data-lucide="alert-triangle" size="16"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $data['low_stock_count'] }}</div>
            <div class="kpi-footer kpi-trend-alert">
                <span>Perlu restock segera</span>
            </div>
        </div>

        <!-- 3. Hampir Kadaluwarsa -->
        <div class="kpi-card">
            <div class="kpi-card-header">
                <span class="kpi-title">Hampir Kadaluwarsa</span>
                <div class="kpi-icon-wrapper kpi-icon-dark">
                    <i data-lucide="clock" size="16"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $data['near_expiry_count'] }}</div>
            <div class="kpi-footer kpi-trend-muted">
                <span>&lt; 3 bulan lagi</span>
            </div>
        </div>

        <!-- 4. Supplier Aktif -->
        <div class="kpi-card">
            <div class="kpi-card-header">
                <span class="kpi-title">Supplier Aktif</span>
                <div class="kpi-icon-wrapper kpi-icon-green">
                    <i data-lucide="truck" size="16"></i>
                </div>
            </div>
            <div class="kpi-value">{{ $data['active_suppliers'] }}</div>
            <div class="kpi-footer kpi-trend-up">
                <span>Terintegrasi</span>
            </div>
        </div>
    </div>

    <!-- Akses Cepat Panel -->
    <div class="akses-cepat-container">
        <div class="akses-cepat-title">Akses Cepat</div>
        <div class="akses-cepat-grid">
            <a href="{{ route('batches.create') }}" class="akses-cepat-card">
                <div class="akses-cepat-icon akses-cepat-icon-blue">
                    <i data-lucide="shopping-cart" size="20"></i>
                </div>
                <span class="akses-cepat-label">Input Obat Masuk</span>
            </a>
            <a href="{{ route('adjustments.index') }}" class="akses-cepat-card">
                <div class="akses-cepat-icon akses-cepat-icon-green">
                    <i data-lucide="clipboard" size="20"></i>
                </div>
                <span class="akses-cepat-label">Stock Opname</span>
            </a>
            <a href="{{ route('medicines.index') }}" class="akses-cepat-card">
                <div class="akses-cepat-icon akses-cepat-icon-dark">
                    <i data-lucide="briefcase-medical" size="20"></i>
                </div>
                <span class="akses-cepat-label">Kelola Data Obat</span>
            </a>
        </div>
    </div>

    <!-- Split Column Layout -->
    <div class="dashboard-layout">
        
        <!-- Left Side Column (Wide) -->
        <div class="main-column">
            
            <!-- Aktivitas Barang Table Card -->
            <div class="rounded-card">
                <div class="card-header-row">
                    <span class="card-header-title">Aktivitas Barang (Real-time)</span>
                    <a href="{{ route('adjustments.index') }}" class="link-lihat-semua">Lihat Semua ></a>
                </div>

                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>WAKTU</th>
                            <th>NAMA OBAT</th>
                            <th>JENIS</th>
                            <th>JUMLAH</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($data['recent_movements'] as $movement)
                        <tr>
                            <td style="color: #64748B; font-weight: 600;">{{ $movement->time }}</td>
                            <td style="font-weight: 700; color: #1E293B;">{{ $movement->name }}</td>
                            <td style="color: #64748B;">{{ $movement->category }}</td>
                            <td style="font-weight: 800; color: {{ str_contains($movement->qty, '-') ? '#EF4444' : '#16A34A' }};">
                                {{ $movement->qty }}
                            </td>
                            <td>
                                <span class="badge-status {{ $movement->status_class == 'MASUK' ? 'badge-success' : 'badge-primary' }}">
                                    {{ $movement->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align: center; color: #94A3B8; padding: 2rem; font-weight: 600;">
                                Belum ada aktivitas keluar masuk barang hari ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Weekly Trend Bar Chart -->
            <div class="rounded-card">
                <div class="card-header-row">
                    <span class="card-header-title">Trend Stok (Mingguan)</span>
                    <div class="chart-legend">
                        <span style="display: flex; align-items: center; gap: 4px;"><div style="width: 8px; height: 8px; background: #0B3E9C; border-radius: 50%;"></div> Keluar</span>
                        <span style="display: flex; align-items: center; gap: 4px;"><div style="width: 8px; height: 8px; background: #16A34A; border-radius: 50%;"></div> Masuk</span>
                    </div>
                </div>
                <div style="position: relative; height: 260px; width: 100%;">
                    <canvas id="weeklyStockChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Right Side Column (Narrow) -->
        <div class="side-column">
            
            <!-- Stok Menipis Warning List -->
            <div class="alert-panel">
                <div class="alert-panel-header" style="color: #DC2626;">
                    <i data-lucide="alert-triangle" size="16"></i>
                    <span>Stok Menipis</span>
                </div>
                
                <div style="display: flex; flex-direction: column;">
                    @forelse($data['low_stock_medicines'] as $med)
                    <div class="alert-panel-item">
                        <div>
                            <div class="alert-med-name">{{ $med->nama }}</div>
                            <div class="alert-med-desc">Sisa: {{ $med->total_stock }} {{ $med->satuan }}</div>
                        </div>
                        <a href="{{ route('batches.create') }}" class="btn-pesan-obat">Pesan</a>
                    </div>
                    @empty
                    <div style="text-align: center; color: #16A34A; padding: 1.25rem 0.5rem; font-size: 11.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i data-lucide="check-circle" size="16"></i>
                        <span>Semua stok aman & tervalidasi</span>
                    </div>
                    @endforelse
                </div>
                <a href="{{ route('medicines.index') }}" class="alert-panel-footer">Lihat Semua {{ $data['low_stock_count'] }} Item</a>
            </div>

            <!-- Mendekati Kadaluwarsa Warning List -->
            <div class="alert-panel">
                <div class="alert-panel-header" style="color: #1E293B;">
                    <i data-lucide="calendar" size="16"></i>
                    <span>Mendekati Kadaluwarsa</span>
                </div>
                
                <div style="display: flex; flex-direction: column;">
                    @forelse($data['upcoming_expiries'] as $expiry)
                    <div class="alert-panel-item">
                        <div>
                            <div class="alert-med-name">{{ $expiry->nama }}</div>
                            <div style="font-size: 10px; color: #64748B; margin-top: 2px;">ED: {{ $expiry->expiry_formatted }}</div>
                        </div>
                        <span class="badge-remaining-days">{{ $expiry->days_to_expiry }} Hari</span>
                    </div>
                    @empty
                    <div style="text-align: center; color: #0B3E9C; padding: 1.25rem 0.5rem; font-size: 11.5px; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 6px;">
                        <i data-lucide="shield-check" size="16"></i>
                        <span>Tidak ada obat mendekati ED</span>
                    </div>
                    @endforelse
                </div>
                <a href="#" onclick="printFifoLaporan('{{ route('dashboard.export-fifo') }}'); return false;" class="alert-panel-footer">Buka Monitoring Kadaluwarsa</a>
            </div>

            <!-- Tips Kelola Stok Banner -->
            <div class="tips-banner">
                <div class="tips-title">Tips Kelola Stok</div>
                <div class="tips-desc">Terapkan prinsip FEFO untuk meminimalisir obat kadaluwarsa.</div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('weeklyStockChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($data['chart_labels']) !!},
                datasets: [
                    {
                        label: 'Keluar',
                        data: {!! json_encode($data['chart_out']) !!},
                        backgroundColor: '#0B3E9C', // Deep blue matching screenshot
                        borderRadius: 2
                    },
                    {
                        label: 'Masuk',
                        data: {!! json_encode($data['chart_in']) !!},
                        backgroundColor: '#16A34A', // Green matching screenshot
                        borderRadius: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        grid: { color: '#F1F5F9' },
                        ticks: { stepSize: 20 }
                    }
                },
                barThickness: 16
            }
        });
    });

    function printFifoLaporan(url) {
        let iframe = document.getElementById('fifoPrintFrame');
        if (!iframe) {
            iframe = document.createElement('iframe');
            iframe.id = 'fifoPrintFrame';
            iframe.style.position = 'fixed';
            iframe.style.width = '0';
            iframe.style.height = '0';
            iframe.style.border = 'none';
            iframe.style.top = '-9999px';
            document.body.appendChild(iframe);
        }
        iframe.src = url;
    }
</script>
@endpush
