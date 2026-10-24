
@extends('layouts.app')

@section('content')
<style>
    .user-form-page {
        padding: 32px;
        background: #f4f6fa;
        min-height: 100%;
        color: #102544;
    }

    .user-form-heading {
        margin-bottom: 28px;
    }

    .user-form-heading h2 {
        font-size: 30px;
        font-weight: 700;
        margin: 0 0 8px;
        color: #102544;
    }

    .user-form-heading p {
        color: #8290a5;
        margin: 0;
        font-size: 14px;
    }

    .user-form-card {
        width: 100%;
        max-width: 1250px;
        background: #fff;
        border: 1px solid #dfe6f0;
        border-radius: 16px;
        padding: 32px;
        box-sizing: border-box;
    }

    .user-form-description {
        color: #8290a5;
        font-size: 14px;
        margin: 0 0 28px;
    }

    .user-form-group {
        margin-bottom: 24px;
    }

    .user-form-group label {
        display: block;
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #102544;
    }

    .user-form-control {
        display: block;
        width: 100%;
        height: 54px;
        padding: 12px 16px;
        box-sizing: border-box;
        border: 1px solid #d8e1ef;
        border-radius: 9px;
        background: #fff;
        color: #243b5a;
        font-size: 15px;
        font-family: inherit;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .user-form-control:focus {
        border-color: #2864f0;
        box-shadow: 0 0 0 3px rgba(40, 100, 240, .12);
    }

    .user-form-help {
        display: block;
        margin-top: 8px;
        font-size: 13px;
        color: #8290a5;
    }

    .user-form-divider {
        border: 0;
        border-top: 1px solid #e1e7f0;
        margin: 30px 0 22px;
    }

    .user-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .user-form-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 48px;
        padding: 0 22px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    .user-form-btn-cancel {
        color: #344761;
        background: #fff;
        border: 1px solid #d8e1ef;
    }

    .user-form-btn-save {
        color: #fff;
        background: #2864f0;
        border: 1px solid #2864f0;
    }

    .user-form-btn-save:hover {
        background: #1d50cf;
    }

    .user-form-error {
        max-width: 1250px;
        padding: 16px 20px;
        margin-bottom: 20px;
        border-radius: 10px;
        background: #fff0f0;
        border: 1px solid #f3caca;
        color: #a52222;
    }

    @media (max-width: 768px) {
        .user-form-page {
            padding: 20px 14px;
        }

        .user-form-card {
            padding: 22px 18px;
        }

        .user-form-heading h2 {
            font-size: 25px;
        }
    }
</style>

<div class="user-form-page">

    <div class="user-form-heading">
        <h2>{{ $row->exists ? 'Edit Pengguna' : 'Tambah Pengguna' }}</h2>
        <p>Kelola informasi dan hak akses pengguna Adara Snack.</p>
    </div>

    @if ($errors->any())
        <div class="user-form-error">
            <strong>Periksa kembali data yang dimasukkan.</strong>
            <ul style="margin: 10px 0 0; padding-left: 22px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="user-form-card">

        <p class="user-form-description">
            Lengkapi informasi berikut dengan benar.
        </p>

        <form
            action="{{ $row->exists
                ? route('users.update', $row)
                : route('users.store') }}"
            method="POST">

            @csrf

            @if ($row->exists)
                @method('PUT')
            @endif

            <div class="user-form-group">
                <label for="name">Nama Lengkap <span style="color:#e5484d">*</span></label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="user-form-control"
                    placeholder="Masukkan nama lengkap"
                    value="{{ old('name', $row->name) }}"
                    maxlength="150"
                    required>
            </div>

            <div class="user-form-group">
                <label for="email">Email <span style="color:#e5484d">*</span></label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="user-form-control"
                    placeholder="contoh@email.com"
                    value="{{ old('email', $row->email) }}"
                    required>
            </div>

            <div class="user-form-group">
                <label for="password">
                    Password <span style="color:#e5484d">{{ $row->exists ? '' : '*' }}</span>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="user-form-control"
                    placeholder="{{ $row->exists ? 'Kosongkan jika tidak diubah' : 'Masukkan password minimal 8 karakter' }}"
                    minlength="8"
                    {{ $row->exists ? '' : 'required' }}
                    autocomplete="new-password">

                <span class="user-form-help">
                    {{ $row->exists
                        ? 'Kosongkan jika tidak ingin mengganti password.'
                        : 'Password harus terdiri dari minimal 8 karakter.' }}
                </span>
            </div>

            <div class="user-form-group">
                <label for="role">Role Pengguna <span style="color:#e5484d">*</span></label>
                <select
                    id="role"
                    name="role"
                    class="user-form-control"
                    required>

                    <option value="">-- Pilih Role Pengguna --</option>

                    <option value="admin"
                        {{ old('role', $row->role) === 'admin' ? 'selected' : '' }}>
                        Admin
                    </option>

                    <option value="staff"
                        {{ old('role', $row->role) === 'staff' ? 'selected' : '' }}>
                        Staff
                    </option>

                    <option value="user"
                        {{ old('role', $row->role) === 'user' ? 'selected' : '' }}>
                        User
                    </option>
                </select>
            </div>

            <hr class="user-form-divider">

            <div class="user-form-actions">
                <a href="{{ route('users.index') }}"
                   class="user-form-btn user-form-btn-cancel">
                    Batal
                </a>

                <button type="submit"
                        class="user-form-btn user-form-btn-save">
                    {{ $row->exists ? 'Simpan Perubahan' : 'Simpan Pengguna' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection
