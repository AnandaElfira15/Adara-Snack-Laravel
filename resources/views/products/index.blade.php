@extends('layouts.app')

@section('title', 'Produk')
@section('heading', 'Produk')

@section('content')

<style>
    .product-pagination {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 20px;
        padding-top: 18px;
        border-top: 1px solid #e5eaf1;
    }

    .product-pagination-info {
        color: #64748b;
        font-size: 13px;
    }

    .product-page-nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 6px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .product-page-nav .page-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        padding: 0;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #ffffff;
        color: #0b1f3a;
        font-size: 14px;
        line-height: 1;
        text-decoration: none;
        box-sizing: border-box;
        transition: background 0.2s, color 0.2s;
    }

    .product-page-nav a.page-button:hover {
        background: #eaf1ff;
        border-color: #b9ceff;
    }

    .product-page-nav .page-button.active {
        background: #0b1f3a;
        border-color: #0b1f3a;
        color: #ffffff;
        font-weight: 600;
    }

    .product-page-nav .page-button.disabled {
        background: #f8fafc;
        color: #cbd5e1;
        cursor: not-allowed;
    }

    @media (max-width: 640px) {
        .product-pagination {
            align-items: flex-start;
            flex-direction: column;
        }

        .product-page-nav {
            justify-content: flex-start;
        }
    }
</style>

<div class="page-actions">
    <p class="muted">
        Kelola informasi produk dan pantau jumlah stok.
    </p>

    @if(auth()->user()->role === 'admin')
        <a class="btn primary" href="{{ route('products.create') }}">
            ＋ Tambah Produk
        </a>
    @endif
</div>

<div class="panel">

    <form
        class="toolbar wrap-toolbar"
        method="GET"
        action="{{ route('products.index') }}"
    >
        <input
            type="text"
            name="q"
            value="{{ request('q') }}"
            placeholder="Cari nama produk atau SKU..."
        >

        <select name="category_id">
            <option value="">Semua kategori</option>

            @foreach($categories as $c)
                <option
                    value="{{ $c->id }}"
                    @selected(request('category_id') == $c->id)
                >
                    {{ $c->name }}
                </option>
            @endforeach
        </select>

        <select name="sort">
            <option
                value="name"
                @selected(request('sort', 'name') === 'name')
            >
                Nama
            </option>

            <option
                value="sku"
                @selected(request('sort') === 'sku')
            >
                SKU
            </option>

            <option
                value="selling_price"
                @selected(request('sort') === 'selling_price')
            >
                Harga
            </option>

            <option
                value="stock"
                @selected(request('sort') === 'stock')
            >
                Stok
            </option>
        </select>

        <select name="direction">
            <option
                value="asc"
                @selected(request('direction', 'asc') === 'asc')
            >
                Naik (A-Z)
            </option>

            <option
                value="desc"
                @selected(request('direction') === 'desc')
            >
                Turun (Z-A)
            </option>
        </select>

        <button type="submit" class="btn secondary">
            Terapkan
        </button>

        <a class="btn ghost" href="{{ route('products.index') }}">
            Reset
        </a>
    </form>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>NO</th>
                    <th>SKU</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga Jual</th>
                    <th class="center">Stok</th>
                    <th class="center">Minimum Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse($rows as $row)
                    <tr>
                        <td>
                            {{ $rows->firstItem() + $loop->index }}
                        </td>

                        <td class="code">
                            {{ $row->sku }}
                        </td>

                        <td class="strong">
                            {{ $row->name }}
                        </td>

                        <td>
                            {{ $row->category->name ?? '-' }}
                        </td>

                        <td>
                            Rp {{ number_format($row->selling_price, 0, ',', '.') }}
                        </td>

                        <td class="center">
                            <span class="stock-number {{ $row->stock <= $row->minimum_stock ? 'low' : '' }}">
                                {{ $row->stock }}
                            </span>
                        </td>

                        <td class="center">
                            {{ $row->minimum_stock }}
                        </td>

                        <td>
                            @if(auth()->user()->role === 'admin')
                                <div class="actions">
                                    <a
                                        class="btn small secondary"
                                        href="{{ route('products.edit', $row) }}"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('products.destroy', $row) }}"
                                        onsubmit="return confirm('Hapus produk ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn small danger-btn"
                                        >
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            @else
                                <span class="muted">Lihat saja</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">
                            Data produk tidak ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="product-pagination">
        <div class="product-pagination-info">
            Menampilkan {{ $rows->firstItem() ?? 0 }}
            sampai {{ $rows->lastItem() ?? 0 }}
            dari {{ $rows->total() }} produk
        </div>

        @if($rows->hasPages())
            <nav class="product-page-nav" aria-label="Navigasi halaman">

                {{-- Tombol halaman sebelumnya --}}
                @if($rows->onFirstPage())
                    <span class="page-button disabled" aria-disabled="true">
                        &lsaquo;
                    </span>
                @else
                    <a
                        class="page-button"
                        href="{{ $rows->previousPageUrl() }}"
                        aria-label="Halaman sebelumnya"
                    >
                        &lsaquo;
                    </a>
                @endif

                {{-- Nomor halaman --}}
                @foreach($rows->getUrlRange(1, $rows->lastPage()) as $page => $url)
                    @if($page == $rows->currentPage())
                        <span class="page-button active" aria-current="page">
                            {{ $page }}
                        </span>
                    @else
                        <a class="page-button" href="{{ $url }}">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Tombol halaman berikutnya --}}
                @if($rows->hasMorePages())
                    <a
                        class="page-button"
                        href="{{ $rows->nextPageUrl() }}"
                        aria-label="Halaman berikutnya"
                    >
                        &rsaquo;
                    </a>
                @else
                    <span class="page-button disabled" aria-disabled="true">
                        &rsaquo;
                    </span>
                @endif

            </nav>
        @endif
    </div>

</div>

@endsection
