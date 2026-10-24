@extends('layouts.app')

@section('title', 'Laporan')
@section('heading', 'Laporan')

@section('content')

<style>
    .report-layout {
        display: grid;
        grid-template-columns: 1.15fr 1fr;
        gap: 16px;
        align-items: stretch;
    }

    .report-filter-panel,
    .report-summary-panel {
        min-width: 0;
        padding: 16px;
    }

    .report-filter-form {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
        align-items: end;
    }

    .report-filter-form label {
        display: block;
        margin-bottom: 6px;
        font-size: 13px;
        font-weight: 600;
    }

    .report-filter-form input {
        width: 100%;
        min-width: 0;
        box-sizing: border-box;
        padding: 9px 10px;
    }

    .report-filter-actions {
        grid-column: 1 / -1;
        display: flex;
        gap: 8px;
    }

    .report-filter-actions .btn {
        padding: 8px 14px;
        font-size: 13px;
    }

    .report-summary-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
    }

    .report-summary-grid .stat-card {
        min-width: 0;
        padding: 14px;
    }

    .report-summary-grid .stat-top {
        font-size: 13px;
    }

    .report-summary-grid .stat-card strong {
        display: block;
        margin: 12px 0 6px;
        font-size: clamp(16px, 1.6vw, 23px);
        overflow-wrap: anywhere;
    }

    .report-summary-grid .stat-card small {
        font-size: 11px;
    }

    .report-section {
        margin-top: 16px;
    }

    .report-section .panel-head {
        padding-bottom: 10px;
    }

    .report-section .panel-head h3 {
        font-size: 15px;
    }

    .report-section .panel-head p {
        font-size: 12px;
    }

    .report-section table {
        font-size: 12px;
    }

    .report-section th,
    .report-section td {
        padding: 8px 10px;
        line-height: 1.3;
        white-space: nowrap;
    }

    .report-section .status {
        padding: 4px 8px;
        font-size: 11px;
    }

    @media (max-width: 900px) {
        .report-layout {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 520px) {
        .report-filter-form {
            grid-template-columns: 1fr;
        }

        .report-filter-actions {
            grid-column: auto;
        }

        .report-summary-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

{{-- Filter dan Ringkasan --}}
<div class="report-layout">

    <div class="panel report-filter-panel">
        <div class="panel-head">
            <div>
                <h3>Filter Pencarian</h3>
                <p>Pilih periode transaksi.</p>
            </div>
        </div>

        <form method="GET"
              action="{{ route('reports.index') }}"
              class="report-filter-form">

            <div>
                <label for="start_date">Tanggal Mulai</label>
                <input
                    type="date"
                    id="start_date"
                    name="start_date"
                    value="{{ $startDate }}"
                >
            </div>

            <div>
                <label for="end_date">Tanggal Akhir</label>
                <input
                    type="date"
                    id="end_date"
                    name="end_date"
                    value="{{ $endDate }}"
                    min="{{ $startDate }}"
                >
            </div>

            <div class="report-filter-actions">
                <button type="submit" class="btn primary">
                    Cari
                </button>

                <a href="{{ route('reports.index') }}"
                   class="btn secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <div class="panel report-summary-panel">
        <div class="panel-head">
            <div>
                <h3>Ringkasan Transaksi</h3>
                <p>Total transaksi yang selesai.</p>
            </div>
        </div>

        <div class="report-summary-grid">

            <div class="stat-card">
                <div class="stat-top">
                    <span>Total Pembelian</span>
                    <span class="stat-icon blue">↓</span>
                </div>

                <strong>
                    Rp {{ number_format((float) $totalPurchases, 0, ',', '.') }}
                </strong>

                <small>{{ $purchases->count() }} transaksi selesai</small>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <span>Total Penjualan</span>
                    <span class="stat-icon green">↑</span>
                </div>

                <strong>
                    Rp {{ number_format((float) $totalSales, 0, ',', '.') }}
                </strong>

                <small>{{ $sales->count() }} transaksi selesai</small>
            </div>

        </div>
    </div>

</div>

{{-- Tabel Pembelian --}}
<div class="panel report-section">
    <div class="panel-head">
        <div>
            <h3>Detail Pembelian</h3>
            <p>Daftar transaksi pembelian yang selesai.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Supplier</th>
                    <th>Petugas</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($purchases as $purchase)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="code">{{ $purchase->purchase_number }}</td>
                        <td>{{ $purchase->purchased_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $purchase->supplier?->name ?? '-' }}</td>
                        <td>{{ $purchase->user?->name ?? '-' }}</td>
                        <td class="strong">
                            Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}
                        </td>
                        <td><span class="status good">Selesai</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="empty">
                            Tidak ada transaksi pembelian pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Tabel Penjualan --}}
<div class="panel report-section">
    <div class="panel-head">
        <div>
            <h3>Detail Penjualan</h3>
            <p>Daftar transaksi penjualan yang selesai.</p>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>No. Transaksi</th>
                    <th>Tanggal</th>
                    <th>Petugas</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($sales as $sale)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td class="code">{{ $sale->sale_number }}</td>
                        <td>{{ $sale->sold_at?->format('d/m/Y H:i') ?? '-' }}</td>
                        <td>{{ $sale->user?->name ?? '-' }}</td>
                        <td class="strong">
                            Rp {{ number_format((float) $sale->total, 0, ',', '.') }}
                        </td>
                        <td><span class="status good">Selesai</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="empty">
                            Tidak ada transaksi penjualan pada periode ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
