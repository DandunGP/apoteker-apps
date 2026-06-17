@extends('layouts.app')

@section('title', 'Sales / POS')

@section('content')
<style>
    .pos-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Outfit', sans-serif;
        color: #1E293B;
    }

    .pos-title {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .pos-subtitle {
        font-size: 13.5px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .pos-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    .section-title {
        font-size: 20px;
        font-weight: 800;
        color: #0B3E9C;
        margin-bottom: 0.25rem;
    }
    .section-subtitle {
        font-size: 13px;
        color: var(--text-muted);
        font-style: italic;
        margin-bottom: 1.5rem;
    }

    .item-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }

    .item-card {
        background: white;
        border: 1px solid var(--border, #E2E8F0);
        border-radius: 8px;
        padding: 1.25rem;
        display: flex;
        flex-direction: column;
        transition: all 0.2s;
    }
    .item-card:hover {
        border-color: var(--primary);
        box-shadow: 0 4px 12px rgba(15, 98, 254, 0.1);
    }
    
    .item-card.category-antibiotik { border-top: 3px solid #0F62FE; }
    .item-card.category-analgesik { border-top: 3px solid #10B981; }
    .item-card.category-vitamin { border-top: 3px solid #3B82F6; }
    .item-card.category-psikotropika { border-top: 3px solid #EF4444; }
    .item-card.category-layanan { border-top: 3px solid #64748B; }

    .item-badge {
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        padding: 4px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 0.75rem;
        align-self: flex-start;
    }
    .badge-antibiotik { background: #E0E8FF; color: #0F62FE; }
    .badge-analgesik { background: #D1FAE5; color: #10B981; }
    .badge-vitamin { background: #DBEAFE; color: #3B82F6; }
    .badge-psikotropika { background: #FEE2E2; color: #EF4444; }
    .badge-layanan { background: #F1F5F9; color: #64748B; }

    .item-title {
        font-size: 15px;
        font-weight: 800;
        color: var(--text-main);
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .item-details {
        font-size: 12px;
        color: var(--text-muted);
        display: grid;
        grid-template-columns: 80px 1fr;
        gap: 4px;
        margin-bottom: 1rem;
    }
    .item-details span:nth-child(even) {
        font-weight: 700;
        color: var(--text-main);
    }
    .item-details .highlight {
        color: #B91C1C;
    }

    .item-footer {
        margin-top: auto;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
    }
    .item-price {
        font-size: 18px;
        font-weight: 800;
        color: #0B3E9C;
    }
    .item-price-sub {
        font-size: 11px;
        color: #EF4444;
        font-weight: 700;
        margin-top: 2px;
    }
    
    .btn-pilih {
        background: #0B3E9C;
        color: white;
        border: none;
        padding: 6px 16px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 12px;
        cursor: pointer;
    }
    .btn-pilih:hover { background: #0F62FE; }

    .cart-sidebar {
        background: #F8FAFC;
        border-radius: 8px;
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        min-height: calc(100vh - 120px);
        position: sticky;
        top: 90px;
    }

    .cart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .cart-title {
        font-size: 16px;
        font-weight: 800;
        color: #0B3E9C;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .btn-kosongkan {
        color: #EF4444;
        font-size: 11px;
        font-weight: 800;
        background: none;
        border: none;
        cursor: pointer;
    }

    .cart-items {
        flex: 1;
        overflow-y: auto;
        margin-bottom: 1.5rem;
    }

    .cart-item {
        background: white;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
    }
    .cart-item-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 0.5rem;
    }
    .cart-item-title {
        font-size: 14px;
        font-weight: 800;
        color: var(--text-main);
    }
    .btn-remove {
        background: none;
        border: none;
        color: var(--text-muted);
        cursor: pointer;
    }
    .cart-item-batch {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        margin-bottom: 1rem;
    }
    .cart-item-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .qty-controls {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #F1F5F9;
        padding: 4px;
        border-radius: 4px;
    }
    .qty-btn {
        background: white;
        border: none;
        width: 24px;
        height: 24px;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-weight: 800;
        color: #0F62FE;
    }
    .qty-val {
        font-size: 13px;
        font-weight: 700;
    }
    .cart-item-price {
        font-size: 14px;
        font-weight: 800;
    }

    .cart-summary {
        background: #F1F5F9;
        padding: 1.25rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: var(--text-main);
        margin-bottom: 0.75rem;
    }
    .summary-row.total {
        font-size: 18px;
        font-weight: 800;
        color: #0B3E9C;
        border-top: 1px solid #E2E8F0;
        padding-top: 1rem;
        margin-bottom: 0;
        margin-top: 0.25rem;
    }

    .payment-methods {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    .btn-payment {
        background: white;
        border: 2px solid #E2E8F0;
        border-radius: 8px;
        padding: 0.75rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 800;
        color: var(--text-muted);
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-payment.active {
        border-color: #0F62FE;
        color: #0F62FE;
        background: #EFF6FF;
    }

    .btn-checkout {
        background: #059669;
        color: white;
        border: none;
        width: 100%;
        padding: 1.25rem;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-checkout:hover { background: #047857; }
    .btn-checkout:disabled { background: #A7F3D0; cursor: not-allowed; }

    .resep-select {
        width: 100%;
        padding: 0.75rem;
        border-radius: 8px;
        border: 1px solid #E2E8F0;
        background: white;
        font-size: 13px;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 1.5rem;
        outline: none;
    }

    .filter-btn {
        background: white;
        border: 1px solid #E2E8F0;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: var(--text-main);
        cursor: pointer;
    }
</style>

<div class="pos-container">
    <!-- Top Breadcrumb & Header Area -->
    <div>
        <!-- Top Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
            <span>&gt;</span>
            <span class="active">Sales / POS</span>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h1 class="pos-title">Inventory Penjualan</h1>
                <p class="pos-subtitle">Prioritas pengeluaran berdasarkan sistem FIFO (First-In, First-Out)</p>
            </div>
            <button class="filter-btn"><i data-lucide="filter" size="14"></i> Filter</button>
        </div>
    </div>

    <div class="pos-layout">
        <!-- Left Area -->
        <div>
            <!-- Medicine Grid -->
            <div class="item-grid">
            @foreach($medicines as $medicine)
                @php
                    $catClass = 'antibiotik';
                    $kategori = strtolower($medicine->kategori ?? '');
                    if (str_contains($kategori, 'analgesik')) $catClass = 'analgesik';
                    elseif (str_contains($kategori, 'vitamin')) $catClass = 'vitamin';
                    elseif (str_contains($kategori, 'psikotropika')) $catClass = 'psikotropika';
                @endphp
                <div class="item-card category-{{ $catClass }}">
                    <div class="item-badge badge-{{ $catClass }}">{{ $medicine->kategori ?? 'UMUM' }}</div>
                    <div class="item-title">{{ $medicine->nama }}</div>
                    
                    <div class="item-details">
                        <span>Batch</span> 
                        <span class="highlight">{{ $medicine->kode }}</span> <!-- Mocking Batch logic with code for display -->
                        
                        <span>Terdekat</span> 
                        <span class="highlight">(12/2026)</span> <!-- Mock data -->
                        
                        <span>Stok Total</span> 
                        <span>{{ number_format($medicine->total_stock, 0, ',', '.') }} Kaplet</span>
                    </div>

                    <div class="item-footer">
                        <div>
                            <div class="item-price">Rp {{ number_format($medicine->harga, 0, ',', '.') }}</div>
                            @if($catClass == 'psikotropika')
                                <div class="item-price-sub">Resep Dokter</div>
                            @endif
                        </div>
                        <button class="btn-pilih" onclick="addToCart('medicine', {{ json_encode($medicine) }})">Pilih</button>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="section-title">Layanan Cek Kesehatan</div>
        <div class="section-subtitle">Pemeriksaan medis ringan oleh apoteker</div>

        <!-- Services Grid -->
        <div class="item-grid">
            @foreach($services as $service)
                <div class="item-card category-layanan">
                    <div class="item-badge badge-layanan">LAYANAN</div>
                    <div class="item-title" style="margin-bottom: 2rem;">{{ $service->nama }}</div>
                    
                    <div class="item-footer">
                        <div class="item-price">Rp {{ number_format($service->harga, 0, ',', '.') }}</div>
                        <button class="btn-pilih" onclick="addToCart('service', {{ json_encode($service) }})">Pilih</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Right Area: Cart -->
    <div class="cart-sidebar">
        <div class="cart-header">
            <div class="cart-title">
                <i data-lucide="shopping-cart" size="18"></i>
                Daftar Belanja
            </div>
            <button class="btn-kosongkan" onclick="clearCart()">KOSONGKAN</button>
        </div>

        <div class="cart-items" id="cartItemsContainer">
            <div id="emptyCart" style="text-align: center; color: var(--text-muted); padding-top: 4rem;">
                <i data-lucide="shopping-bag" size="48" style="opacity: 0.2; margin-bottom: 1rem;"></i>
                <p style="font-size: 13px;">Belum ada item dipilih</p>
            </div>
            <div id="cartItems"></div>
        </div>

        <div>
            <select class="resep-select" id="resepAddon" onchange="renderCart()">
                <option value="0">Tanpa Tambahan Resep</option>
                <option value="5000">Puyer (+ Rp 5.000)</option>
                <option value="10000">Salep Racikan (+ Rp 10.000)</option>
            </select>

            <div class="cart-summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotalLabel">Rp 0</span>
                </div>
                <div class="summary-row">
                    <span>PPN (11%)</span>
                    <span id="ppnLabel">Rp 0</span>
                </div>
                <div class="summary-row total">
                    <span>Total Akhir</span>
                    <span id="totalLabel">Rp 0</span>
                </div>
            </div>

            <div class="payment-methods">
                <div class="btn-payment active" onclick="setPayment('tunai', this)">
                    <i data-lucide="banknote" size="24"></i>
                    TUNAI
                </div>
                <div class="btn-payment" onclick="setPayment('qris', this)">
                    <i data-lucide="qr-code" size="24"></i>
                    QRIS / NON-TUNAI
                </div>
            </div>

            <button id="checkoutBtn" class="btn-checkout" onclick="checkout()" disabled>
                <i data-lucide="check-circle" size="20"></i>
                PROSES PEMBAYARAN
            </button>
        </div>
    </div>
</div>
</div>

@endsection

@push('scripts')
<script>
    let cart = [];
    try {
        const saved = localStorage.getItem('pos_cart');
        if (saved) {
            cart = JSON.parse(saved);
            localStorage.removeItem('pos_cart'); // Clear after loading
        }
    } catch(e) {
        console.error("Gagal load cart", e);
    }
    let paymentMethod = 'tunai';

    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true
    });

    function addToCart(type, item) {
        const id = parseInt(item.id);
        const existing = cart.find(i => i.id === id && i.type === type);
        
        if (existing) {
            if (type === 'medicine') {
                if (existing.quantity < item.total_stock) {
                    existing.quantity++;
                } else {
                    Toast.fire({ icon: 'warning', title: `Stok maksimal tercapai` });
                }
            } else {
                existing.quantity++;
            }
        } else {
            cart.push({
                id: id,
                type: type,
                nama: item.nama,
                harga: parseFloat(item.harga),
                quantity: 1,
                kode: item.kode || 'SRV-01',
                max: type === 'medicine' ? parseInt(item.total_stock) : 999
            });
        }
        renderCart();
    }

    function removeFromCart(id, type) {
        cart = cart.filter(item => !(item.id === id && item.type === type));
        renderCart();
    }

    function updateQty(id, type, delta) {
        const item = cart.find(i => i.id === id && i.type === type);
        if (item) {
            const newQty = item.quantity + delta;
            if (newQty <= 0) {
                removeFromCart(id, type);
            } else if (newQty > item.max) {
                Toast.fire({ icon: 'warning', title: `Stok maksimal tercapai` });
            } else {
                item.quantity = newQty;
            }
        }
        renderCart();
    }

    function clearCart() {
        cart = [];
        renderCart();
    }

    function setPayment(method, el) {
        paymentMethod = method;
        document.querySelectorAll('.btn-payment').forEach(btn => btn.classList.remove('active'));
        el.classList.add('active');
    }

    function renderCart() {
        const container = document.getElementById('cartItems');
        const empty = document.getElementById('emptyCart');
        const checkoutBtn = document.getElementById('checkoutBtn');
        const resepFee = parseInt(document.getElementById('resepAddon').value) || 0;
        
        if (cart.length === 0) {
            empty.style.display = 'block';
            container.innerHTML = '';
            checkoutBtn.disabled = true;
            document.getElementById('subtotalLabel').innerText = 'Rp 0';
            document.getElementById('ppnLabel').innerText = 'Rp 0';
            document.getElementById('totalLabel').innerText = 'Rp 0';
        } else {
            empty.style.display = 'none';
            container.innerHTML = cart.map(item => `
                <div class="cart-item">
                    <div class="cart-item-header">
                        <div class="cart-item-title">${item.nama}</div>
                        <button class="btn-remove" onclick="removeFromCart(${item.id}, '${item.type}')"><i data-lucide="x" size="14"></i></button>
                    </div>
                    <div class="cart-item-batch">BATCH: ${item.kode} (FIFO)</div>
                    <div class="cart-item-footer">
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateQty(${item.id}, '${item.type}', -1)">-</button>
                            <span class="qty-val">${item.quantity}</span>
                            <button class="qty-btn" onclick="updateQty(${item.id}, '${item.type}', 1)">+</button>
                        </div>
                        <div class="cart-item-price">Rp ${(item.harga * item.quantity).toLocaleString('id-ID')}</div>
                    </div>
                </div>
            `).join('');
            checkoutBtn.disabled = false;
            
            const subtotal = cart.reduce((acc, item) => acc + (item.harga * item.quantity), 0) + resepFee;
            const ppn = subtotal * 0.11;
            const totalAkhir = subtotal + ppn;

            document.getElementById('subtotalLabel').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
            document.getElementById('ppnLabel').innerText = 'Rp ' + ppn.toLocaleString('id-ID', {maximumFractionDigits: 0});
            document.getElementById('totalLabel').innerText = 'Rp ' + totalAkhir.toLocaleString('id-ID', {maximumFractionDigits: 0});

            if (window.lucide) lucide.createIcons();
        }
    }

    async function checkout() {
        if (cart.length === 0) return;
        
        const { isConfirmed } = await Swal.fire({
            title: 'Proses Pembayaran?',
            text: "Konfirmasi pembayaran menggunakan " + (paymentMethod === 'tunai' ? 'Tunai' : 'QRIS/Non-Tunai'),
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            confirmButtonText: 'Proses Bayar',
            cancelButtonText: 'Batal'
        });

        if (!isConfirmed) return;

        const btn = document.getElementById('checkoutBtn');
        btn.disabled = true;
        btn.innerHTML = '<i data-lucide="loader-2" class="spin" size="20"></i> Processing...';

        try {
            const response = await fetch("{{ route('pos.checkout') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    items: cart.map(i => ({ 
                        type: i.type,
                        id: i.id, 
                        quantity: i.quantity 
                    })),
                    payment_method: paymentMethod
                })
            });

            const result = await response.json();
            if (result.success) {
                await Swal.fire({
                    icon: 'success',
                    title: 'Transaksi Berhasil',
                    text: 'Invoice: ' + result.invoice,
                    confirmButtonColor: '#059669'
                });
                location.reload();
            } else {
                Swal.fire({ icon: 'error', title: 'Gagal', text: result.message });
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="check-circle" size="20"></i> PROSES PEMBAYARAN';
                if(window.lucide) lucide.createIcons();
            }
        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Opps!', text: 'Terjadi kesalahan sistem.' });
            btn.disabled = false;
            btn.innerHTML = '<i data-lucide="check-circle" size="20"></i> PROSES PEMBAYARAN';
            if(window.lucide) lucide.createIcons();
        }
    }

    // Initialize lucide on load
    document.addEventListener("DOMContentLoaded", function() {
        lucide.createIcons();
        renderCart();
    });
</script>
@endpush
