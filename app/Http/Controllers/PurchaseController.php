<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $rows = Purchase::with(['supplier', 'user'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $query->where(
                    'purchase_number',
                    'like',
                    '%' . $request->q . '%'
                );
            })
            ->when($request->filled('from'), function ($query) use ($request) {
                $query->whereDate('purchased_at', '>=', $request->from);
            })
            ->when($request->filled('to'), function ($query) use ($request) {
                $query->whereDate('purchased_at', '<=', $request->to);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest('purchased_at')
            ->paginate(10)
            ->withQueryString();

        return view('purchases.index', compact('rows'));
    }

    public function create()
    {
        return view('purchases.create', [
            'suppliers' => Supplier::orderBy('name')->get(),
            'products' => Product::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => [
                'required',
                'integer',
                'exists:suppliers,id',
            ],
            'purchased_at' => [
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
            'supplier_id.required' => 'Silakan pilih supplier.',
            'supplier_id.exists' => 'Supplier yang dipilih tidak ditemukan.',
            'items.required' => 'Tambahkan minimal satu produk.',
            'items.min' => 'Transaksi harus memiliki minimal satu produk.',
            'items.*.product_id.distinct' => 'Produk yang sama tidak boleh dimasukkan berulang. Gabungkan kuantitasnya.',
            'items.*.quantity.required' => 'Kuantitas produk wajib diisi.',
            'items.*.quantity.integer' => 'Kuantitas harus berupa bilangan bulat.',
            'items.*.quantity.min' => 'Kuantitas minimal 1.',
        ]);

        $purchase = DB::transaction(function () use ($data) {
            $purchase = Purchase::create([
                'supplier_id' => $data['supplier_id'],
                'user_id' => auth()->id(),
                'purchase_number' => 'PO-' . now()->format('YmdHis')
                    . '-' . Str::upper(Str::random(8)),
                'total' => 0,
                'status' => 'completed',
                'purchased_at' => $data['purchased_at'],
            ]);

            $total = 0;

            foreach ($data['items'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];
                $unitCost = $product->purchase_price;

                if (
                    !is_numeric($unitCost) ||
                    !is_finite((float) $unitCost) ||
                    (float) $unitCost < 0
                ) {
                    throw ValidationException::withMessages([
                        'items' => 'Harga beli produk '
                            . $product->name
                            . ' belum valid. Periksa data produk terlebih dahulu.',
                    ]);
                }

                $unitCost = (float) $unitCost;
                $subtotal = round($unitCost * $quantity, 2);

                $purchase->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'subtotal' => $subtotal,
                ]);

                $product->increment('stock', $quantity);

                $total += $subtotal;
            }

            $purchase->update([
                'total' => round($total, 2),
            ]);

            return $purchase;
        });

        return redirect()
            ->route('purchases.show', $purchase)
            ->with('success', 'Stock In berhasil disimpan. Stok produk telah bertambah.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['supplier', 'user', 'items.product']);

        return view('purchases.show', [
            'row' => $purchase,
        ]);
    }

    public function cancel(Purchase $purchase)
    {
        $result = DB::transaction(function () use ($purchase) {
            $lockedPurchase = Purchase::whereKey($purchase->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedPurchase->status !== 'completed') {
                return 'invalid_status';
            }

            $lockedPurchase->load('items');

            foreach ($lockedPurchase->items as $item) {
                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ((int) $product->stock < (int) $item->quantity) {
                    return 'insufficient_stock';
                }
            }

            foreach ($lockedPurchase->items as $item) {
                $product = Product::whereKey($item->product_id)
                    ->lockForUpdate()
                    ->firstOrFail();

                $product->decrement('stock', $item->quantity);
            }

            $lockedPurchase->update([
                'status' => 'cancelled',
            ]);

            return 'cancelled';
        });

        if ($result === 'invalid_status') {
            return back()->with(
                'error',
                'Transaksi tidak dapat dibatalkan karena statusnya bukan completed.'
            );
        }

        if ($result === 'insufficient_stock') {
            return back()->with(
                'error',
                'Pembelian tidak dapat dibatalkan karena stok produk sudah terpakai.'
            );
        }

        return back()->with(
            'success',
            'Stock In dibatalkan dan stok produk telah disesuaikan.'
        );
    }
}
