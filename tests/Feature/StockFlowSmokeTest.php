<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Sale;

class StockFlowSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/dashboard')
            ->assertRedirect('/login');
    }

    public function test_admin_can_open_dashboard(): void
    {
        $this->seed();

        $user = User::where('email', 'admin@adarasnack.test')
            ->firstOrFail();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertOk();
    }

    public function test_staff_cannot_open_user_management(): void
    {
        $this->seed();

        $user = User::where('email', 'staff@adarasnack.test')
            ->firstOrFail();

        $this->actingAs($user)
            ->get('/users')
            ->assertForbidden();
    }

    public function test_user_can_view_products(): void
    {
        $this->seed();

        $user = User::where('role', 'user')->firstOrFail();

        $this->actingAs($user)
            ->get('/products')
            ->assertOk();
    }

    public function test_user_cannot_open_dashboard(): void
    {
        $this->seed();

        $user = User::where('role', 'user')->firstOrFail();

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertForbidden();
    }

    public function test_user_cannot_manage_products(): void
    {
        $this->seed();

        $user = User::where('role', 'user')->firstOrFail();

        $this->actingAs($user)
            ->get('/products/create')
            ->assertForbidden();
    }

    public function test_user_cannot_access_internal_pages(): void
    {
        $this->seed();

        $user = User::where('role', 'user')->firstOrFail();

        $this->actingAs($user)->get('/categories')->assertForbidden();
        $this->actingAs($user)->get('/suppliers')->assertForbidden();
        $this->actingAs($user)->get('/purchases')->assertForbidden();
        $this->actingAs($user)->get('/sales')->assertForbidden();
        $this->actingAs($user)->get('/reports')->assertForbidden();
        $this->actingAs($user)->get('/users')->assertForbidden();
    }

    private function prepareTransactionData(): array
    {
        $this->seed();

        $admin = User::where('email', 'admin@adarasnack.test')
            ->firstOrFail();

        $product = Product::where('sku', 'ADS-001')->firstOrFail();

        $supplier = Supplier::where('name', 'Supplier Adara Snack')
            ->firstOrFail();

        return [$admin, $product, $supplier];
    }

    public function test_purchase_increases_product_stock(): void
    {
        [$admin, $product, $supplier] = $this->prepareTransactionData();

        $initialStock = $product->stock;

        $response = $this->actingAs($admin)->post('/purchases', [
            'supplier_id' => $supplier->id,
            'purchased_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ]);

        $response->assertRedirect();

        $this->assertSame(
            $initialStock + 5,
            (int) $product->fresh()->stock
        );
    }

    public function test_sale_decreases_product_stock(): void
    {
        [$admin, $product] = $this->prepareTransactionData();

        $initialStock = $product->stock;

        $response = $this->actingAs($admin)->post('/sales', [
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                ],
            ],
        ]);

        $response->assertRedirect();

        $this->assertSame(
            $initialStock - 3,
            (int) $product->fresh()->stock
        );
    }

    public function test_sale_is_rejected_when_stock_is_insufficient(): void
    {
        [$admin, $product] = $this->prepareTransactionData();

        $product->update(['stock' => 2]);

        $response = $this->actingAs($admin)->post('/sales', [
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 5,
                ],
            ],
        ]);

        $response->assertSessionHasErrors('items');

        $this->assertSame(2, (int) $product->fresh()->stock);

        $this->assertDatabaseCount('sales', 0);
    }

    public function test_cancelling_purchase_reverses_stock_increase(): void
    {
        [$admin, $product, $supplier] = $this->prepareTransactionData();

        $initialStock = (int) $product->stock;

        $this->actingAs($admin)->post('/purchases', [
            'supplier_id' => $supplier->id,
            'purchased_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 4,
                ],
            ],
        ])->assertRedirect();

        $this->assertSame(
            $initialStock + 4,
            (int) $product->fresh()->stock
        );

        $purchase = Purchase::latest('id')->firstOrFail();

        $this->post('/purchases/' . $purchase->id . '/cancel')
            ->assertRedirect();

        $this->assertSame(
            $initialStock,
            (int) $product->fresh()->stock
        );

        $this->assertSame('cancelled', $purchase->fresh()->status);
    }

    public function test_cancelling_sale_restores_product_stock(): void
    {
        [$admin, $product] = $this->prepareTransactionData();

        $initialStock = (int) $product->stock;

        $this->actingAs($admin)->post('/sales', [
            'sold_at' => now()->format('Y-m-d H:i:s'),
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 3,
                ],
            ],
        ])->assertRedirect();

        $this->assertSame(
            $initialStock - 3,
            (int) $product->fresh()->stock
        );

        $sale = Sale::latest('id')->firstOrFail();

        $this->post('/sales/' . $sale->id . '/cancel')
            ->assertRedirect();

        $this->assertSame(
            $initialStock,
            (int) $product->fresh()->stock
        );

        $this->assertSame('cancelled', $sale->fresh()->status);
    }
}
