<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('products')->insertBatch([
            ['name' => 'Cola 1.5L', 'price' => '85.00', 'stock_quantity' => 40, 'created_at' => '2026-09-01 08:00:00'],
            ['name' => 'Mineral Water 500ml', 'price' => '20.00', 'stock_quantity' => 120, 'created_at' => '2026-09-01 08:05:00'],
            ['name' => 'Potato Chips 85g', 'price' => '38.00', 'stock_quantity' => 25, 'created_at' => '2026-09-01 08:10:00'],
            ['name' => 'Instant Pancit Canton', 'price' => '18.00', 'stock_quantity' => 3, 'created_at' => '2026-09-01 08:15:00'],
            ['name' => '3-in-1 Coffee (10 sachets)', 'price' => '75.00', 'stock_quantity' => 30, 'created_at' => '2026-09-02 09:00:00'],
            ['name' => 'Powdered Milk 300g', 'price' => '135.00', 'stock_quantity' => 15, 'created_at' => '2026-09-02 09:05:00'],
            ['name' => 'White Bread Loaf', 'price' => '78.00', 'stock_quantity' => 0, 'created_at' => '2026-09-02 09:10:00'],
            ['name' => 'Bath Soap 135g', 'price' => '52.00', 'stock_quantity' => 22, 'created_at' => '2026-09-03 10:00:00'],
        ]);
    }
}
