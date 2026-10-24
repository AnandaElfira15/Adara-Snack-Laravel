<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Dashboard') - Adara Snack</title>

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

@php
    $role = auth()->user()->role;
    $isAdmin = $role === 'admin';
    $isStaff = $role === 'staff';
    $isOperational = in_array($role, ['admin', 'staff'], true);
@endphp

<div class="app-shell">

    {{-- SIDEBAR --}}
    <aside class="sidebar">

        {{-- BRAND --}}
        <a href="{{ route('dashboard') }}" class="brand">
            <span class="brand-mark">A</span>

            <span class="brand-text">
                <strong>Adara Snack</strong>
                <small>Sistem Persediaan & Penjualan</small>
            </span>
        </a>

        {{-- INFORMASI PENGGUNA --}}
        <div class="user-chip">
            <span class="avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </span>

            <span class="user-info">
                <strong>{{ auth()->user()->name }}</strong>
                <small>{{ ucfirst($role) }}</small>
            </span>
        </div>

        {{-- MENU NAVIGASI --}}
        <nav class="sidebar-nav">

            <div class="nav-label">MENU UTAMA</div>

            {{-- Dashboard --}}
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <span class="nav-icon">▦</span>
                <span>Dashboard</span>
            </a>

            {{-- Produk: semua role dapat melihat --}}
            <a href="{{ route('products.index') }}"
               class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                <span class="nav-icon">▣</span>
                <span>Produk</span>
            </a>

            {{-- Kategori: hanya admin --}}
            @if($isAdmin)
                <a href="{{ route('categories.index') }}"
                   class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <span class="nav-icon">◈</span>
                    <span>Kategori</span>
                </a>

                {{-- Supplier: hanya admin --}}
                <a href="{{ route('suppliers.index') }}"
                   class="nav-link {{ request()->routeIs('suppliers.*') ? 'active' : '' }}">
                    <span class="nav-icon">♧</span>
                    <span>Supplier</span>
                </a>
            @endif

            {{-- TRANSAKSI: admin dan staff --}}
            @if($isOperational)

                <div class="nav-label">TRANSAKSI</div>

                <a href="{{ route('purchases.index') }}"
                   class="nav-link {{ request()->routeIs('purchases.*') ? 'active' : '' }}">
                    <span class="nav-icon">↓</span>
                    <span>Pembelian / Stock-in</span>
                </a>

                <a href="{{ route('sales.index') }}"
                   class="nav-link {{ request()->routeIs('sales.*') ? 'active' : '' }}">
                    <span class="nav-icon">↑</span>
                    <span>Penjualan</span>
                </a>

                <a href="{{ route('reports.index') }}"
                   class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <span class="nav-icon">▤</span>
                    <span>Laporan</span>
                </a>

            @endif

            {{-- ADMINISTRASI: hanya admin --}}
            @if($isAdmin)

                <div class="nav-label">ADMINISTRASI</div>

                <a href="{{ route('users.index') }}"
                   class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <span class="nav-icon">♙</span>
                    <span>Pengguna</span>
                </a>

            @endif

            {{-- PROFIL: semua role --}}
            <div class="nav-label">AKUN</div>

            <a href="{{ route('profile') }}"
               class="nav-link {{ request()->routeIs('profile') ? 'active' : '' }}">
                <span class="nav-icon">◎</span>
                <span>Profil Saya</span>
            </a>

        </nav>

        {{-- LOGOUT --}}
        <form action="{{ route('logout') }}"
              method="POST"
              class="logout-form">
            @csrf

            <button type="submit" class="logout-button">
                <span>↪</span>
                <span>Keluar</span>
            </button>
        </form>

    </aside>

    {{-- KONTEN UTAMA --}}
    <main class="main">

        {{-- HEADER --}}
        <header class="topbar">

            <div class="topbar-heading">
                <div class="eyebrow">
                    SISTEM PERSEDIAAN & PENJUALAN
                </div>

                <h1>@yield('heading', 'Dashboard')</h1>
            </div>

            <div class="top-date">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>

        </header>

        {{-- ISI HALAMAN --}}
        <section class="content">

            {{-- Pesan berhasil --}}
            @if(session('success'))
                <div class="alert success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Pesan kesalahan --}}
            @if(session('error'))
                <div class="alert danger">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Pesan validasi --}}
            @if($errors->any())
                <div class="alert danger">
                    <strong>Periksa kembali input berikut:</strong>

                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')

        </section>

        {{-- FOOTER --}}
        <footer class="app-footer">
            Adara Snack · UTS Web Programming 2
        </footer>

    </main>

</div>

</body>
</html>
