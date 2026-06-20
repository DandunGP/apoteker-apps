@extends('layouts.app')

@section('title', 'Semua Notifikasi')

@section('content')
<style>
    .notif-container {
        max-width: 900px;
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        font-family: 'Inter', sans-serif;
    }

    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.5rem;
    }

    .notif-title-area h1 {
        font-size: 24px;
        font-weight: 800;
        color: #1E293B;
        margin: 0;
    }

    .notif-title-area p {
        font-size: 13.5px;
        color: #64748B;
        margin: 4px 0 0 0;
        font-weight: 500;
    }

    .btn-mark-read {
        background: white;
        color: #0F62FE;
        border: 1.5px solid #0F62FE;
        border-radius: 6px;
        padding: 8px 18px;
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }

    .btn-mark-read:hover {
        background: #F0F4FC;
        transform: translateY(-1px);
    }

    .notif-card-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .notif-item-link {
        text-decoration: none;
        color: inherit;
    }

    .notif-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        transition: all 0.2s;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }

    .notif-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        transform: translateY(-1px);
    }

    .notif-card.unread {
        background: #F8FAFC;
        border-left: 4px solid #0F62FE;
    }

    .notif-content-wrapper {
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }

    .notif-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .notif-icon-box.expiry {
        background: #FEF2F2;
        color: #EF4444;
    }

    .notif-icon-box.info {
        background: #EFF6FF;
        color: #1D4ED8;
    }

    .notif-details {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .notif-title {
        font-size: 14.5px;
        font-weight: 700;
        color: #1E293B;
    }

    .notif-message {
        font-size: 13px;
        color: #475569;
        line-height: 1.5;
        margin-top: 2px;
    }

    .notif-time {
        font-size: 11px;
        color: #94A3B8;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
    }

    .unread-badge {
        font-size: 10px;
        background: #E0E8FF;
        color: #0F62FE;
        padding: 2px 8px;
        border-radius: 20px;
        font-weight: 700;
        align-self: center;
    }

    .pagination-wrapper {
        margin-top: 1rem;
        display: flex;
        justify-content: center;
    }

    /* Custom styles for pagination */
    .pagination {
        display: flex;
        gap: 6px;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .page-item .page-link {
        display: block;
        padding: 6px 12px;
        border: 1px solid #E2E8F0;
        border-radius: 6px;
        color: #475569;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        background: white;
        transition: all 0.2s;
    }

    .page-item.active .page-link {
        background: #0F62FE;
        color: white;
        border-color: #0F62FE;
    }

    .page-item.disabled .page-link {
        color: #94A3B8;
        pointer-events: none;
        background: #F8FAFC;
    }

    .page-item .page-link:hover:not(.active) {
        background: #F1F5F9;
        border-color: #CBD5E1;
    }
</style>

<div class="notif-container">
    <!-- Breadcrumb -->
    <div class="breadcrumb">
        <a href="{{ route('dashboard') }}">Home</a>
        <i data-lucide="chevron-right" size="12"></i>
        <span class="active">Notifikasi</span>
    </div>

    <!-- Header Section -->
    <div class="notif-header">
        <div class="notif-title-area">
            <h1>Notifikasi</h1>
            <p>Kelola semua notifikasi dan informasi terbaru dari sistem</p>
        </div>
        
        @if($notifications->whereNull('read_at')->count() > 0)
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="btn-mark-read">
                    <i data-lucide="check-check" size="16"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif
    </div>

    <!-- Notifications List -->
    <div class="notif-card-list">
        @forelse($notifications as $notif)
            <a href="{{ route('notifications.read', $notif->id) }}" class="notif-item-link">
                <div class="notif-card {{ is_null($notif->read_at) ? 'unread' : '' }}">
                    <div class="notif-content-wrapper">
                        @if($notif->type === 'expiry')
                            <div class="notif-icon-box expiry">
                                <i data-lucide="alert-triangle" size="18"></i>
                            </div>
                        @else
                            <div class="notif-icon-box info">
                                <i data-lucide="bell" size="18"></i>
                            </div>
                        @endif
                        <div class="notif-details">
                            <div class="notif-title">{{ $notif->title }}</div>
                            <div class="notif-message">{{ $notif->message }}</div>
                            <div class="notif-time">
                                <i data-lucide="clock" size="11"></i>
                                {{ $notif->created_at->diffForHumans() }}
                            </div>
                        </div>
                    </div>
                    
                    @if(is_null($notif->read_at))
                        <span class="unread-badge">Baru</span>
                    @endif
                </div>
            </a>
        @empty
            <div style="background: white; border: 1px solid #E2E8F0; border-radius: 8px; padding: 50px 20px; text-align: center; color: #64748B; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                <i data-lucide="check-circle" size="48" style="color: #10B981; display: block; margin: 0 auto 16px;"></i>
                <h3 style="margin: 0; font-size: 16px; font-weight: 700; color: #1E293B;">Tidak ada notifikasi</h3>
                <p style="margin: 6px 0 0 0; font-size: 13.5px; color: #64748B;">Semua pemberitahuan penting akan ditampilkan di sini.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($notifications->hasPages())
        <div class="pagination-wrapper">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
