@extends('layouts.app')

@section('title', 'Input Stock Masuk (Faktur)')

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
        align-items: flex-start;
        margin-bottom: 2rem;
    }

    .header-left {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .page-title-row {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
        letter-spacing: -0.5px;
    }

    .fifo-badge {
        background: #DCFCE7;
        color: #15803D;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        border: 1px solid #BBF7D0;
    }

    .page-subtitle {
        color: #64748B;
        font-size: 13.5px;
        margin: 0;
        font-style: italic;
        font-weight: 500;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-cancel {
        background: white;
        border: 1px solid #CBD5E1;
        color: #475569;
        border-radius: 4px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #F8FAFC;
        color: #1E293B;
        border-color: #94A3B8;
    }

    .btn-submit {
        background: #0F62FE;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: background 0.2s;
    }

    .btn-submit:hover {
        background: #0046B5;
    }

    /* Main layout grid */
    .faktur-grid {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 1.25rem;
        align-items: start;
        margin-bottom: 1.5rem;
    }

    .faktur-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .card-title {
        font-size: 12px;
        font-weight: 800;
        color: #1E293B;
        margin: 0 0 1.25rem 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #F1F5F9;
        padding-bottom: 0.75rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 1.25rem;
    }

    .form-label {
        font-size: 11px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-input {
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 600;
        color: #1E293B;
        outline: none;
        transition: all 0.2s;
        width: 100%;
        box-sizing: border-box;
        height: 41px;
    }

    .form-input:focus {
        border-color: #0F62FE;
        background: white;
        box-shadow: 0 0 0 3px rgba(15, 98, 254, 0.08);
    }

    .form-select {
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 600;
        color: #1E293B;
        outline: none;
        cursor: pointer;
        width: 100%;
        box-sizing: border-box;
        height: 41px;
    }

    .form-select:focus {
        border-color: #0F62FE;
    }

    .form-row-grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    /* Falling card for Tempo */
    .tempo-alert-card {
        background: #EFF6FF;
        border: 1px dashed #BFDBFE;
        border-radius: 4px;
        padding: 1rem;
        margin-top: 0.5rem;
    }

    .tempo-alert-title {
        font-size: 11px;
        font-weight: 800;
        color: #1E40AF;
        text-transform: uppercase;
        margin-bottom: 6px;
    }

    .tempo-alert-text {
        font-size: 11px;
        color: #2563EB;
        margin-top: 6px;
        font-weight: 500;
    }

    /* Items Table Column Adjustments */
    .items-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1.25rem;
    }

    .items-table th {
        text-align: left;
        padding: 8px 10px;
        font-size: 10px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        border-bottom: 2px solid #E2E8F0;
        letter-spacing: 0.5px;
    }

    .items-table td {
        padding: 6px;
        vertical-align: middle;
    }

    .btn-add-row {
        background: #15803D;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        font-weight: 700;
        font-size: 11px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: background 0.2s;
    }

    .btn-add-row:hover {
        background: #166534;
    }

    .btn-remove-row {
        background: #FEE2E2;
        color: #EF4444;
        border: none;
        padding: 8px;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .btn-remove-row:hover {
        background: #FCA5A5;
    }

    /* Compact styles for table inputs to prevent truncation */
    .items-table .form-input {
        padding: 10px 6px;
        font-size: 13px;
        width: 100% !important;
        box-sizing: border-box;
    }
    .items-table .form-select {
        padding: 10px 4px;
        font-size: 13px;
        width: 100% !important;
        box-sizing: border-box;
    }
    .items-table input[type=number]::-webkit-inner-spin-button, 
    .items-table input[type=number]::-webkit-outer-spin-button { 
        -webkit-appearance: none; 
        margin: 0; 
    }
    .items-table input[type=number] {
        -moz-appearance: textfield;
    }

    /* Bottom Info blocks */
    .info-row-grid {
        display: grid;
        grid-template-columns: 1.3fr 1fr;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .info-alert-box {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        border-radius: 4px;
        padding: 1.25rem;
        display: flex;
        gap: 12px;
    }

    .info-alert-icon {
        color: #1D4ED8;
        display: flex;
        align-items: flex-start;
    }

    .info-alert-content h4 {
        margin: 0 0 6px 0;
        font-size: 13px;
        font-weight: 800;
        color: #1E3A8A;
    }

    .info-alert-content p {
        margin: 0;
        font-size: 12px;
        color: #1E40AF;
        line-height: 1.5;
        font-weight: 500;
    }

    .price-summary-box {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 1.25rem;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 8px;
    }

    .summary-label {
        font-size: 13px;
        color: #475569;
        font-weight: 500;
    }

    .summary-val {
        font-size: 14px;
        font-weight: 700;
        color: #1E293B;
    }

    .summary-grand-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 2px solid #E2E8F0;
        margin-top: 12px;
        padding-top: 12px;
    }

    .grand-label {
        font-size: 13px;
        font-weight: 800;
        color: #0F62FE;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .grand-val {
        font-size: 20px;
        font-weight: 800;
        color: #0F62FE;
    }

    /* FIFO Bottom Banner */
    .fifo-banner {
        background: #EFF6FF;
        border: 1px solid #BFDBFE;
        border-radius: 4px;
        padding: 1.5rem;
        margin-top: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        position: relative;
        overflow: hidden;
    }

    .fifo-banner::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #0F62FE;
    }

    .fifo-banner-left {
        max-width: 75%;
    }

    .fifo-banner-title {
        font-size: 16px;
        font-weight: 800;
        color: #1E3A8A;
        margin-bottom: 6px;
    }

    .fifo-banner-text {
        font-size: 12px;
        color: #1E40AF;
        line-height: 1.5;
        font-weight: 500;
    }

    .fifo-banner-graphic {
        display: flex;
        align-items: center;
        gap: 10px;
        background: white;
        border: 1px solid #BFDBFE;
        padding: 8px 16px;
        border-radius: 8px;
    }

    .graphic-node {
        text-align: center;
    }

    .graphic-num {
        font-size: 16px;
        font-weight: 800;
        color: #0F62FE;
    }

    .graphic-lbl {
        font-size: 9px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .graphic-arrow {
        color: #64748B;
    }
</style>

<form action="{{ route('batches.store') }}" method="POST" id="supplyForm">
    @csrf

    <!-- Top Action Area -->
    <div>
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
            <span>&gt;</span>
            <span class="active">Input Obat Masuk</span>
        </div>

        <div class="header-area">
            <div class="header-left">
                <div class="page-title-row">
                    <h1 class="page-title">Input Stock Masuk (Faktur)</h1>
                    <span class="fifo-badge">
                        <i data-lucide="check" size="12"></i>
                        FIFO Status: ACTIVE
                    </span>
                </div>
                <p class="page-subtitle">Mendukung pengelolaan stok berdasarkan prinsip FIFO (First-In, First-Out).</p>
            </div>
            
            <div class="header-actions">
                <a href="{{ route('batches.index') }}" class="btn-cancel">
                    <i data-lucide="x" size="16"></i>
                    Batal
                </a>
                <button type="submit" class="btn-submit">
                    <i data-lucide="save" size="16"></i>
                    Simpan Data
                </button>
            </div>
        </div>
    </div>

    <!-- Main Two Column Grid -->
    <div class="faktur-grid">
        
        <!-- Left Column: Detail Faktur -->
        <div class="faktur-card">
            <h3 class="card-title">
                <span><i data-lucide="file-text" size="16" style="color: #0F62FE; vertical-align: middle; margin-right: 6px;"></i>Detail Faktur</span>
            </h3>

            <div class="form-group">
                <label class="form-label" for="no_faktur">Nomor Faktur</label>
                <input type="text" name="no_faktur" id="no_faktur" class="form-input" placeholder="Contoh: INV/2023/10/001" required value="{{ old('no_faktur') }}">
                @error('no_faktur') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row-grid-2">
                <div class="form-group">
                    <label class="form-label" for="tanggal_masuk">Tanggal Faktur</label>
                    <input type="date" name="tanggal_masuk" id="tanggal_masuk" value="{{ old('tanggal_masuk', date('Y-m-d')) }}" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="tipe_faktur">Jenis Faktur</label>
                    <select name="tipe_faktur" id="tipe_faktur" class="form-select" required>
                        <option value="Lunas" {{ old('tipe_faktur') == 'Lunas' ? 'selected' : '' }}>Lunas</option>
                        <option value="Tempo" {{ old('tipe_faktur') == 'Tempo' ? 'selected' : '' }}>Tempo</option>
                        <option value="Titipan" {{ old('tipe_faktur') == 'Titipan' ? 'selected' : '' }}>Titipan</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="supplier_id">Nama Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-select" required>
                    <option value="">Pilih Supplier...</option>
                    @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Falldown Tempo Box -->
            <div class="tempo-alert-card" id="tempoBox" style="display: none;">
                <div class="tempo-alert-title">Jatuh Tempo</div>
                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo', date('Y-m-d', strtotime('+30 days'))) }}" class="form-input" style="background: white;">
                <div class="tempo-alert-text">*Hanya berlaku untuk jenis faktur Tempo.</div>
            </div>
        </div>

        <!-- Right Column: Daftar Item Obat -->
        <div class="faktur-card">
            <h3 class="card-title">
                <span><i data-lucide="package" size="16" style="color: #0F62FE; vertical-align: middle; margin-right: 6px;"></i>Daftar Item Obat</span>
                <button type="button" id="addRow" class="btn-add-row">
                    <i data-lucide="plus" size="13"></i>
                    TAMBAH BARIS
                </button>
            </h3>

            <table class="items-table" id="itemsTable">
                <thead>
                    <tr>
                        <th style="width: 32%;">Nama Obat</th>
                        <th style="width: 14%;">No. Batch</th>
                        <th style="width: 20%;">ED (Expired)</th>
                        <th style="width: 9%; text-align: center;">Qty</th>
                        <th style="width: 18%; text-align: right;">Harga Satuan</th>
                        <th style="width: 7%; text-align: center;">Disc (%)</th>
                        <th style="width: 40px;"></th>
                    </tr>
                </thead>
                <tbody id="itemsBody">
                    <!-- Initial Row -->
                    <tr class="item-row">
                        <td>
                            <select name="items[0][medicine_id]" class="form-select med-select" required>
                                <option value="">Pilih Obat...</option>
                                @foreach($medicines as $med)
                                    <option value="{{ $med->id }}" data-price="{{ $med->harga }}">{{ $med->nama }} ({{ $med->kode }})</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" name="items[0][no_batch]" class="form-input" placeholder="Batch..." required>
                        </td>
                        <td>
                            <input type="date" name="items[0][tanggal_kadaluwarsa]" class="form-input" required>
                        </td>
                        <td>
                            <input type="number" name="items[0][stok_sisa]" class="form-input qty-input" style="text-align: center;" placeholder="0" required min="1">
                        </td>
                        <td>
                            <input type="number" name="items[0][harga_satuan]" class="form-input price-input" style="text-align: right;" placeholder="0" required min="0">
                        </td>
                        <td>
                            <input type="number" name="items[0][discount]" class="form-input disc-input" style="text-align: center;" value="0" required min="0" max="100">
                        </td>
                        <td style="text-align: center;">
                            <button type="button" class="btn-remove-row remove-row" style="display: none;">
                                <i data-lucide="x" size="14"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Bottom Row: Info & Pricing -->
            <div class="info-row-grid">
                
                <!-- Bottom Left: Informasi Penting -->
                <div class="info-alert-box">
                    <div class="info-alert-icon">
                        <i data-lucide="info" size="20"></i>
                    </div>
                    <div class="info-alert-content">
                        <h4>Informasi Penting</h4>
                        <p>Data yang dimasukkan akan secara otomatis tercatat dalam sistem inventory sebagai batch baru. Sistem akan memprioritaskan stok ini untuk keluar setelah stok lama habis berdasarkan Tanggal Kadaluwarsa (FIFO). Pastikan Nomor Batch dan Tanggal Kadaluwarsa sesuai dengan fisik obat.</p>
                    </div>
                </div>

                <!-- Bottom Right: Pricing Summary -->
                <div class="price-summary-box">
                    <div class="summary-line">
                        <span class="summary-label">Total Pembelian</span>
                        <span class="summary-val" id="lblTotal">Rp 0</span>
                    </div>
                    <div class="summary-line">
                        <span class="summary-label">PPN (11%)</span>
                        <span class="summary-val" id="lblPpn">Rp 0</span>
                    </div>
                    <div class="summary-grand-line">
                        <span class="grand-label">Grand Total</span>
                        <span class="grand-val" id="lblGrand">Rp 0</span>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Bottom Full Width Banner: Prinsip FIFO Diaktifkan -->
    <div class="fifo-banner">
        <div class="fifo-banner-left">
            <h4 class="fifo-banner-title">Prinsip FIFO Diaktifkan</h4>
            <p class="fifo-banner-text">Setiap entri obat masuk melalui formulir ini akan divalidasi dan diurutkan dalam antrian stok global. Unit farmasi akan selalu diarahkan untuk mengambil obat dengan tanggal kadaluwarsa terdekat yang Anda masukkan di sini.</p>
        </div>
        <div class="fifo-banner-graphic">
            <div class="graphic-node">
                <div class="graphic-num">01</div>
                <div class="graphic-lbl">Masuk</div>
            </div>
            <div class="graphic-arrow">
                <i data-lucide="arrow-right" size="16"></i>
            </div>
            <div class="graphic-node">
                <div class="graphic-num">01</div>
                <div class="graphic-lbl">Keluar</div>
            </div>
        </div>
    </div>

</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsBody = document.getElementById('itemsBody');
        const addRowBtn = document.getElementById('addRow');
        const tipeFakturSelect = document.getElementById('tipe_faktur');
        const tempoBox = document.getElementById('tempoBox');
        let rowCount = 1;

        // Toggle Tempo falling card
        function checkTempo() {
            if (tipeFakturSelect.value === 'Tempo') {
                tempoBox.style.display = 'block';
            } else {
                tempoBox.style.display = 'none';
            }
        }
        tipeFakturSelect.addEventListener('change', checkTempo);
        checkTempo(); // Initial call

        // Dynamic Calculations
        function calculateSummary() {
            let totalPembelian = 0;
            const rows = document.querySelectorAll('.item-row');
            
            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const disc = parseFloat(row.querySelector('.disc-input').value) || 0;
                
                const subtotal = qty * price * (1 - disc / 100);
                totalPembelian += subtotal;
            });

            const ppn = totalPembelian * 0.11;
            const grandTotal = totalPembelian + ppn;

            // Format as IDR
            document.getElementById('lblTotal').innerText = formatIDR(totalPembelian);
            document.getElementById('lblPpn').innerText = formatIDR(ppn);
            document.getElementById('lblGrand').innerText = formatIDR(grandTotal);
        }

        function formatIDR(num) {
            return 'Rp ' + Math.round(num).toLocaleString('id-ID');
        }

        // Handle price auto-fill on medicine selection
        itemsBody.addEventListener('change', function(e) {
            if (e.target.classList.contains('med-select')) {
                const selectedOption = e.target.options[e.target.selectedIndex];
                const price = selectedOption.getAttribute('data-price') || 0;
                const row = e.target.closest('.item-row');
                const priceInput = row.querySelector('.price-input');
                priceInput.value = price;
                calculateSummary();
            }
        });

        // Event delegation for input events to trigger live calculation
        itemsBody.addEventListener('input', function(e) {
            if (e.target.classList.contains('qty-input') || 
                e.target.classList.contains('price-input') || 
                e.target.classList.contains('disc-input')) {
                calculateSummary();
            }
        });

        // Add Row action
        addRowBtn.addEventListener('click', function() {
            const newRow = document.createElement('tr');
            newRow.className = 'item-row';
            
            newRow.innerHTML = `
                <td>
                    <select name="items[${rowCount}][medicine_id]" class="form-select med-select" required>
                        <option value="">Pilih Obat...</option>
                        @foreach($medicines as $med)
                            <option value="{{ $med->id }}" data-price="{{ $med->harga }}">{{ $med->nama }} ({{ $med->kode }})</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="text" name="items[${rowCount}][no_batch]" class="form-input" placeholder="Batch..." required>
                </td>
                <td>
                    <input type="date" name="items[${rowCount}][tanggal_kadaluwarsa]" class="form-input" required>
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][stok_sisa]" class="form-input qty-input" style="text-align: center;" placeholder="0" required min="1">
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][harga_satuan]" class="form-input price-input" style="text-align: right;" placeholder="0" required min="0">
                </td>
                <td>
                    <input type="number" name="items[${rowCount}][discount]" class="form-input disc-input" style="text-align: center;" value="0" required min="0" max="100">
                </td>
                <td style="text-align: center;">
                    <button type="button" class="btn-remove-row remove-row">
                        <i data-lucide="x" size="14"></i>
                    </button>
                </td>
            `;
            
            itemsBody.appendChild(newRow);
            rowCount++;
            
            // Re-initialize Lucide icons for new content
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
            
            updateRemoveButtons();
            calculateSummary();
        });

        itemsBody.addEventListener('click', function(e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('.item-row').remove();
                updateRemoveButtons();
                calculateSummary();
            }
        });

        function updateRemoveButtons() {
            const rows = document.querySelectorAll('.item-row');
            rows.forEach((row) => {
                const removeBtn = row.querySelector('.remove-row');
                if (rows.length === 1) {
                    removeBtn.style.display = 'none';
                } else {
                    removeBtn.style.display = 'flex';
                }
            });
        }
    });
</script>
@endsection
