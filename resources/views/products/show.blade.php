@extends('layouts.app')

@section('title', 'Detail Produk')
@section('heading', 'Detail Produk')

@section('content')
<div class="form-panel wide-form">
    <p class="muted">Informasi lengkap produk Adara Snack.</p>

    <div class="form-grid">
        <div>
            <label>SKU</label>
            <p class="strong">{{ $row->sku }}</p>
        </div>

        <div>
            <label>Nama Produk</label>
            <p class="strong">{{ $row->name }}</p>
        </div>

        <div>
            <label>Kategori</label>
            <p>{{ $row->category->name ?? '-' }}</p>
        </div>

        <div>
            <label>Harga Beli</label>
            <p>Rp {{ number_format($row->purchase_price, 0, ',', '.') }}</p>
        </div>

        <div>
            <label>Harga Jual</label>
            <p>Rp {{ number_format($row->selling_price, 0, ',', '.') }}</p>
        </div>

        <div>
            <label>Stok Saat Ini</label>
            <p>{{ $row->stock }}</p>
        </div>

        <div>
            <label>Minimum Stok</label>
            <p>{{ $row->minimum_stock }}</p>
        </div>
    </div>

    <div class="form-actions">
        <a class="btn ghost" href="{{ route('products.index') }}">Kembali</a>

        @if(auth()->user()->role === 'admin')
            <a class="btn primary" href="{{ route('products.edit', $row) }}">Edit Produk</a>
        @endif
    </div>
</div>
@endsection
