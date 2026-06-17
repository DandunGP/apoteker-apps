@extends('layouts.app')

@section('title', 'Edit Layanan')

@section('content')
<!-- Top Breadcrumb -->
<div class="breadcrumb">
    <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
    <span>&gt;</span>
    <a href="{{ route('services.index') }}">Layanan Jasa</a>
    <span>&gt;</span>
    <span class="active">Edit Layanan</span>
</div>

<div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; padding: 2rem 0;">
    
    <div style="width: 100%; max-width: 550px; margin-bottom: 2rem;">
        <a href="{{ route('services.index') }}" class="btn-back">
            <i data-lucide="arrow-left" size="18"></i>
            Kembali ke Daftar Layanan
        </a>
        <h1 style="font-size: 28px; font-weight: 800; margin-top: 1rem; color: var(--text-dark);">Edit Layanan</h1>
        <p style="color: var(--text-muted); font-size: 14px; margin-top: 0.5rem;">Perbarui informasi layanan jasa atau pemeriksaan kesehatan.</p>
    </div>

    <div class="rounded-card" style="width: 100%; max-width: 550px; padding: 2.5rem;">
        <form action="{{ route('services.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="form-group">
                <label class="form-label">NAMA LAYANAN</label>
                <div style="position: relative;">
                    <i data-lucide="stethoscope" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);" size="18"></i>
                    <input type="text" name="nama" class="form-input" style="padding-left: 3rem;" placeholder="Contoh: Cek Gula Darah" value="{{ old('nama', $service->nama) }}" required>
                </div>
                @error('nama') <p style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            <div class="form-group">
                <label class="form-label">HARGA LAYANAN (RP)</label>
                <div style="position: relative;">
                    <i data-lucide="banknote" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: var(--text-muted);" size="18"></i>
                    <input type="number" name="harga" class="form-input" style="padding-left: 3rem;" placeholder="15000" value="{{ old('harga', $service->harga) }}" required>
                </div>
                @error('harga') <p style="color: #EF4444; font-size: 12px; margin-top: 4px;">{{ $message }}</p> @enderror
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 1rem; border-radius: 16px; font-size: 16px; font-weight: 700; gap: 10px; margin-top: 1rem;">
                <i data-lucide="save" size="20"></i>
                Update Layanan
            </button>
        </form>
    </div>
</div>
@endsection
