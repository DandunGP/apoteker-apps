@extends('layouts.app')

@section('title', 'Ubah Layanan')

@section('content')

<style>
    .service-form-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 2rem;
        max-width: 650px;
        margin: 0 auto;
    }

    .form-title {
        font-size: 18px;
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 1.5rem;
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
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #1E293B;
        outline: none;
        transition: all 0.2s;
    }

    .form-input:focus {
        border-color: #0F62FE;
        background: white;
        box-shadow: 0 0 0 3px rgba(15, 98, 254, 0.08);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 2rem;
        border-top: 1px solid #F1F5F9;
        padding-top: 1.25rem;
    }

    .btn-submit {
        background: #0056B3;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }

    .btn-submit:hover {
        background: #004494;
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
        text-align: center;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #F8FAFC;
        color: #1E293B;
    }
</style>

<div>
    <!-- Top Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
        <span>&gt;</span>
        <a href="{{ route('services.index') }}">Layanan Jasa</a>
        <span>&gt;</span>
        <span class="active">Ubah Layanan</span>
    </div>

    <!-- Service Form Card -->
    <div class="service-form-card">
        <div class="form-title">Ubah Data Layanan</div>

        <form action="{{ route('services.update', $service->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="nama">Nama Layanan</label>
                <input type="text" name="nama" id="nama" class="form-input" placeholder="Contoh: Cek Gula Darah" value="{{ old('nama', $service->nama) }}" required>
                @error('nama') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="harga">Harga Layanan (Rp)</label>
                <input type="number" name="harga" id="harga" class="form-input" placeholder="Contoh: 15000" value="{{ old('harga', $service->harga) }}" required>
                @error('harga') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('services.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endsection
