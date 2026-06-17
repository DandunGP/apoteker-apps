@extends('layouts.app')

@section('content')
<style>
    /* Premium Executive CSS System for Retur & Pemusnahan page */
    .retur-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    .retur-header {
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .retur-header h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .retur-header p {
        font-size: 13.5px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    /* Workflow Cards */
    .workflow-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
        margin-bottom: 0.5rem;
    }

    .workflow-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .workflow-title-box {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 12px;
    }

    .workflow-icon {
        width: 42px;
        height: 42px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .bg-blue-light { background: #EFF6FF; color: #0B3E9C; }
    .bg-red-light { background: #FEF2F2; color: #EF4444; }

    .workflow-desc h3 {
        font-size: 15px;
        font-weight: 800;
        color: #1E293B;
        margin: 0 0 4px;
    }

    .workflow-desc p {
        font-size: 12px;
        color: #64748B;
        line-height: 1.5;
        margin: 0;
        font-weight: 500;
    }

    .flow-steps {
        margin-top: 12px;
        padding-left: 14px;
        font-size: 11.5px;
        color: #475569;
        font-weight: 600;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .flow-steps li {
        list-style-type: decimal;
    }

    /* Datagrid styling */
    .datagrid-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .datagrid-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
    }

    .datagrid-header h3 {
        font-size: 15px;
        font-weight: 850;
        color: #1E293B;
        margin: 0;
    }

    .datagrid-table {
        width: 100%;
        border-collapse: collapse;
    }

    .datagrid-table th {
        background: #F8FAFC;
        color: #64748B;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 12px 14px;
        border-bottom: 1.5px solid #EFF2F5;
        text-align: left;
        letter-spacing: 0.5px;
    }

    .datagrid-table td {
        padding: 14px 14px;
        font-size: 13px;
        border-bottom: 1px solid #F1F5F9;
        color: #334155;
        vertical-align: middle;
    }

    .datagrid-table tr:hover {
        background: #F8FAFC;
    }

    .badge-action {
        font-size: 9.5px;
        font-weight: 900;
        padding: 3px 8px;
        border-radius: 4px;
        text-transform: uppercase;
        display: inline-block;
        border: 1px solid transparent;
    }

    .badge-retur {
        background: #EFF6FF;
        color: #0B3E9C;
        border-color: #BFDBFE;
    }

    .badge-pemusnahan {
        background: #FEF2F2;
        color: #EF4444;
        border-color: #FCA5A5;
    }

    .btn-print-doc {
        background: white;
        border: 1.5px solid #CBD5E1;
        color: #475569;
        border-radius: 5px;
        padding: 6px 12px;
        font-size: 12px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-print-doc:hover {
        background: #EFF6FF;
        color: #0B3E9C;
        border-color: #BFDBFE;
    }
</style>
<div class="retur-container">
    <!-- Top Breadcrumb & Header Area -->
    <div>
        <!-- Top Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
            <span>&gt;</span>
            <a href="{{ route('admin.reports.index') }}">Kelola Laporan</a>
            <span>&gt;</span>
            <span class="active">Retur & Pemusnahan</span>
        </div>

        <!-- Header Area -->
        <div class="retur-header">
            <div>
                <h1>Retur & Pemusnahan Obat</h1>
                <p>Kelola alur penyesuaian inventaris kritis dan cetak dokumen kepatuhan hukum.</p>
            </div>
            <a href="{{ route('admin.reports.index') }}" class="btn-print-doc" style="padding: 8px 14px; font-weight:800;">
                <i data-lucide="bar-chart-3" size="14"></i>
                Kembali ke Laporan
            </a>
        </div>
    </div>

<!-- Workflow Description Cards -->
<div class="workflow-grid">
    
    <!-- Alur Retur ke Supplier -->
    <div class="workflow-card">
        <div>
            <div class="workflow-title-box">
                <div class="workflow-icon bg-blue-light">
                    <i data-lucide="truck" size="20"></i>
                </div>
                <div class="workflow-desc">
                    <h3>Alur Proses Retur ke Supplier</h3>
                    <p>Diterapkan pada obat yang mendekati ED (&lt; 3 Bulan) untuk ditukar kembali dengan masa aktif yang baru.</p>
                </div>
            </div>
            <ul class="flow-steps">
                <li>Sistem mendeteksi batch kritis mendekati kadaluwarsa.</li>
                <li>Apoteker mengonfirmasi pengembalian barang ke Supplier terkait.</li>
                <li>Stok fisik di gudang dinolkan demi keakuratan nilai inventaris riil.</li>
                <li>Sistem mengompilasi data dan menerbitkan <strong>Nota/Surat Jalan Retur Obat</strong> resmi untuk pihak Supplier.</li>
            </ul>
        </div>
    </div>

    <!-- Alur Pemusnahan Obat -->
    <div class="workflow-card">
        <div>
            <div class="workflow-title-box">
                <div class="workflow-icon bg-red-light">
                    <i data-lucide="trash-2" size="20"></i>
                </div>
                <div class="workflow-desc">
                    <h3>Alur Proses Pemusnahan Obat</h3>
                    <p>Diterapkan pada obat rusak atau sudah benar-benar melewati tanggal kedaluwarsa demi keamanan konsumen.</p>
                </div>
            </div>
            <ul class="flow-steps">
                <li>Sistem mendaftar batch rusak atau expired yang harus ditarik dari peredaran.</li>
                <li>Apoteker melakukan pemusnahan fisik disaksikan pihak saksi resmi.</li>
                <li>Stok dinolkan dan tercatat sebagai penyusutan nilai modal dalam buku akuntansi.</li>
                <li>Sistem menerbitkan berkas resmi <strong>Berita Acara Pemusnahan Obat</strong> yang sah di hadapan hukum dan BPOM.</li>
            </ul>
        </div>
    </div>

</div>

<!-- Audit Trail Datagrid List -->
<div class="datagrid-card">
    <div class="datagrid-header">
        <h3>Log Histori Aksi Retur & Pemusnahan</h3>
        <span class="badge-action badge-retur" style="font-weight: 850; letter-spacing: 0.5px;">BPOM COMPLIANT</span>
    </div>

    <div style="overflow-x: auto;">
        <table class="datagrid-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Tanggal & Waktu</th>
                    <th style="width: 15%;">Tipe Tindakan</th>
                    <th style="width: 25%;">Nama Obat / No. Batch</th>
                    <th style="width: 10%; text-align: center;">Jumlah</th>
                    <th style="width: 15%;">Petugas</th>
                    <th style="width: 15%; text-align: center;">Dokumen Resmi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($adjustments as $idx => $adj)
                    @php
                        $isRetur = str_contains($adj->reason, 'Retur');
                    @endphp
                    <tr>
                        <td>{{ $idx + 1 }}</td>
                        <td style="color:#64748B; font-weight:600;">{{ $adj->created_at->format('d M Y, H:i') }}</td>
                        <td>
                            @if($isRetur)
                                <span class="badge-action badge-retur">RETUR</span>
                            @else
                                <span class="badge-action badge-pemusnahan">PEMUSNAHAN</span>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #1E293B;">{{ $adj->batch->medicine->nama ?? 'Unknown' }}</strong>
                            <div style="font-size: 11px; color:#64748B; margin-top: 2px;">Batch: {{ $adj->batch->no_batch ?? '-' }}</div>
                        </td>
                        <td style="text-align: center;"><strong style="color:#EF4444;">{{ abs($adj->difference) }} Unit</strong></td>
                        <td style="font-weight: 700; color: #475569;">{{ $adj->user->name ?? 'Apoteker' }}</td>
                        <td style="text-align: center;">
                            <button type="button" class="btn-print-doc" onclick="printDocument({{ $adj->id }})">
                                <i data-lucide="printer" size="13"></i>
                                @if($isRetur)
                                    Nota Retur
                                @else
                                    Berita Acara
                                @endif
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align: center; padding: 3rem; color: #94A3B8; font-weight: 600;">
                            Belum ada log transaksi retur atau pemusnahan tercatat saat ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Hidden IFrame for Direct Printing PDF Dialog -->
<iframe id="print_iframe" style="display: none; border: none; width: 0; height: 0;"></iframe>
</div>

<script>
    function printDocument(id) {
        const url = "{{ url('admin/retur-pemusnahan') }}/" + id + "/print";
        document.getElementById('print_iframe').src = url;
    }
</script>
@endsection
