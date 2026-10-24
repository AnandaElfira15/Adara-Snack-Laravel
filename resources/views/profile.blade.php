@extends('layouts.app')

@section('title', 'Profil')
@section('heading', 'Profil Saya')

@section('content')
<style>
    .profile-wrapper {
        max-width: 850px;
        margin: 0 auto;
    }

    .profile-card {
        background: #ffffff;
        border: 1px solid #dce3ec;
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 4px 15px rgba(11, 31, 58, 0.06);
    }

    .profile-header {
        display: flex;
        align-items: center;
        gap: 18px;
        padding-bottom: 25px;
        margin-bottom: 25px;
        border-bottom: 1px solid #e5eaf1;
    }

    .profile-avatar {
        width: 72px;
        height: 72px;
        min-width: 72px;
        border-radius: 50%;
        background: #0b1f3a;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 30px;
        font-weight: 700;
    }

    .profile-name {
        margin: 0 0 6px;
        color: #0b1f3a;
        font-size: 23px;
        font-weight: 700;
    }

    .profile-description {
        margin: 0;
        color: #64748b;
        font-size: 14px;
    }

    .profile-badge {
        margin-left: auto;
        padding: 7px 14px;
        border-radius: 20px;
        background: #e8edf5;
        color: #0b1f3a;
        font-size: 12px;
        font-weight: 700;
    }

    .profile-info {
        margin-bottom: 20px;
    }

    .profile-info:last-child {
        margin-bottom: 0;
    }

    .profile-label {
        margin-bottom: 8px;
        color: #475569;
        font-size: 13px;
        font-weight: 600;
    }

    .profile-value {
        display: flex;
        align-items: center;
        min-height: 48px;
        padding: 12px 16px;
        border: 1px solid #dce3ec;
        border-radius: 9px;
        background: #f8fafc;
        color: #1e293b;
        font-size: 14px;
        overflow-wrap: anywhere;
    }

    .profile-role {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #0b1f3a;
        font-weight: 700;
    }

    .profile-role-dot {
        width: 9px;
        height: 9px;
        border-radius: 50%;
        background: #0b1f3a;
    }

    @media (max-width: 600px) {
        .profile-card {
            padding: 20px;
        }

        .profile-avatar {
            width: 56px;
            height: 56px;
            min-width: 56px;
            font-size: 24px;
        }

        .profile-name {
            font-size: 18px;
        }

        .profile-badge {
            display: none;
        }
    }
</style>

<div class="profile-wrapper">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div>
                <h2 class="profile-name">
                    {{ auth()->user()->name }}
                </h2>

                <p class="profile-description">
                    Informasi akun Adara Snack
                </p>
            </div>

            <div class="profile-badge">
                {{ ucfirst(auth()->user()->role) }}
            </div>
        </div>

        <div class="profile-info">
            <div class="profile-label">Nama Lengkap</div>
            <div class="profile-value">
                {{ auth()->user()->name }}
            </div>
        </div>

        <div class="profile-info">
            <div class="profile-label">Email</div>
            <div class="profile-value">
                {{ auth()->user()->email }}
            </div>
        </div>

        <div class="profile-info">
            <div class="profile-label">Role Akun</div>
            <div class="profile-value">
                <span class="profile-role">
                    <span class="profile-role-dot"></span>
                    {{ ucfirst(auth()->user()->role) }}
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
