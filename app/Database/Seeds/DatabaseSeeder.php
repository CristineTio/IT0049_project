<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Seeds every table with sample data. Run with: php spark db:seed DatabaseSeeder
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Sales reference the other three tables, so they are seeded last.
        $this->call(ProductSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(SaleSeeder::class);
    }
}
