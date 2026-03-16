<?php

namespace Database\Seeders;

use App\Models\ManualAction;
use Illuminate\Database\Seeder;

class ManualActionSeeder extends Seeder
{
    public function run(): void
    {
        $actions = [
            [
                'ip_address' => '45.143.221.9',
                'action_type' => 'block',
                'reason' => 'Melakukan serangan SQL Injection berulang kali (OR 1=1)',
            ],
            [
                'ip_address' => '218.92.0.115',
                'action_type' => 'block',
                'reason' => 'Mencoba DROP TABLE users dari endpoint /search',
            ],
            [
                'ip_address' => '104.248.135.22',
                'action_type' => 'whitelist',
                'reason' => 'IP internal perusahaan, bukan serangan sebenarnya (false positive)',
            ],
            [
                'ip_address' => '185.234.14.77',
                'action_type' => 'drop',
                'reason' => 'Mencoba eksekusi command shell (cmd.exe) melalui endpoint /debug',
            ],
            [
                'ip_address' => '202.51.107.22',
                'action_type' => 'block',
                'reason' => 'Mengunduh payload berbahaya dari server eksternal',
            ],
            [
                'ip_address' => '178.62.101.74',
                'action_type' => 'block',
                'reason' => 'Path traversal mencoba membaca /etc/passwd',
            ],
            [
                'ip_address' => '128.199.209.56',
                'action_type' => 'whitelist',
                'reason' => 'IP dari tim developer, akses ke .env sengaja untuk keperluan debug',
            ],
        ];

        foreach ($actions as $action) {
            ManualAction::create($action);
        }
    }
}
