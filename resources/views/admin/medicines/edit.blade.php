@extends('layouts.app')

@section('title', 'Edit Data Obat')

@section('content')

<style>
    .breadcrumb {
        font-size: 11px;
        font-weight: 700;
        color: #64748B;
        display: flex;
        gap: 4px;
        margin: 0 0 1rem 0;
    }

    .breadcrumb a {
        color: #64748B;
        text-decoration: none;
    }

    .breadcrumb span.active {
        color: #0F62FE;
    }

    .medicine-form-card {
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

    .form-select {
        background: #F8FAFC;
        border: 1px solid #CBD5E1;
        border-radius: 4px;
        padding: 10px 14px;
        font-size: 13px;
        font-weight: 600;
        color: #1E293B;
        outline: none;
        cursor: pointer;
    }

    .form-select:focus {
        border-color: #0F62FE;
    }

    .form-row-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 1.25rem;
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
        <a href="{{ route('medicines.index') }}">Kelola Data Obat</a>
        <span>&gt;</span>
        <span class="active">Edit Obat</span>
    </div>

    <!-- Medicine Form Card -->
    <div class="medicine-form-card">
        <div class="form-title">Edit Data Obat</div>

        <form action="{{ route('medicines.update', $medicine->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="kode">Kode Obat</label>
                <input type="text" name="kode" id="kode" class="form-input" placeholder="Contoh: PCT001" required value="{{ old('kode', $medicine->kode) }}">
                @error('kode') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="nama">Nama Obat</label>
                <input type="text" name="nama" id="nama" class="form-input" placeholder="Masukkan nama lengkap obat" required value="{{ old('nama', $medicine->nama) }}">
                @error('nama') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="kategori">Kategori Obat</label>
                <select name="kategori" id="kategori" class="form-select" required>
                    <option value="">Pilih Kategori Obat</option>
                    <option value="Obat Bebas" {{ old('kategori', $medicine->kategori) == 'Obat Bebas' ? 'selected' : '' }}>Obat Bebas</option>
                    <option value="Obat Terbatas" {{ old('kategori', $medicine->kategori) == 'Obat Terbatas' ? 'selected' : '' }}>Obat Terbatas</option>
                    <option value="Obat Keras" {{ old('kategori', $medicine->kategori) == 'Obat Keras' ? 'selected' : '' }}>Obat Keras</option>
                    <option value="Suplemen" {{ old('kategori', $medicine->kategori) == 'Suplemen' ? 'selected' : '' }}>Suplemen</option>
                    <option value="Alat Kesehatan" {{ old('kategori', $medicine->kategori) == 'Alat Kesehatan' ? 'selected' : '' }}>Alat Kesehatan</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="satuan">Satuan Obat</label>
                <select name="satuan" id="satuan" class="form-select" required>
                    <option value="">Pilih Satuan</option>
                    <option value="Strip" {{ old('satuan', $medicine->satuan) == 'Strip' ? 'selected' : '' }}>Strip</option>
                    <option value="Botol" {{ old('satuan', $medicine->satuan) == 'Botol' ? 'selected' : '' }}>Botol</option>
                    <option value="Box" {{ old('satuan', $medicine->satuan) == 'Box' ? 'selected' : '' }}>Box</option>
                </select>
                @error('satuan') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-row-grid">
                <div class="form-group">
                    <label class="form-label" for="harga">Harga (Rp)</label>
                    <input type="number" name="harga" id="harga" class="form-input" placeholder="0" required value="{{ old('harga', $medicine->harga) }}">
                    @error('harga') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="min_stok">Min. Stok</label>
                    <input type="number" name="min_stok" id="min_stok" class="form-input" placeholder="10" required value="{{ old('min_stok', $medicine->min_stok) }}">
                    @error('min_stok') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-actions">
                <a href="{{ route('medicines.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Perbarui Data Obat</button>
            </div>
        </form>
    </div>
</div>

@endsection
