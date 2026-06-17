@extends('layouts.app')
@section('title', 'Validasi Obat Masuk')

@section('content')
<style>
    .val-header { display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:1.5rem; }
    .val-title { font-size:22px; font-weight:800; color:#1E293B; margin:0; }
    .val-subtitle { font-size:13px; color:#64748B; margin:4px 0 0; font-weight:500; }
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
    .breadcrumb a { color:#64748B; text-decoration:none; }
    .breadcrumb span.active { color:#0F62FE; }

    /* KPI row */
    .kpi-row { display:grid; grid-template-columns:repeat(3,1fr); gap:1rem; margin-bottom:1.5rem; }
    .kpi-box { background:white; border:1px solid #E2E8F0; border-radius:8px; padding:1.25rem 1.5rem; display:flex; align-items:center; gap:1rem; }
    .kpi-icon { width:44px; height:44px; border-radius:8px; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .kpi-icon-blue { background:#EFF6FF; color:#1D4ED8; }
    .kpi-icon-red  { background:#FEF2F2; color:#DC2626; }
    .kpi-icon-green{ background:#ECFDF5; color:#059669; }
    .kpi-num { font-size:28px; font-weight:800; color:#1E293B; line-height:1; }
    .kpi-lbl { font-size:12px; color:#64748B; font-weight:600; margin-top:2px; }

    /* Search bar */
    .toolbar { display:flex; align-items:center; gap:10px; margin-bottom:1rem; }
    .search-faktur { flex:1; position:relative; }
    .search-faktur input { width:100%; padding:9px 12px 9px 36px; border:1px solid #CBD5E1; border-radius:6px; font-size:13px; background:#F8FAFC; outline:none; box-sizing:border-box; }
    .search-faktur .search-icon { position:absolute; left:10px; top:50%; transform:translateY(-50%); color:#94A3B8; pointer-events:none; display:flex; align-items:center; }
    .btn-filter { background:white; border:1px solid #CBD5E1; border-radius:6px; padding:8px 14px; font-size:12px; font-weight:700; color:#475569; cursor:pointer; display:flex; align-items:center; gap:6px; }

    /* Table */
    .val-card { background:white; border:1px solid #E2E8F0; border-radius:8px; overflow:hidden; }
    .val-card-header { padding:1rem 1.5rem; border-bottom:1px solid #F1F5F9; display:flex; align-items:center; justify-content:space-between; }
    .val-card-title { font-size:15px; font-weight:800; color:#1E293B; }
    .val-table { width:100%; border-collapse:collapse; }
    .val-table th { background:#F8FAFC; padding:10px 16px; font-size:10.5px; font-weight:800; color:#64748B; text-transform:uppercase; letter-spacing:.5px; text-align:left; border-bottom:1px solid #E2E8F0; }
    .val-table td { padding:14px 16px; font-size:13px; color:#475569; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
    .val-table tr:last-child td { border-bottom:none; }
    .val-table tr:hover td { background:#F8FAFC; }
    .faktur-name { font-weight:700; color:#1E293B; font-size:13.5px; }
    .faktur-sub  { font-size:11px; color:#94A3B8; font-weight:500; margin-top:2px; }

    /* Status pills */
    .pill { display:inline-block; padding:3px 10px; border-radius:20px; font-size:10px; font-weight:800; text-transform:uppercase; letter-spacing:.4px; }
    .pill-diterima  { background:#DCFCE7; color:#15803D; border:1px solid #BBF7D0; }
    .pill-pending   { background:#FEF9C3; color:#92400E; border:1px solid #FDE68A; }
    .pill-validated { background:#EFF6FF; color:#1D4ED8; border:1px solid #BFDBFE; }

    /* Action buttons */
    .btn-validasi { background:#0B3E9C; color:white; border:none; border-radius:5px; padding:7px 16px; font-size:12px; font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:6px; transition:background .2s; }
    .btn-validasi:hover { background:#0046B5; }
    .btn-validated { background:#ECFDF5; color:#059669; border:1px solid #A7F3D0; border-radius:5px; padding:7px 16px; font-size:12px; font-weight:700; display:inline-flex; align-items:center; gap:6px; }

    /* Empty state */
    .empty-state { text-align:center; padding:3rem; color:#94A3B8; }

    /* Validation Panel */
    #panelOverlay { display:none; position:fixed; inset:0; background:rgba(15,23,42,.55); z-index:900; }
    #panelDrawer  { display:none; position:fixed; top:0; right:0; bottom:0; width:760px; background:white; z-index:901; box-shadow:-8px 0 40px rgba(0,0,0,.15); overflow-y:auto; flex-direction:column; }
    #panelDrawer.open { display:flex; }

    .drawer-header { background:linear-gradient(135deg,#0B3E9C,#1D4ED8); padding:1.25rem 1.5rem; color:white; display:flex; align-items:center; justify-content:space-between; flex-shrink:0; }
    .drawer-title  { font-size:16px; font-weight:800; }
    .drawer-meta   { font-size:12px; color:#BFDBFE; margin-top:3px; }
    .btn-close-drawer { background:rgba(255,255,255,.15); border:none; color:white; border-radius:6px; padding:6px 10px; cursor:pointer; font-size:18px; line-height:1; }

    .drawer-body { flex:1; padding:1.5rem; }
    .drawer-note-row { background:#EFF6FF; border:1px solid #BFDBFE; border-radius:6px; padding:.875rem 1rem; margin-bottom:1.25rem; font-size:12.5px; color:#1E3A8A; font-weight:600; display:flex; align-items:center; gap:8px; }

    /* Check table */
    .check-table { width:100%; border-collapse:collapse; }
    .check-table th { padding:9px 10px; font-size:10px; font-weight:800; color:#94A3B8; text-transform:uppercase; letter-spacing:.5px; border-bottom:2px solid #E2E8F0; text-align:left; }
    .check-table td { padding:10px; border-bottom:1px solid #F1F5F9; vertical-align:middle; }
    .check-table tr:last-child td { border-bottom:none; }
    .med-name  { font-weight:700; color:#1E293B; font-size:13px; }
    .med-kat   { font-size:11px; color:#94A3B8; font-weight:500; }
    .sys-info  { font-size:12px; font-weight:600; color:#475569; }
    .sys-info span { display:block; }
    .check-input { width:100%; padding:7px 9px; border:1px solid #CBD5E1; border-radius:5px; font-size:12.5px; font-weight:600; background:#F8FAFC; outline:none; box-sizing:border-box; }
    .check-input:focus { border-color:#0B3E9C; background:white; }
    .check-select { width:100%; padding:7px 9px; border:1px solid #CBD5E1; border-radius:5px; font-size:12.5px; font-weight:600; background:#F8FAFC; outline:none; box-sizing:border-box; }
    .kesesuaian-btn { width:32px; height:32px; border-radius:50%; border:2px solid #E2E8F0; background:white; cursor:pointer; display:flex; align-items:center; justify-content:center; transition:all .2s; }
    .kesesuaian-btn.ok { border-color:#059669; background:#ECFDF5; color:#059669; }
    .kesesuaian-btn.notok { border-color:#DC2626; background:#FEF2F2; color:#DC2626; }

    .drawer-footer { border-top:1px solid #E2E8F0; padding:1rem 1.5rem; display:flex; gap:10px; justify-content:flex-end; flex-shrink:0; background:#F8FAFC; }
    .btn-tunda { background:white; border:1.5px solid #CBD5E1; color:#475569; border-radius:6px; padding:9px 20px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; }
    .btn-selesai { background:#059669; color:white; border:none; border-radius:6px; padding:9px 22px; font-size:13px; font-weight:700; cursor:pointer; display:flex; align-items:center; gap:6px; }
    .btn-selesai:hover { background:#047857; }
</style>

<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
    <span>&gt;</span>
    <span class="active">Validasi Obat Masuk</span>
</div>

<div class="val-header">
    <div>
        <h1 class="val-title">Validasi Obat Masuk</h1>
        <p class="val-subtitle">Konfirmasi kesesuaian fisik obat yang diterima gudang dengan data sistem.</p>
    </div>
</div>

<!-- KPI Row -->
<div class="kpi-row">
    <div class="kpi-box">
        <div class="kpi-icon kpi-icon-blue"><i data-lucide="clipboard-list" size="22"></i></div>
        <div>
            <div class="kpi-num">{{ $menunggu }}</div>
            <div class="kpi-lbl">Menunggu Validasi</div>
        </div>
    </div>
    <div class="kpi-box">
        <div class="kpi-icon kpi-icon-red"><i data-lucide="alert-circle" size="22"></i></div>
        <div>
            <div class="kpi-num" style="color:#DC2626;">{{ $urgensi }}</div>
            <div class="kpi-lbl">Urgensi Tinggi (Hampir ED)</div>
        </div>
    </div>
    <div class="kpi-box">
        <div class="kpi-icon kpi-icon-green"><i data-lucide="check-circle-2" size="22"></i></div>
        <div>
            <div class="kpi-num" style="color:#059669;">{{ $validated }}</div>
            <div class="kpi-lbl">Sudah Tervalidasi</div>
        </div>
    </div>
</div>

<!-- Table Card -->
<div class="val-card">
    <div class="val-card-header">
        <span class="val-card-title">Daftar Kedatangan Obat</span>
        <div class="toolbar" style="margin:0;">
            <div class="search-faktur">
                <span class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
                </span>
                <input type="text" id="searchInput" placeholder="Cari No. Faktur atau Supplier...">
            </div>
            <select class="btn-filter" id="statusFilter" style="border-radius:6px; padding:8px 12px; font-size:12px; font-weight:700; color:#475569; border:1px solid #CBD5E1; background:white;">
                <option value="all">Semua Status</option>
                <option value="pending">Belum Validasi</option>
                <option value="validated">Sudah Validasi</option>
            </select>
        </div>
    </div>

    <table class="val-table" id="fakturTable">
        <thead>
            <tr>
                <th>TGL DATANG</th>
                <th>NO. FAKTUR</th>
                <th>TIPE</th>
                <th style="text-align:center;">JUMLAH ITEM</th>
                <th>STATUS GUDANG</th>
                <th style="text-align:center;">ACTION</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fakturList as $f)
            <tr data-validated="{{ $f->is_validated ? '1' : '0' }}" data-faktur="{{ strtolower($f->no_faktur) }}">
                <td style="color:#64748B; font-weight:600;">{{ $f->tanggal_masuk }}</td>
                <td>
                    <div class="faktur-name">{{ $f->no_faktur }}</div>
                    <div class="faktur-sub">{{ $f->tipe_faktur }}</div>
                </td>
                <td>
                    @if($f->tipe_faktur === 'Tempo')
                        <span class="pill" style="background:#FEF3C7;color:#92400E;border:1px solid #FDE68A;">Tempo</span>
                    @elseif($f->tipe_faktur === 'Titipan')
                        <span class="pill" style="background:#F5F3FF;color:#6D28D9;border:1px solid #DDD6FE;">Titipan</span>
                    @else
                        <span class="pill" style="background:#F0FDF4;color:#15803D;border:1px solid #BBF7D0;">Lunas</span>
                    @endif
                </td>
                <td style="text-align:center; font-weight:800; color:#0B3E9C; font-size:15px;">{{ $f->items_count }}</td>
                <td>
                    @if($f->is_validated)
                        <span class="pill pill-validated"><i data-lucide="check" size="10" style="vertical-align:middle;"></i> Tervalidasi</span>
                    @elseif($f->gudang_status === 'pending')
                        <span class="pill pill-pending">Pending</span>
                    @else
                        <span class="pill pill-diterima">Diterima Gudang</span>
                    @endif
                </td>
                <td style="text-align:center;">
                    @if($f->is_validated)
                        <span class="btn-validated">
                            <i data-lucide="check-circle-2" size="14"></i> Sudah Divalidasi
                        </span>
                    @else
                        <button class="btn-validasi" onclick="openPanel('{{ $f->no_faktur }}')">
                            <i data-lucide="clipboard-check" size="14"></i> Validasi
                        </button>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="empty-state">
                    <i data-lucide="inbox" size="40" style="display:block;margin:0 auto 8px;color:#CBD5E1;"></i>
                    Belum ada data obat masuk.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Overlay -->
<div id="panelOverlay" onclick="closePanel()"></div>

<!-- Validation Panel Drawer -->
<div id="panelDrawer">
    <div class="drawer-header">
        <div>
            <div class="drawer-title" id="drawerTitle">Panel Validasi</div>
            <div class="drawer-meta" id="drawerMeta">Memuat data...</div>
        </div>
        <button class="btn-close-drawer" onclick="closePanel()">&times;</button>
    </div>

    <div class="drawer-body">
        <div class="drawer-note-row">
            <i data-lucide="info" size="16"></i>
            Periksa kesesuaian fisik obat dengan data sistem. Isi jumlah fisik, nomor batch, dan tanggal kedaluwarsa yang tertera pada kemasan, lalu tentukan kondisinya.
        </div>

        <div id="panelLoading" style="text-align:center; padding:3rem; color:#94A3B8;">
            <i data-lucide="loader" size="32" style="animation:spin 1s linear infinite; display:block; margin:0 auto 8px;"></i>
            Memuat data faktur...
        </div>

        <div id="panelContent" style="display:none;">
            <table class="check-table" id="checkTable">
                <thead>
                    <tr>
                        <th style="width:22%;">Nama Obat</th>
                        <th style="width:20%;">Data Gudang (Faktur)</th>
                        <th style="width:13%;">Qty Fisik</th>
                        <th style="width:15%;">No. Batch Fisik</th>
                        <th style="width:13%;">ED Fisik</th>
                        <th style="width:12%;">Kondisi</th>
                        <th style="width:7%; text-align:center;">✓</th>
                    </tr>
                </thead>
                <tbody id="checkTableBody">
                </tbody>
            </table>

            <div style="margin-top:1.25rem;">
                <label style="font-size:11px; font-weight:800; color:#64748B; text-transform:uppercase; letter-spacing:.4px; display:block; margin-bottom:6px;">Catatan Validasi (Opsional)</label>
                <textarea id="validationNotes" rows="3" style="width:100%; border:1px solid #CBD5E1; border-radius:6px; padding:10px 12px; font-size:13px; font-weight:500; background:#F8FAFC; outline:none; box-sizing:border-box; resize:vertical;" placeholder="Catatan tambahan untuk validasi ini..."></textarea>
            </div>
        </div>
    </div>

    <div class="drawer-footer">
        <button class="btn-tunda" id="btnTunda" onclick="deferValidation()">
            <i data-lucide="clock" size="15"></i> Tunda Validasi
        </button>
        <button class="btn-selesai" id="btnSelesai" onclick="submitValidation()">
            <i data-lucide="check-circle-2" size="15"></i> Selesaikan Validasi
        </button>
    </div>
</div>

<style>
@keyframes spin { 100% { transform: rotate(360deg); } }
</style>

<script>
let currentFaktur = null;
let panelItems = [];

function openPanel(noFaktur) {
    currentFaktur = noFaktur;
    document.getElementById('panelOverlay').style.display = 'block';
    const drawer = document.getElementById('panelDrawer');
    drawer.classList.add('open');
    document.getElementById('drawerTitle').textContent = 'Panel Validasi: ' + noFaktur;
    document.getElementById('drawerMeta').textContent = 'Memuat data...';
    document.getElementById('panelLoading').style.display = 'block';
    document.getElementById('panelContent').style.display = 'none';

    fetch(`/apoteker/validasi/panel?no=${encodeURIComponent(noFaktur)}`)
        .then(r => r.json())
        .then(data => {
            panelItems = data.items;
            document.getElementById('drawerMeta').textContent =
                (data.supplier || '-') + ' | ' + data.tanggal_masuk;

            const tbody = document.getElementById('checkTableBody');
            tbody.innerHTML = '';

            data.items.forEach((item, idx) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>
                        <div class="med-name">${item.nama}</div>
                        <div class="med-kat">${item.kategori}</div>
                    </td>
                    <td class="sys-info">
                        <span>Diterima: <b>${item.qty_system} ${item.satuan}</b></span>
                        <span style="color:#94A3B8;">Sisa: ${item.stok_sisa} ${item.satuan}</span>
                        <span>Batch: <b>${item.no_batch}</b></span>
                        <span>ED: <b>${item.tanggal_kadaluwarsa}</b></span>
                    </td>
                    <td>
                        <input type="number" class="check-input" id="pqty_${idx}"
                            value="${item.physical_qty}" min="0">
                    </td>
                    <td>
                        <input type="text" class="check-input" id="pbatch_${idx}"
                            value="${item.physical_batch}">
                    </td>
                    <td>
                        <input type="text" class="check-input" id="pexpiry_${idx}"
                            value="${item.physical_expiry}" placeholder="mm/yy">
                    </td>
                    <td>
                        <select class="check-select" id="kondisi_${idx}">
                            <option value="Baik" ${item.kondisi==='Baik'?'selected':''}>Baik</option>
                            <option value="Rusak Ringan" ${item.kondisi==='Rusak Ringan'?'selected':''}>Rusak Ringan</option>
                            <option value="Rusak Berat" ${item.kondisi==='Rusak Berat'?'selected':''}>Rusak Berat</option>
                        </select>
                    </td>
                    <td style="text-align:center;">
                        <button type="button" class="kesesuaian-btn ${item.kesesuaian ? 'ok' : 'notok'}"
                            id="ksesuaian_${idx}"
                            onclick="toggleKesesuaian(${idx})"
                            title="Toggle kesesuaian">
                            <i data-lucide="${item.kesesuaian ? 'check' : 'x'}" size="14"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(row);
            });

            lucide.createIcons();
            document.getElementById('panelLoading').style.display = 'none';
            document.getElementById('panelContent').style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            document.getElementById('panelLoading').innerHTML =
                '<span style="color:#EF4444;">Gagal memuat data. Coba lagi.</span>';
        });
}

function toggleKesesuaian(idx) {
    const btn = document.getElementById('ksesuaian_' + idx);
    const isOk = btn.classList.contains('ok');
    btn.classList.toggle('ok', !isOk);
    btn.classList.toggle('notok', isOk);
    btn.innerHTML = `<i data-lucide="${!isOk ? 'check' : 'x'}" size="14"></i>`;
    lucide.createIcons();
}

function closePanel() {
    document.getElementById('panelOverlay').style.display = 'none';
    document.getElementById('panelDrawer').classList.remove('open');
    currentFaktur = null;
    panelItems = [];
}

function submitValidation() {
    if (!currentFaktur || panelItems.length === 0) return;

    const items = panelItems.map((item, idx) => ({
        id: item.id,
        physical_qty:    document.getElementById('pqty_' + idx).value,
        physical_batch:  document.getElementById('pbatch_' + idx).value,
        physical_expiry: document.getElementById('pexpiry_' + idx).value,
        kondisi:         document.getElementById('kondisi_' + idx).value,
        kesesuaian:      document.getElementById('ksesuaian_' + idx).classList.contains('ok') ? 1 : 0,
    }));

    const notes = document.getElementById('validationNotes').value;

    Swal.fire({
        title: 'Konfirmasi Validasi',
        html: `Anda akan memvalidasi faktur <b>${currentFaktur}</b> dengan <b>${items.length} item</b>.<br>Pastikan semua data fisik sudah sesuai.`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#059669',
        cancelButtonColor: '#6B7280',
        confirmButtonText: '<i class="swal-icon"></i> Ya, Selesaikan Validasi',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (!result.isConfirmed) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content
                       || '{{ csrf_token() }}';

        fetch(`/apoteker/validasi/confirm`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ no_faktur: currentFaktur, items, notes }),
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                closePanel();
                Swal.fire({
                    title: 'Validasi Berhasil!',
                    text: resp.message,
                    icon: 'success',
                    confirmButtonColor: '#059669',
                    timer: 2500,
                    timerProgressBar: true,
                }).then(() => location.reload());
            } else {
                Swal.fire('Gagal', resp.message || 'Terjadi kesalahan.', 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Gagal menghubungi server.', 'error'));
    });
}

function deferValidation() {
    if (!currentFaktur) return;

    Swal.fire({
        title: 'Tunda Validasi?',
        text: `Faktur ${currentFaktur} akan ditandai sebagai PENDING. Anda dapat memvalidasinya nanti.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#D97706',
        cancelButtonColor: '#6B7280',
        confirmButtonText: 'Ya, Tunda',
        cancelButtonText: 'Batal',
    }).then(result => {
        if (!result.isConfirmed) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';
        fetch(`/apoteker/validasi/defer`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            body: JSON.stringify({ no_faktur: currentFaktur }),
        })
        .then(r => r.json())
        .then(resp => {
            if (resp.success) {
                closePanel();
                Swal.fire({
                    title: 'Ditunda',
                    text: resp.message,
                    icon: 'info',
                    confirmButtonColor: '#0B3E9C',
                    timer: 2000,
                    timerProgressBar: true,
                }).then(() => location.reload());
            }
        });
    });
}

// Search + filter
document.getElementById('searchInput').addEventListener('input', filterTable);
document.getElementById('statusFilter').addEventListener('change', filterTable);

function filterTable() {
    const q      = document.getElementById('searchInput').value.toLowerCase();
    const status = document.getElementById('statusFilter').value;
    document.querySelectorAll('#fakturTable tbody tr[data-faktur]').forEach(row => {
        const fakturMatch  = row.dataset.faktur.includes(q);
        const validated    = row.dataset.validated === '1';
        const statusMatch  = status === 'all'
            || (status === 'validated' && validated)
            || (status === 'pending'   && !validated);
        row.style.display = (fakturMatch && statusMatch) ? '' : 'none';
    });
}
</script>

@endsection
