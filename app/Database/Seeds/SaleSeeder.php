<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        // The seeded stock quantities already account for these sales.
        $this->db->table('sales')->insertBatch([
            ['product_id' => 1, 'customer_id' => 1, 'sold_by' => 3, 'quantity' => 2, 'total_price' => '170.00', 'created_at' => '2026-10-01 10:12:00'],
            ['product_id' => 2, 'customer_id' => null, 'sold_by' => 3, 'quantity' => 5, 'total_price' => '100.00', 'created_at' => '2026-10-01 11:40:00'],
            ['product_id' => 3, 'customer_id' => 2, 'sold_by' => 4, 'quantity' => 3, 'total_price' => '114.00', 'created_at' => '2026-10-02 14:05:00'],
            ['product_id' => 6, 'customer_id' => 3, 'sold_by' => 4, 'quantity' => 1, 'total_price' => '135.00', 'created_at' => '2026-10-03 09:30:00'],
            ['product_id' => 5, 'customer_id' => null, 'sold_by' => 3, 'quantity' => 2, 'total_price' => '150.00', 'created_at' => '2026-10-03 16:20:00'],
            ['product_id' => 4, 'customer_id' => 5, 'sold_by' => 2, 'quantity' => 4, 'total_price' => '72.00', 'created_at' => '2026-10-04 13:10:00'],
        ]);
    }
}
