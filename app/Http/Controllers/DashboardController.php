<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        $startOfMonth = now()->startOfMonth()->toDateTimeString();
        $endOfMonth = now()->endOfMonth()->toDateTimeString();

        return view('dashboard', [
            // Jumlah data utama
            'productCount' => Product::count(),
            'categoryCount' => Category::count(),
            'supplierCount' => Supplier::count(),

            // Total penjualan bulan ini
            'monthSales' => Sale::where('status', 'completed')
                ->whereBetween('sold_at', [
                    $startOfMonth,
                    $endOfMonth
                ])
                ->sum('total'),

            // Total pembelian bulan ini
            'monthPurchases' => Purchase::where('status', 'completed')
                ->whereBetween('purchased_at', [
                    $startOfMonth,
                    $endOfMonth
                ])
                ->sum('total'),

            // Produk dengan stok rendah
            'lowStock' => Product::with('category')
                ->whereColumn('stock', '<=', 'minimum_stock')
                ->orderBy('stock')
                ->take(8)
                ->get(),

            // Lima transaksi penjualan terbaru
            'recentSales' => Sale::with('user')
                ->latest('sold_at')
                ->take(5)
                ->get(),

            // Lima transaksi pembelian terbaru
            'recentPurchases' => Purchase::with(['supplier', 'user'])
                ->latest('purchased_at')
                ->take(5)
                ->get(),
        ]);
    }
}
