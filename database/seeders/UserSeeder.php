<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Kepala IT',
            'email' => 'superadmin@rs.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
        ]);

        User::create([
            'name' => 'Staf Keamanan',
            'email' => 'admin@rs.com',
            'password' => Hash::make('password'),
            'role' => 'admin_jaringan',
        ]);

        User::create([
            'name' => 'Direktur RS',
            'email' => 'manajemen@rs.com',
            'password' => Hash::make('password'),
            'role' => 'manajemen',
        ]);
    }
}
