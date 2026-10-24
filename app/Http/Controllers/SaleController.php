<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $rows = Sale::with('user')
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(
                    'sale_number',
                    'like',
                    '%' . $request->q . '%'
                );
            })
            ->when($request->filled('from'), function ($query) use ($request) {
                $query->whereDate('sold_at', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($query) use ($request) {
                $query->whereDate('sold_at', '<=', $request->to);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest('sold_at')
            ->paginate(10)
            ->withQueryString();

        return view('sales.index', compact('rows'));
    }

    public function create()
    {
        return view('sales.create', [
            'products' => Product::where('stock', '>', 0)
                ->orderBy('name')
                ->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sold_at' => [
                'required',
                'date',
            ],
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ], [
            'items.required' => 'Tambahkan minimal satu produk.',
            'items.min' => 'Transaksi harus memiliki minimal satu produk.',
            'items.*.product_id.distinct' => 'Produk yang sama tidak boleh dimasukkan berulang. Gabungkan kuantitasnya.',
            'items.*.quantity.required' => 'Kuantitas produk wajib diisi.',
            'items.*.quantity.integer' => 'Kuantitas harus berupa bilangan bulat.',
            'items.*.quantity.min' => 'Kuantitas minimal 1.',
        ]);

        $sale = DB::transaction(function () use ($data) {
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'sale_number' => 'SO-' . now()->format('YmdHis')
                    . '-' . Str::upper(Str::random(8)),
                'total' => 0,
                'status' => 'completed',
                'sold_at' => $data['sold_at'],
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];

                if ($quantity < 1) {
                    throw ValidationException::withMessages([
                        'items' => 'Kuantitas produk harus minimal 1.',
                    ]);
                }

                if ((int) $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => 'Stok produk '
                            . $product->name
                            . ' tidak cukup. Stok tersedia: '
                            . $product->stock
                            . ', jumlah diminta: '
                            . $quantity
                            . '.',
                    ]);
                }

                $unitPrice = $product->selling_price;

                if (
                    !is_numeric($unitPrice) ||
                    !is_finite((float) $unitPrice) ||
                    (float) $unitPrice < 0
                ) {
                    throw ValidationException::withMessages([
                        'items' => 'Harga jual produk '
                            . $product->name
                            . ' belum valid. Periksa data produk terlebih dahulu.',
                    ]);
                }

                $unitPrice = (float) $unitPrice;
                $subtotal = round($unitPrice * $quantity, 2);

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ]);

                $product->decrement('stock', $quantity);

                $total += $subtotal;
            }

            $sale->update([
                'total' => round($total, 2),
            ]);

            return $sale;
        });

        return redirect()
            ->route('sales.show', $sale)
            ->with('success', 'Stock Out berhasil disimpan. Stok produk telah berkurang.');
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'items.product']);

        return view('sales.show', [
            'row' => $sale,
        ]);
    }

    public function cancel(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $lockedSale = Sale::whereKey($sale->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedSale->status !== 'completed') {
                throw ValidationException::withMessages([
                    'transaction' => 'Transaksi tidak dapat dibatalkan karena statusnya bukan completed atau sudah dibatalkan.',
                ]);
            }

            $lockedSale->load('items');

            foreach ($lockedSale->items as $item) {
                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $product->increment('stock', $item->quantity);
            }

            $lockedSale->update([
                'status' => 'cancelled',
            ]);
        });

        return back()->with(
            'success',
            'Penjualan dibatalkan dan stok produk telah dipulihkan.'
        );
    }
}
