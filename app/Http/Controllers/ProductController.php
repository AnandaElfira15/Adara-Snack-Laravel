<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index(Request $r)
    {
        $rows = Product::with('category')
            ->when($r->q, function ($b, $q) {
                $b->where(function ($x) use ($q) {
                    $x->where('name', 'like', "%{$q}%")
                      ->orWhere('sku', 'like', "%{$q}%");
                });
            })
            ->when($r->category_id, function ($b, $id) {
                $b->where('category_id', $id);
            })
            ->orderBy(
                in_array($r->sort, [
                    'name',
                    'sku',
                    'stock',
                    'selling_price'
                ]) ? $r->sort : 'name',
                $r->direction === 'desc' ? 'desc' : 'asc'
            )
            ->paginate(10)
            ->withQueryString();

        return view('products.index', [
            'rows' => $rows,
            'categories' => Category::orderBy('name')->get()
        ]);
    }

    public function create()
    {
        return view('products.form', [
            'row' => new Product(),
            'categories' => Category::orderBy('name')->get()
        ]);
    }

    public function store(Request $r)
    {
        $data = $this->validateData($r);

        Product::create($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        return view('products.form', [
            'row' => $product,
            'categories' => Category::orderBy('name')->get()
        ]);
    }

    public function update(Request $r, Product $product)
    {
        $data = $this->validateData($r, $product);

        $product->update($data);

        return redirect()
            ->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function show(Product $product)
    {
        $product->load('category');

        return view('products.show', [
            'row' => $product
        ]);
    }

    private function validateData(
        Request $r,
        ?Product $p = null
    ): array {
        $rules = [
            'sku' => [
                'required',
                'string',
                'max:50',
                Rule::unique('products', 'sku')->ignore($p?->id)
            ],
            'name' => [
                'required',
                'string',
                'max:150'
            ],
            'category_id' => [
                'required',
                'exists:categories,id'
            ],
            'purchase_price' => [
                'required',
                'numeric',
                'min:0'
            ],
            'selling_price' => [
                'required',
                'numeric',
                'min:0'
            ],
            'minimum_stock' => [
                'required',
                'integer',
                'min:0'
            ]
        ];

        if (!$p) {
            $rules['stock'] = [
                'required',
                'integer',
                'min:0'
            ];
        }

        return $r->validate($rules);
    }

    public function destroy(Product $product)
    {
        if (
            $product->purchaseItems()->exists()
            || $product->saleItems()->exists()
        ) {
            return back()->with(
                'error',
                'Produk sudah digunakan dalam transaksi dan tidak dapat dihapus.'
            );
        }

        $product->delete();

        return back()->with(
            'success',
            'Produk berhasil dihapus.'
        );
    }
}
