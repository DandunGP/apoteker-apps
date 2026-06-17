@extends('layouts.app')

@section('title', 'Penyesuaian Stok')

@section('content')
<!-- Top Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
    <span>&gt;</span>
    <a href="{{ route('adjustments.index') }}">Stock Opname</a>
    <span>&gt;</span>
    <span class="active">Penyesuaian Stok</span>
</div>

<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; padding: 2rem 0;">
    
    <div style="width: 100%; max-width: 500px; margin-bottom: 2rem;">
        <a href="{{ route('adjustments.index') }}" style="text-decoration: none; color: var(--text-muted); font-size: 14px; font-weight: 500; display: flex; align-items: center; gap: 8px;">
            <i data-lucide="arrow-left" size="18"></i>
            Kembali
        </a>
        <h1 style="font-size: 28px; font-weight: 800; margin-top: 1rem; color: var(--text-dark);">Penyesuaian Stok</h1>
        <p style="color: var(--text-muted); font-size: 14px; margin-top: 0.5rem;">Batch: <strong>{{ $batch->no_batch }}</strong> | {{ $batch->medicine->nama }}</p>
    </div>

    <div class="rounded-card" style="width: 100%; max-width: 500px; padding: 2.5rem; background: white; border: 1px solid #F1F5F9; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);">
        <form action="{{ route('adjustments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="medicine_batch_id" value="{{ $batch->id }}">
            
            <div style="background: #F8FAFC; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; display: flex; justify-content: space-between;">
                <div>
                    <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Stok Sistem</div>
                    <div style="font-size: 20px; font-weight: 800; color: var(--primary);">{{ $batch->stok_sisa }}</div>
                </div>
                <div style="text-align: right;">
                    <div style="font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 700;">Satuan</div>
                    <div style="font-size: 14px; font-weight: 600; margin-top: 4px;">Unit/Tablet</div>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: var(--text-dark); text-transform: uppercase; letter-spacing: 0.5px;">Stok Fisik (Hasil Opname)</label>
                <div style="position: relative;">
                    <i data-lucide="package-search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);" size="18"></i>
                    <input type="number" name="new_stock" 
                           style="width: 100%; padding: 0.85rem 1rem 0.85rem 3rem; border-radius: 14px; border: 1.5px solid #E2E8F0; outline: none; font-size: 16px; transition: all 0.2s;" 
                           placeholder="Masukkan hasil perhitungan fisik" required
                           onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px var(--primary-light)'"
                           onblur="this.style.borderColor='#E2E8F0'; this.style.boxShadow='none'">
                </div>
            </div>

            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-size: 13px; font-weight: 700; margin-bottom: 8px; color: var(--text-dark); text-transform: uppercase; letter-spacing: 0.5px;">Alasan Perubahan</label>
                <textarea name="reason" rows="3" 
                          style="width: 100%; padding: 0.85rem 1rem; border-radius: 14px; border: 1.5px solid #E2E8F0; outline: none; font-family: inherit; font-size: 15px; resize: none; transition: all 0.2s;" 
                          placeholder="Misal: Barang rusak, Kesalahan input, atau Kehilangan stok..." required
                          onfocus="this.style.borderColor='var(--primary)';"
                          onblur="this.style.borderColor='#E2E8F0';"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem; border-radius: 16px; font-size: 16px; font-weight: 700; gap: 10px; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);">
                <i data-lucide="check-square" size="20"></i>
                Konfirmasi Penyesuaian
            </button>
        </form>
    </div>
</div>
@endsection
