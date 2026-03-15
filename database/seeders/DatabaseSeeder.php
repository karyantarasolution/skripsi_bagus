<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            RuleSeeder::class,
            AttackLogSeeder::class,
    // Tambahkan seeder lain di sini jika ad    ]);
        ]);
    }
}