<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $this->db->table('customers')->insertBatch([
            ['full_name' => 'Maria Santos', 'email' => 'maria.santos@example.com', 'phone' => '09171234501', 'created_at' => '2026-09-01 09:15:00'],
            ['full_name' => 'Juan Dela Cruz', 'email' => 'juan.delacruz@example.com', 'phone' => '09181234502', 'created_at' => '2026-09-02 10:30:00'],
            ['full_name' => 'Angela Reyes', 'email' => 'angela.reyes@example.com', 'phone' => '09191234503', 'created_at' => '2026-09-03 13:45:00'],
            ['full_name' => 'Mark Villanueva', 'email' => 'mark.villanueva@example.com', 'phone' => null, 'created_at' => '2026-09-05 15:20:00'],
            ['full_name' => 'Patricia Gomez', 'email' => 'patricia.gomez@example.com', 'phone' => '09201234505', 'created_at' => '2026-09-08 11:05:00'],
        ]);
    }
}
