@extends('layouts.app')

@section('title', 'Dashboard')
@section('heading', 'Dashboard')

@section('content')

<style>
    .dashboard-summary-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 12px;
        margin-bottom: 20px;
    }

    .dashboard-summary-grid .stat-card {
        min-width: 0;
        padding: 16px;
    }

    .dashboard-summary-grid .stat-top {
        display: flex;
        gap: 8px;
        align-items: center;
        justify-content: space-between;
    }

    .dashboard-summary-grid .stat-top > span:first-child {
        font-size: 12px;
        line-height: 1.5;
    }

    .dashboard-summary-grid .stat-icon {
        flex-shrink: 0;
    }

    .dashboard-summary-grid .stat-card > strong {
        display: block;
        font-size: clamp(15px, 1.4vw, 22px);
        line-height: 1.5;
        overflow-wrap: anywhere;
        margin-top: 10px;
    }

    .dashboard-summary-grid .stat-card > small {
        display: block;
        font-size: 11px;
        margin-top: 4px;
    }

    .dashboard-stock-panel {
        width: 100%;
        min-width: 0;
        margin-bottom: 20px;
    }

    .dashboard-stock-panel .table-wrap {
        width: 100%;
        overflow-x: auto;
    }

    @media (max-width: 1100px) {
        .dashboard-summary-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }
    }

    @media (max-width: 650px) {
        .dashboard-summary-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 380px) {
        .dashboard-summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@php
    $role = auth()->user()->role;
    $isUserOnly = $role === 'user';
@endphp

{{-- SAPAAN DASHBOARD --}}
<div class="welcome">
    <div>
        <span class="pill">RINGKASAN BISNIS</span>
        <h2>Halo, {{ auth()->user()->name }} 👋</h2>
        <p>
            @if($isUserOnly)
                Selamat datang di Adara Snack. Silakan lihat produk yang tersedia.
            @else
                Pantau stok dan aktivitas toko dari satu tempat.
            @endif
        </p>
    </div>
    <div class="welcome-icon">▦</div>
</div>

{{-- KARTU RINGKASAN --}}
<div class="dashboard-summary-grid">

    {{-- Penjualan dan pembelian hanya untuk admin dan staff --}}
    @if(!$isUserOnly)

        <div class="stat-card">
            <div class="stat-top">
                <span>Penjualan Bulan Ini</span>
                <span class="stat-icon green">↗</span>
            </div>
            <strong>
                Rp {{ number_format($monthSales, 0, ',', '.') }}
            </strong>
            <small>Total transaksi selesai</small>
        </div>

        <div class="stat-card">
            <div class="stat-top">
                <span>Pembelian Bulan Ini</span>
                <span class="stat-icon blue">↙</span>
            </div>
            <strong>
                Rp {{ number_format($monthPurchases, 0, ',', '.') }}
            </strong>
            <small>Total transaksi selesai</small>
        </div>

    @endif

    {{-- Kartu yang tetap ditampilkan --}}
    <div class="stat-card">
        <div class="stat-top">
            <span>Total Produk</span>
            <span class="stat-icon blue">▣</span>
        </div>
        <strong>{{ number_format($productCount) }}</strong>
        <small>Produk terdaftar</small>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Kategori</span>
            <span class="stat-icon purple">◈</span>
        </div>
        <strong>{{ number_format($categoryCount) }}</strong>
        <small>Kategori produk</small>
    </div>

    <div class="stat-card">
        <div class="stat-top">
            <span>Supplier</span>
            <span class="stat-icon orange">♧</span>
        </div>
        <strong>{{ number_format($supplierCount) }}</strong>
        <small>Pemasok terdaftar</small>
    </div>

</div>

{{-- PRODUK STOK RENDAH --}}
{{-- Hanya ditampilkan untuk admin dan staff --}}
@if(!$isUserOnly)

    <div class="panel dashboard-stock-panel">
        <div class="panel-head">
            <div>
                <h3>Produk Stok Rendah</h3>
                <p>
                    Produk yang stoknya sama dengan atau di bawah batas minimum.
                </p>
            </div>
            <a class="text-link" href="{{ route('products.index') }}">
                Lihat produk →
            </a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="center">Stok</th>
                        <th class="center">Minimum Stok</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lowStock as $p)
                        <tr>
                            <td class="code">{{ $p->sku }}</td>
                            <td class="strong">{{ $p->name }}</td>
                            <td>{{ $p->category->name ?? '-' }}</td>
                            <td class="center">{{ $p->stock }}</td>
                            <td class="center">{{ $p->minimum_stock }}</td>
                            <td>
                                <span class="status warning">Stok rendah</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                Semua stok berada di atas batas minimum.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif

{{-- PENJUALAN TERBARU: ADMIN DAN STAFF --}}
@if(!$isUserOnly)

    <div class="panel mt">
        <div class="panel-head">
            <div>
                <h3>Penjualan Terbaru</h3>
                <p>Transaksi penjualan yang terakhir dicatat.</p>
            </div>
            <a class="text-link" href="{{ route('sales.index') }}">
                Semua penjualan →
            </a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Waktu</th>
                        <th>Petugas</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentSales as $s)
                        <tr>
                            <td class="code">{{ $s->sale_number }}</td>
                            <td>
                                {{ $s->sold_at
                                    ? $s->sold_at->format('d/m/Y H:i')
                                    : '-' }}
                            </td>
                            <td>{{ $s->user->name ?? '-' }}</td>
                            <td class="strong">
                                Rp {{ number_format($s->total, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="status {{ $s->status === 'completed' ? 'good' : 'muted-status' }}">
                                    {{ $s->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                                </span>
                            </td>
                            <td>
                                <a class="text-link" href="{{ route('sales.show', $s) }}">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                Belum ada transaksi penjualan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif

{{-- PEMBELIAN TERBARU: ADMIN DAN STAFF --}}
@if(!$isUserOnly)

    <div class="panel mt">
        <div class="panel-head">
            <div>
                <h3>Pembelian Terbaru</h3>
                <p>Transaksi pembelian barang dari supplier.</p>
            </div>
            <a class="text-link" href="{{ route('purchases.index') }}">
                Semua pembelian →
            </a>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>No. Transaksi</th>
                        <th>Waktu</th>
                        <th>Supplier</th>
                        <th>Petugas</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPurchases as $p)
                        <tr>
                            <td class="code">{{ $p->purchase_number }}</td>
                            <td>
                                {{ $p->purchased_at
                                    ? $p->purchased_at->format('d/m/Y H:i')
                                    : '-' }}
                            </td>
                            <td>{{ $p->supplier->name ?? '-' }}</td>
                            <td>{{ $p->user->name ?? '-' }}</td>
                            <td class="strong">
                                Rp {{ number_format($p->total, 0, ',', '.') }}
                            </td>
                            <td>
                                <span class="status {{ $p->status === 'completed' ? 'good' : 'muted-status' }}">
                                    {{ $p->status === 'completed' ? 'Selesai' : 'Dibatalkan' }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="empty">
                                Belum ada transaksi pembelian.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif

@endsection
