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
            SettingSeeder::class,
            AttackLogSeeder::class,
            ManualActionSeeder::class,
        ]);
    }
}
