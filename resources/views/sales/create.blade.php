@extends('layouts.app')

@section('title', 'Buat Penjualan')
@section('heading', 'Buat Penjualan')

@section('content')
<div class="form-panel wide-form">

    <p class="muted">
        Pilih produk dan kuantitas. Total penjualan dihitung oleh
        server berdasarkan harga yang tersimpan di database.
    </p>

    {{-- Pesan kesalahan validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger" role="alert">
            <strong>Penjualan belum dapat disimpan.</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('sales.store') }}">
        @csrf

        {{-- Tanggal penjualan --}}
        <div>
            <label for="sold_at">
                Tanggal Penjualan <span class="required">*</span>
            </label>

            <input
                type="datetime-local"
                id="sold_at"
                name="sold_at"
                value="{{ old('sold_at', now()->format('Y-m-d\TH:i')) }}"
                required
            >
        </div>

        {{-- Daftar item --}}
        <div class="section-heading">
            <h3>Item Penjualan</h3>

            <button
                type="button"
                class="btn secondary small"
                onclick="addRow('sale-items')"
            >
                + Tambah Item
            </button>
        </div>

        <div id="sale-items" class="item-list">

            @php
                $oldItems = old('items', [
                    ['product_id' => '', 'quantity' => 1]
                ]);
            @endphp

            @foreach ($oldItems as $index => $oldItem)
                <div class="item-row">

                    <select
                        name="items[{{ $index }}][product_id]"
                        required
                    >
                        <option value="">Pilih produk</option>

                        @foreach ($products as $p)
                            <option
                                value="{{ $p->id }}"
                                @selected(
                                    (string) ($oldItem['product_id'] ?? '')
                                    === (string) $p->id
                                )
                            >
                                {{ $p->sku }} — {{ $p->name }}
                                (stok {{ $p->stock }} ·
                                Rp {{ number_format($p->selling_price, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>

                    <input
                        type="number"
                        name="items[{{ $index }}][quantity]"
                        min="1"
                        value="{{ $oldItem['quantity'] ?? 1 }}"
                        required
                        placeholder="Kuantitas"
                    >

                    <button
                        type="button"
                        class="btn small danger-btn"
                        onclick="removeRow(this)"
                    >
                        Hapus
                    </button>

                </div>
            @endforeach

        </div>

        {{-- Tombol form --}}
        <div class="form-actions">
            <a class="btn ghost" href="{{ route('sales.index') }}">
                Batal
            </a>

            <button type="submit" class="btn primary">
                Simpan Penjualan
            </button>
        </div>
    </form>
</div>

<script>
    let itemIndex = {{ count(old('items', [
        ['product_id' => '', 'quantity' => 1]
    ])) }};

    function addRow(id) {
        const box = document.getElementById(id);
        const firstRow = box.querySelector('.item-row');
        const row = firstRow.cloneNode(true);

        row.querySelectorAll('select, input').forEach(function (el) {
            el.name = el.name.replace(
                /items\[\d+\]/,
                'items[' + itemIndex + ']'
            );

            if (el.tagName === 'SELECT') {
                el.selectedIndex = 0;
            } else {
                el.value = 1;
            }
        });

        box.appendChild(row);
        itemIndex++;
    }

    function removeRow(btn) {
        const box = btn.closest('.item-list');

        if (box.children.length > 1) {
            btn.closest('.item-row').remove();
        }
    }
</script>
@endsection
