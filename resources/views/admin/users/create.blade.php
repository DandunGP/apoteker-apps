@extends('layouts.app')

@section('title', 'Tambah User Baru')

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

    /* Centered Form Wrapper Layout */
    .form-wrapper {
        display: flex;
        justify-content: center;
        padding: 1.5rem 0;
    }

    .form-container-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 2.5rem;
        width: 100%;
        max-width: 640px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .form-title {
        font-size: 20px;
        font-weight: 800;
        color: #1E293B;
        margin: 0 0 1.5rem 0;
        padding-bottom: 1.25rem;
        border-bottom: 1px solid #E2E8F0;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-size: 11px;
        font-weight: 800;
        color: #64748B;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 0.5px;
    }

    .form-input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 600;
        color: #1E293B;
        outline: none;
        box-sizing: border-box;
        transition: all 0.2s;
        background: #F8FAFC;
    }

    .form-input:focus {
        border-color: #0F62FE;
        background: white;
        box-shadow: 0 0 0 3px rgba(15, 98, 254, 0.08);
    }

    .form-select {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        font-size: 14px;
        font-weight: 700;
        color: #475569;
        outline: none;
        box-sizing: border-box;
        cursor: pointer;
        background: #F8FAFC;
        height: 46px;
        appearance: none;
        background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
    }

    .form-select:focus {
        border-color: #0F62FE;
        background-color: white;
    }

    .error-feedback {
        color: #EF4444;
        font-size: 11px;
        font-weight: 700;
        margin-top: 5px;
        display: block;
    }

    .btn-actions-row {
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        margin-top: 2.25rem;
        padding-top: 1.5rem;
        border-top: 1px solid #E2E8F0;
    }

    .btn-submit-primary {
        background: #0056B3;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 11px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }

    .btn-submit-primary:hover {
        background: #004494;
    }

    .btn-cancel-outline {
        background: white;
        color: #475569;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        padding: 11px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .btn-cancel-outline:hover {
        background: #F8FAFC;
        border-color: #CBD5E1;
        color: #1E293B;
    }
</style>

<div>
    <!-- Top Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">{{ str_replace('_', ' ', auth()->user()->role) }}</a>
        <span>&gt;</span>
        <a href="{{ route('users.index') }}">Kelola User</a>
        <span>&gt;</span>
        <span class="active">Tambah User</span>
    </div>

    <!-- Centered Form Card -->
    <div class="form-wrapper">
        <div class="form-container-card">
            <h2 class="form-title">Tambah User Baru</h2>

            <form action="{{ route('users.store') }}" method="POST">
                @csrf

                <!-- Name Input -->
                <div class="form-group">
                    <label for="name" class="form-label">Nama User</label>
                    <input type="text" name="name" id="name" class="form-input" placeholder="Masukkan nama user" value="{{ old('name') }}" required autocomplete="off">
                    @error('name')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Email Input -->
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input type="email" name="email" id="email" class="form-input" placeholder="Masukkan alamat email" value="{{ old('email') }}" required autocomplete="off">
                    @error('email')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Masukkan password (min. 6 karakter)" required autocomplete="new-password">
                    @error('password')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Confirmation Input -->
                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-input" placeholder="Masukkan kembali password" required autocomplete="new-password">
                    @error('password_confirmation')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Role Selection -->
                <div class="form-group">
                    <label for="role" class="form-label">Role / Hak Akses</label>
                    <select name="role" id="role" class="form-select" required>
                        <option value="" disabled {{ old('role') === null ? 'selected' : '' }}>Pilih role akses...</option>
                        <option value="admin_gudang" {{ old('role') === 'admin_gudang' ? 'selected' : '' }}>Admin Gudang</option>
                        <option value="apoteker" {{ old('role') === 'apoteker' ? 'selected' : '' }}>Apoteker</option>
                        <option value="kasir" {{ old('role') === 'kasir' ? 'selected' : '' }}>Kasir / Sales</option>
                    </select>
                    @error('role')
                        <span class="error-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Action Buttons aligned to the right -->
                <div class="btn-actions-row">
                    <a href="{{ route('users.index') }}" class="btn-cancel-outline">
                        Batal
                    </a>
                    <button type="submit" class="btn-submit-primary">
                        Simpan User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
