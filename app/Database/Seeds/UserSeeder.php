<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Demo password for every seeded staff account. Change it after deploying.
     */
    private const DEMO_PASSWORD = 'password123';

    public function run(): void
    {
        $password = password_hash(self::DEMO_PASSWORD, PASSWORD_DEFAULT);

        $this->db->table('users')->insertBatch([
            ['username' => 'admin', 'full_name' => 'Andrea Cruz', 'password' => $password, 'created_at' => '2026-08-28 08:00:00'],
            ['username' => 'manager', 'full_name' => 'Paolo Garcia', 'password' => $password, 'created_at' => '2026-08-28 08:30:00'],
            ['username' => 'cashier01', 'full_name' => 'Ramon Bautista', 'password' => $password, 'created_at' => '2026-08-29 09:00:00'],
            ['username' => 'cashier02', 'full_name' => 'Liza Fernandez', 'password' => $password, 'created_at' => '2026-08-29 09:15:00'],
            ['username' => 'inventory', 'full_name' => 'Jessa Ramos', 'password' => $password, 'created_at' => '2026-08-30 10:00:00'],
        ]);
    }
}
