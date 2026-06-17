@extends('layouts.app')
@section('title', 'Laporan Arus Kas')
@section('content')
<style>
.cf-container { display:flex; flex-direction:column; gap:1.5rem; font-family:'Outfit',sans-serif; }
.cf-title { font-size:24px; font-weight:800; color:#1E293B; margin:0; }
.cf-subtitle { font-size:13.5px; color:#64748B; margin:4px 0 0; font-weight:500; }

/* Summary Cards */
.summary-grid { display:grid; grid-template-columns:1fr 1fr 1.1fr; gap:1.25rem; }
.sum-card { background:white; border:1px solid #E2E8F0; border-radius:6px; padding:1.25rem 1.5rem; box-shadow:0 1px 3px rgba(0,0,0,.02); }
.sum-card-blue { background:linear-gradient(135deg,#0F62FE,#004494); border:none; color:white; }
.sum-badge { font-size:9px; font-weight:800; text-transform:uppercase; letter-spacing:.5px; padding:3px 8px; border-radius:12px; display:inline-block; margin-bottom:.5rem; }
.sum-badge-green { background:#ECFDF5; color:#059669; }
.sum-badge-blue  { background:rgba(255,255,255,.2); color:white; }
.sum-label { font-size:11px; font-weight:700; color:#64748B; }
.sum-card-blue .sum-label { color:#BFDBFE; }
.sum-value { font-size:26px; font-weight:800; color:#1E293B; margin:4px 0; }
.sum-card-blue .sum-value { color:white; }
.sum-trend { font-size:11px; font-weight:700; }
.trend-up   { color:#10B981; }
.trend-down { color:#EF4444; }
.sum-card-blue .sum-trend { color:#A5F3FC; }
.sum-note { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; margin-top:6px; }
.sum-note-green { color:#10B981; }
.sum-note-red   { color:#F87171; }
.sum-note-blue  { color:#93C5FD; }

/* Filter bar */
.filter-bar { background:white; border:1px solid #E2E8F0; border-radius:6px; padding:1.25rem 1.5rem; display:flex; flex-wrap:wrap; gap:1rem; align-items:flex-end; }
.filter-group { display:flex; flex-direction:column; gap:5px; }
.filter-lbl { font-size:10px; font-weight:800; color:#64748B; text-transform:uppercase; letter-spacing:.4px; }
.filter-select,.filter-input { background:#F8FAFC; border:1px solid #CBD5E1; border-radius:4px; padding:8px 12px; font-size:12.5px; font-weight:700; color:#1E293B; outline:none; }
.btn-filter { background:#EFF6FF; color:#1D4ED8; border:1px solid #BFDBFE; border-radius:4px; padding:9px 16px; font-size:12px; font-weight:800; cursor:pointer; display:inline-flex; align-items:center; gap:6px; }
.btn-catat  { background:#0F62FE; color:white; border:none; border-radius:4px; padding:9px 20px; font-size:12px; font-weight:800; cursor:pointer; display:inline-flex; align-items:center; gap:8px; text-decoration:none; margin-left:auto; }
.btn-catat:hover { background:#004494; }

/* Table */
.table-card { background:white; border:1px solid #E2E8F0; border-radius:6px; box-shadow:0 1px 3px rgba(0,0,0,.02); overflow:hidden; }
.table-header { padding:1.25rem 1.5rem; border-bottom:1px solid #F1F5F9; display:flex; justify-content:space-between; align-items:center; }
.table-title { font-size:15px; font-weight:800; color:#1E293B; }
.btn-export { background:white; border:1px solid #CBD5E1; border-radius:4px; padding:6px 14px; font-size:11px; font-weight:800; color:#475569; cursor:pointer; display:inline-flex; align-items:center; gap:6px; text-decoration:none; }
.cf-table { width:100%; border-collapse:collapse; }
.cf-table th { background:#F8FAFC; color:#475569; font-size:10.5px; font-weight:800; text-transform:uppercase; padding:12px 20px; border-bottom:1px solid #E2E8F0; letter-spacing:.5px; text-align:left; }
.cf-table td { padding:15px 20px; font-size:13px; border-bottom:1px solid #F8FAFC; vertical-align:middle; }
.cf-table tbody tr:last-child td { border-bottom:none; }
.type-pill { display:inline-block; padding:3px 10px; border-radius:12px; font-size:9px; font-weight:900; text-transform:uppercase; letter-spacing:.5px; }
.pill-masuk  { background:#DCFCE7; color:#15803D; }
.pill-keluar { background:#FEE2E2; color:#B91C1C; }
.cat-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.3px; margin-top:3px; }
.cat-penjualan  { color:#3B82F6; }
.cat-inkaso     { color:#F59E0B; }
.cat-operasional{ color:#8B5CF6; }
.cat-modal      { color:#10B981; }
.nominal-masuk  { font-weight:800; color:#1E293B; }
.nominal-keluar { font-weight:800; color:#EF4444; }
.btn-view { width:30px; height:30px; border-radius:50%; background:#F1F5F9; border:none; display:flex; align-items:center; justify-content:center; cursor:pointer; color:#475569; }
.btn-view:hover { background:#DBEAFE; color:#1D4ED8; }
.tfoot-row { padding:1.25rem 1.5rem; background:#F8FAFC; border-top:1px solid #E2E8F0; display:flex; justify-content:space-between; align-items:center; }
.tfoot-text { font-size:12.5px; color:#64748B; font-weight:600; }
.page-btns { display:flex; gap:5px; }
.pg-btn { width:28px; height:28px; border-radius:4px; border:1px solid #CBD5E1; background:white; font-size:12px; font-weight:700; color:#475569; display:flex; align-items:center; justify-content:center; text-decoration:none; }
.pg-btn.active { background:#0F62FE; border-color:#0F62FE; color:white; }
.pg-btn.disabled { background:#F1F5F9; color:#94A3B8; pointer-events:none; }

/* Bottom row: form + integrity card */
.bottom-grid { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; }
.form-card { background:white; border:1px solid #E2E8F0; border-radius:6px; padding:1.5rem; box-shadow:0 1px 3px rgba(0,0,0,.02); }
.form-card-title { font-size:14px; font-weight:800; color:#1E293B; margin:0 0 .25rem; display:flex; align-items:center; gap:8px; }
.form-note { font-size:11.5px; color:#64748B; font-weight:500; margin:0 0 1.25rem; }
.toggle-row { display:flex; gap:0; border:1px solid #CBD5E1; border-radius:4px; overflow:hidden; width:fit-content; margin-bottom:.25rem; }
.toggle-btn { padding:8px 20px; font-size:12.5px; font-weight:800; border:none; cursor:pointer; background:white; color:#475569; }
.toggle-btn.active-masuk  { background:#0F62FE; color:white; }
.toggle-btn.active-keluar { background:#EF4444; color:white; }
.form-lbl { font-size:10.5px; font-weight:800; color:#64748B; text-transform:uppercase; letter-spacing:.4px; margin:1rem 0 5px; display:block; }
.form-inp { width:100%; box-sizing:border-box; background:#F8FAFC; border:1px solid #E2E8F0; border-radius:4px; padding:10px 14px; font-size:13px; color:#1E293B; outline:none; }
.form-inp:focus { border-color:#0F62FE; }
textarea.form-inp { resize:vertical; min-height:70px; }
.btn-simpan { width:100%; background:#0F62FE; color:white; border:none; border-radius:4px; padding:13px; font-size:13px; font-weight:800; cursor:pointer; margin-top:1.25rem; }
.btn-simpan:hover { background:#004494; }

/* Integrity card */
.integrity-card { background:linear-gradient(135deg,#F0FDF4,#DCFCE7); border:1px solid #BBF7D0; border-radius:6px; padding:1.5rem; display:flex; flex-direction:column; justify-content:space-between; }
.integrity-icon { width:44px; height:44px; background:#059669; border-radius:10px; display:flex; align-items:center; justify-content:center; color:white; margin-bottom:1rem; }
.integrity-title { font-size:16px; font-weight:800; color:#064E3B; margin:0 0 6px; }
.integrity-desc  { font-size:12.5px; color:#065F46; font-weight:500; line-height:1.5; }
.integrity-row { display:flex; justify-content:space-between; align-items:center; padding:10px 0; border-top:1px solid #A7F3D0; margin-top:1rem; font-size:12.5px; }
.integrity-key   { color:#065F46; font-weight:700; }
.integrity-val   { font-weight:800; color:#064E3B; }
.status-dot { display:inline-block; width:8px; height:8px; background:#10B981; border-radius:50%; margin-right:4px; }
</style>

<div class="cf-container">

    <!-- Top Breadcrumb & Header Area -->
    <div>
        <!-- Top Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
            <span>&gt;</span>
            <span class="active">Arus Kas</span>
        </div>

        {{-- ① Title --}}
        <div>
            <h1 class="cf-title">Laporan Penjualan Harian & Arus Kas</h1>
        </div>
    </div>

    {{-- ② Summary cards --}}
    <div class="summary-grid">
        <div class="sum-card">
            <span class="sum-badge sum-badge-green">Hari Ini</span>
            <div class="sum-label">Total Uang Masuk (Retail & Resep)</div>
            <div class="sum-value">{{ $stats['total_masuk'] }}</div>
            <div class="sum-trend trend-up">
                @if($stats['masuk_pct'] >= 0)
                    ↑ +{{ $stats['masuk_pct'] }}% vs Bulan Lalu
                @else
                    ↓ {{ $stats['masuk_pct'] }}% vs Bulan Lalu
                @endif
            </div>
            <div class="sum-note sum-note-green">OTOMATIS DARI PENJUALAN</div>
        </div>

        <div class="sum-card">
            <span class="sum-badge" style="background:#FEF3C7;color:#B45309;">Inkaso & Operasional</span>
            <div class="sum-label">Total Uang Keluar (Inkaso & Operasional)</div>
            <div class="sum-value" style="color:#EF4444;">{{ $stats['total_keluar'] }}</div>
            <div class="sum-trend trend-down">
                @if($stats['keluar_pct'] >= 0)
                    ↑ +{{ $stats['keluar_pct'] }}% vs Bulan Lalu
                @else
                    ↓ {{ $stats['keluar_pct'] }}% vs Bulan Lalu
                @endif
            </div>
            <div class="sum-note sum-note-red">INPUT MANUAL (INKASO/OPERASIONAL)</div>
        </div>

        <div class="sum-card sum-card-blue">
            <span class="sum-badge sum-badge-blue">Saldo Akhir</span>
            <div class="sum-label">Saldo Kas Saat Ini</div>
            <div class="sum-value">{{ $stats['saldo'] }}</div>
            <div class="sum-trend">↑ +8% vs Bulan Lalu</div>
        </div>
    </div>

    {{-- ③ Filter bar --}}
    <form action="{{ route('kasir.cashflow') }}" method="GET" class="filter-bar" id="filterForm">
        <div class="filter-group">
            <span class="filter-lbl">Periode</span>
            <select name="period" class="filter-select">
                <option value="harian"   {{ $period=='harian'   ? 'selected':'' }}>Harian</option>
                <option value="mingguan" {{ $period=='mingguan' ? 'selected':'' }}>Mingguan</option>
                <option value="bulanan"  {{ $period=='bulanan'  ? 'selected':'' }}>Bulanan</option>
            </select>
        </div>
        <div class="filter-group">
            <span class="filter-lbl">Rentang Tanggal</span>
            <div style="display:flex;gap:4px;align-items:center;">
                <input type="date" name="date_from" class="filter-input" value="{{ $dateFrom }}" style="width:140px;">
                <span style="color:#64748B;font-weight:700;font-size:12px;">–</span>
                <input type="date" name="date_to"   class="filter-input" value="{{ $dateTo }}"   style="width:140px;">
            </div>
        </div>
        <div class="filter-group">
            <span class="filter-lbl">Tipe Transaksi</span>
            <select name="type" class="filter-select">
                <option value="semua"  {{ $type=='semua'  ? 'selected':'' }}>Semua Tipe</option>
                <option value="masuk"  {{ $type=='masuk'  ? 'selected':'' }}>Masuk</option>
                <option value="keluar" {{ $type=='keluar' ? 'selected':'' }}>Keluar</option>
            </select>
        </div>
        <button type="submit" class="btn-filter">
            <i data-lucide="filter" size="13"></i> Terapkan Filter
        </button>
        <a href="#formCatat" class="btn-catat">
            <i data-lucide="plus-circle" size="14"></i> Catat Arus Kas Manual
        </a>
    </form>

    {{-- ④ History table --}}
    <div class="table-card">
        <div class="table-header">
            <span class="table-title">Riwayat Arus Kas</span>
            <a href="#" onclick="exportPDF()" class="btn-export">
                <i data-lucide="download" size="13"></i> Export PDF
            </a>
        </div>

        <table class="cf-table">
            <thead>
                <tr>
                    <th style="width:18%">Tanggal & Waktu</th>
                    <th style="width:10%">Jenis</th>
                    <th style="width:40%">Keterangan</th>
                    <th style="width:18%;text-align:right">Nominal</th>
                    <th style="width:8%;text-align:center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paginated as $cf)
                    <tr>
                        <td style="color:#475569;font-weight:600;">{{ $cf->time_formatted }}</td>
                        <td>
                            <span class="type-pill {{ $cf->type === 'masuk' ? 'pill-masuk' : 'pill-keluar' }}">
                                {{ strtoupper($cf->type) }}
                            </span>
                        </td>
                        <td>
                            <div style="font-weight:700;color:#1E293B;">{{ $cf->keterangan }}</div>
                            <div class="cat-label
                                @if($cf->category === 'LAPORAN_PENJUALAN') cat-penjualan
                                @elseif($cf->category === 'INKASO_SUPPLIER') cat-inkaso
                                @elseif($cf->category === 'OPERASIONAL') cat-operasional
                                @else cat-modal @endif">
                                {{ $cf->category_label }}
                                — {{ isset($cf->is_auto) && $cf->is_auto ? 'Otomatis dari Sistem' : 'Input Manual' }}
                            </div>
                        </td>
                        <td style="text-align:right">
                            <span class="{{ $cf->type === 'masuk' ? 'nominal-masuk' : 'nominal-keluar' }}">
                                {{ $cf->nominal_formatted }}
                            </span>
                        </td>
                        <td style="text-align:center">
                            <button class="btn-view" title="Detail"
                                onclick="showDetail(
                                    '{{ addslashes($cf->keterangan) }}',
                                    '{{ $cf->time_formatted }}',
                                    '{{ $cf->nominal_formatted }}',
                                    '{{ strtoupper($cf->type) }}',
                                    '{{ $cf->category_label }}'
                                )">
                                <i data-lucide="eye" size="14"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:3rem;color:#94A3B8;">
                            <i data-lucide="inbox" size="32" style="display:block;margin:0 auto .5rem;opacity:.4;"></i>
                            Belum ada riwayat arus kas untuk periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($paginated->total() > 0)
        <div class="tfoot-row">
            <span class="tfoot-text">
                Menampilkan {{ $paginated->firstItem() }}-{{ $paginated->lastItem() }} dari {{ $paginated->total() }} entri
            </span>
            <div class="page-btns">
                @if($paginated->onFirstPage())
                    <span class="pg-btn disabled">&lt;</span>
                @else
                    <a href="{{ $paginated->previousPageUrl() }}" class="pg-btn">&lt;</a>
                @endif

                @for($i = 1; $i <= min($paginated->lastPage(), 5); $i++)
                    <a href="{{ $paginated->url($i) }}" class="pg-btn {{ $i == $paginated->currentPage() ? 'active':'' }}">{{ $i }}</a>
                @endfor

                @if($paginated->hasMorePages())
                    <a href="{{ $paginated->nextPageUrl() }}" class="pg-btn">&gt;</a>
                @else
                    <span class="pg-btn disabled">&gt;</span>
                @endif
            </div>
        </div>
        @endif
    </div>

    {{-- ⑤ Form + Integrity card --}}
    <div class="bottom-grid" id="formCatat">

        {{-- Form --}}
        <div class="form-card">
            <h3 class="form-card-title">
                <i data-lucide="book-open" size="18" style="color:#0F62FE;"></i>
                Form Pencatatan Pembayaran & Arus Kas
            </h3>
            <p class="form-note">Gunakan formulir ini untuk semua pengeluaran manual (Inkaso & Operasional).</p>

            @if(session('success'))
                <div style="background:#DCFCE7;color:#15803D;padding:10px 14px;border-radius:4px;font-size:12.5px;font-weight:700;margin-bottom:1rem;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('kasir.cashflow.store') }}" method="POST" id="cashflowForm">
                @csrf

                {{-- Masuk / Keluar toggle --}}
                <label class="form-lbl">Jenis Transaksi</label>
                <div class="toggle-row" id="typeToggle">
                    <button type="button" class="toggle-btn" id="btnMasuk" onclick="setType('masuk')">Masuk</button>
                    <button type="button" class="toggle-btn active-keluar" id="btnKeluar" onclick="setType('keluar')">Keluar</button>
                </div>
                <input type="hidden" name="type" id="typeInput" value="keluar">

                {{-- Category --}}
                <label class="form-lbl">Kategori</label>
                <select name="category" class="form-inp" id="categorySelect">
                    @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ $key === 'INKASO_SUPPLIER' ? 'selected':'' }}>{{ $label }}</option>
                    @endforeach
                </select>

                {{-- Keterangan --}}
                <label class="form-lbl">Penerima / Keperluan</label>
                <input type="text" name="keterangan" class="form-inp" placeholder="Contoh: PBF Kimia Farma atau Token Listrik" required>

                {{-- Nominal --}}
                <label class="form-lbl">Nominal</label>
                <input type="number" name="nominal" class="form-inp" value="0" min="1" required>

                {{-- Notes --}}
                <label class="form-lbl">Keterangan Tambahan</label>
                <textarea name="keterangan_extra" class="form-inp" placeholder="Contoh: Pembelian Token Listrik atau Plastik R1"></textarea>

                <button type="submit" class="btn-simpan">Simpan Pembayaran Inkaso / Kas</button>
            </form>
        </div>

        {{-- Integrity card --}}
        <div class="integrity-card">
            <div>
                <div class="integrity-icon">
                    <i data-lucide="shield-check" size="24"></i>
                </div>
                <div class="integrity-title">Integritas Data Kas</div>
                <p class="integrity-desc">
                    Setiap transaksi arus kas yang dicatat manual akan direkam dengan stampel waktu dan ID Kasir yang bertugas.
                </p>
            </div>
            <div>
                <div class="integrity-row">
                    <span class="integrity-key">Terakhir Update:</span>
                    <span class="integrity-val">{{ $lastUpdated }}</span>
                </div>
                <div class="integrity-row">
                    <span class="integrity-key">Status Shift:</span>
                    <span class="integrity-val">
                        <span class="status-dot"></span> Terbuka
                    </span>
                </div>
                <div class="integrity-row">
                    <span class="integrity-key">Kasir Aktif:</span>
                    <span class="integrity-val">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div style="text-align:center;padding:1rem 0;font-size:11px;color:#94A3B8;font-weight:600;">
        © {{ date('Y') }} Apotek Pakis Medika Utama | Clinical Precision Management System v2.4
    </div>

</div>

{{-- Detail Modal --}}
<div id="detailModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.4);z-index:1000;align-items:center;justify-content:center;">
    <div style="background:white;border-radius:8px;padding:1.5rem;max-width:420px;width:90%;box-shadow:0 20px 60px rgba(0,0,0,.15);">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h3 style="font-size:15px;font-weight:800;color:#1E293B;margin:0;">Detail Arus Kas</h3>
            <button onclick="closeDetail()" style="background:none;border:none;cursor:pointer;color:#64748B;">
                <i data-lucide="x" size="18"></i>
            </button>
        </div>
        <table style="width:100%;font-size:13px;border-collapse:collapse;">
            <tr><td style="padding:8px 0;color:#64748B;font-weight:700;width:40%">Keterangan</td><td id="dKet" style="font-weight:800;color:#1E293B;"></td></tr>
            <tr><td style="padding:8px 0;color:#64748B;font-weight:700;">Tanggal</td><td id="dTgl" style="font-weight:700;color:#475569;"></td></tr>
            <tr><td style="padding:8px 0;color:#64748B;font-weight:700;">Nominal</td><td id="dNom" style="font-weight:800;"></td></tr>
            <tr><td style="padding:8px 0;color:#64748B;font-weight:700;">Jenis</td><td id="dType"></td></tr>
            <tr><td style="padding:8px 0;color:#64748B;font-weight:700;">Kategori</td><td id="dCat" style="font-weight:700;color:#475569;"></td></tr>
        </table>
    </div>
</div>

{{-- Print Preview Modal --}}
<div id="printModal" class="modal-backdrop">
    <div class="modal-content" style="max-width: 800px; padding: 0;">
        <div style="padding: 1.5rem; border-bottom: 1px solid #F1F5F9; display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 16px; font-weight: 700; color: #1E293B; margin: 0;">Preview Cetak Laporan Arus Kas</h3>
            <button onclick="closeModal()" style="background: none; border: none; color: #64748B; cursor: pointer;">
                <i data-lucide="x" size="20"></i>
            </button>
        </div>
        <div style="padding: 1rem; background: #F8FAFC;">
            <iframe id="printFrame" style="width: 100%; height: 500px; border: 1px solid #E2E8F0; border-radius: 8px; background: white;"></iframe>
        </div>
        <div style="padding: 1.5rem; display: flex; gap: 12px; justify-content: flex-end; border-top: 1px solid #F1F5F9;">
            <button onclick="closeModal()" class="btn btn-outline" style="min-width: 120px;">Batal</button>
            <button onclick="doPrint()" class="btn btn-primary" style="min-width: 150px; background: #0F62FE; color: white;">
                <i data-lucide="printer" size="18" style="margin-right: 8px;"></i>
                Cetak Laporan
            </button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function setType(t) {
    document.getElementById('typeInput').value = t;
    const bM = document.getElementById('btnMasuk');
    const bK = document.getElementById('btnKeluar');
    bM.className = 'toggle-btn' + (t==='masuk' ? ' active-masuk' : '');
    bK.className = 'toggle-btn' + (t==='keluar' ? ' active-keluar' : '');
}

function exportPDF() {
    const form = document.getElementById('filterForm');
    const params = new URLSearchParams(new FormData(form)).toString();
    const url = "{{ route('kasir.cashflow.export') }}?" + params;
    
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

function showDetail(ket, tgl, nom, type, cat) {
    document.getElementById('dKet').textContent  = ket;
    document.getElementById('dTgl').textContent  = tgl;
    document.getElementById('dNom').textContent  = nom;
    document.getElementById('dNom').style.color  = type === 'MASUK' ? '#10B981' : '#EF4444';
    document.getElementById('dType').innerHTML   = `<span class="type-pill ${type==='MASUK'?'pill-masuk':'pill-keluar'}">${type}</span>`;
    document.getElementById('dCat').textContent  = cat;
    const m = document.getElementById('detailModal');
    m.style.display = 'flex';
    if(window.lucide) lucide.createIcons();
}

function closeDetail() {
    document.getElementById('detailModal').style.display = 'none';
}

window.addEventListener('click', function(e) {
    if(e.target === document.getElementById('detailModal')) closeDetail();
    if(e.target === document.getElementById('printModal')) closeModal();
});
</script>
@endpush
