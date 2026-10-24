<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $purchases = Purchase::with(['supplier', 'user'])
            ->where('status', 'completed')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('purchased_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('purchased_at', '<=', $endDate);
            })
            ->orderByDesc('purchased_at')
            ->get();

        $sales = Sale::with('user')
            ->where('status', 'completed')
            ->when($startDate, function ($query) use ($startDate) {
                $query->whereDate('sold_at', '>=', $startDate);
            })
            ->when($endDate, function ($query) use ($endDate) {
                $query->whereDate('sold_at', '<=', $endDate);
            })
            ->orderByDesc('sold_at')
            ->get();

        $totalPurchases = $purchases->sum('total');
        $totalSales = $sales->sum('total');

        return view('reports.index', compact(
            'purchases',
            'sales',
            'totalPurchases',
            'totalSales',
            'startDate',
            'endDate'
        ));
    }
}
