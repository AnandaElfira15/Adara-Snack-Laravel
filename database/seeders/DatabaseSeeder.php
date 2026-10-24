<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================================
        // 1. MEMBUAT AKUN ADMIN
        // =====================================
        User::firstOrCreate(
            ['email' => 'admin@adarasnack.test'],
            [
                'name' => 'Admin Adara Snack',
                'password' => Hash::make('Admin123!'),
                'role' => 'admin',
            ]
        );

        // =====================================
        // 2. MEMBUAT AKUN STAFF
        // =====================================
        User::firstOrCreate(
            ['email' => 'staff@adarasnack.test'],
            [
                'name' => 'Staff Adara Snack',
                'password' => Hash::make('Staff123!'),
                'role' => 'staff',
            ]
        );

        // =====================================
        // 3. MEMBUAT AKUN USER
        // =====================================
        User::firstOrCreate(
            ['email' => 'user@adarasnack.test'],
            [
                'name' => 'User Adara Snack',
                'password' => Hash::make('User123!'),
                'role' => 'user',
            ]
        );

        // =====================================
        // 4. MEMBUAT KATEGORI PRODUK
        // =====================================
        $categories = [
            'Minuman',
            'Makanan Ringan',
            'Gorengan',
            'Camilan Kemasan',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate([
                'name' => $name,
            ]);
        }

        // =====================================
        // 5. MEMBUAT DATA SUPPLIER
        // =====================================
        Supplier::firstOrCreate(
            ['name' => 'Supplier Adara Snack'],
            [
                'phone' => '081234567890',
                'email' => 'supplier@adarasnack.test',
            ]
        );

        // =====================================
        // 6. MEMBUAT DATA PRODUK
        // =====================================
        $samples = [
            [
                'ADS-001',
                'Es Teh Manis',
                'Minuman',
                2500,
                5000,
                40,
                10,
            ],
            [
                'ADS-002',
                'Es Teh Lemon',
                'Minuman',
                4000,
                8000,
                25,
                8,
            ],
            [
                'ADS-003',
                'Kentang Goreng',
                'Makanan Ringan',
                6000,
                10000,
                20,
                5,
            ],
            [
                'ADS-004',
                'Sosis Goreng',
                'Gorengan',
                2500,
                5000,
                18,
                5,
            ],
            [
                'ADS-005',
                'Nugget Goreng',
                'Gorengan',
                5000,
                9000,
                3,
                5,
            ],
            [
                'ADS-006',
                'Keripik Singkong',
                'Camilan Kemasan',
                4000,
                7000,
                15,
                4,
            ],
        ];

        foreach (
            $samples as [
                $sku,
                $name,
                $category,
                $buy,
                $sell,
                $stock,
                $minimum
            ]
        ) {
            $categoryId = Category::where(
                'name',
                $category
            )->value('id');

            Product::firstOrCreate(
                ['sku' => $sku],
                [
                    'name' => $name,
                    'category_id' => $categoryId,
                    'purchase_price' => $buy,
                    'selling_price' => $sell,
                    'stock' => $stock,
                    'minimum_stock' => $minimum,
                ]
            );
        }
    }
}
