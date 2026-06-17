@extends('layouts.app')

@section('title', 'Ubah Supplier')

@section('content')

<style>
    .supplier-form-card {
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

    .form-textarea {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
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
        <a href="{{ route('suppliers.index') }}">Kelola Supplier</a>
        <span>&gt;</span>
        <span class="active">Ubah Supplier</span>
    </div>

    <div class="supplier-form-card">
        <div class="form-title">Ubah Data Supplier</div>

        <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="nama">Nama Supplier</label>
                <input type="text" name="nama" id="nama" class="form-input" required value="{{ old('nama', $supplier->nama) }}">
                @error('nama') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="kontak_person">Kontak Person</label>
                <input type="text" name="kontak_person" id="kontak_person" class="form-input" required value="{{ old('kontak_person', $supplier->kontak_person) }}">
                @error('kontak_person') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="no_telepon">No. Telepon</label>
                <input type="text" name="no_telepon" id="no_telepon" class="form-input" required value="{{ old('no_telepon', $supplier->no_telepon) }}">
                @error('no_telepon') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="alamat">Alamat Lengkap</label>
                <textarea name="alamat" id="alamat" class="form-input form-textarea" required>{{ old('alamat', $supplier->alamat) }}</textarea>
                @error('alamat') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="status">Status Pemasok</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="Aktif" {{ old('status', $supplier->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="Non-Aktif" {{ old('status', $supplier->status) == 'Non-Aktif' ? 'selected' : '' }}>Non-Aktif</option>
                </select>
                @error('status') <span style="color: #EF4444; font-size: 11px; font-weight: 600;">{{ $message }}</span> @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('suppliers.index') }}" class="btn-cancel">Batal</a>
                <button type="submit" class="btn-submit">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@endsection
