<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AttackLog;
use Carbon\Carbon;

class AttackLogSeeder extends Seeder
{
    public function run()
    {
        $kategoriList = ['SQL Injection', 'XSS', 'Path Traversal', 'Brute Force', 'Lainnya'];
        $riskLevels = ['Low', 'Medium', 'High', 'Critical'];
        $actions = ['Allowed', 'Logged', 'Blocked', 'Dropped'];
        $endpoints = ['/login', '/api/data', '/admin', '/cpanel', '/wp-admin', '/api/users', '/search', '/index.php', '/db/admin', '/config.php'];
        $origins = ['Jakarta', 'Surabaya', 'Banjarmasin', 'Bandung', 'Medan', 'Makassar', 'Luar Negeri', 'Unknown'];

        $ipPools = [
            '192.168.1.', '10.0.0.', '172.16.0.',
            '103.10.0.', '36.70.', '114.120.',
            '180.240.', '202.150.', '125.160.',
        ];

        $data = [];

        // Generate 200 sample records spanning the last 30 days
        for ($i = 0; $i < 200; $i++) {
            $date = Carbon::now()->subDays(rand(0, 29))->subHours(rand(0, 23))->subMinutes(rand(0, 59));

            $kategori = $kategoriList[array_rand($kategoriList)];

            // Map pola_terdeteksi based on kategori
            switch ($kategori) {
                case 'SQL Injection':
                    $pola = collect(["' OR '1'='1", "UNION SELECT *", "DROP TABLE users", "'; DROP TABLE", "1=1--", "' UNION SELECT", "admin'--", "OR 1=1"])->random();
                    break;
                case 'XSS':
                    $pola = collect(["<script>alert(1)</script>", "javascript:alert(1)", "onerror=alert(1)", "<img src=x onerror=alert(1)>", "document.cookie", "<iframe src=javascript:alert(1)>"])->random();
                    break;
                case 'Path Traversal':
                    $pola = collect(["../../../etc/passwd", "..\\..\\windows\\system32", "%2e%2e%2fetc%2fpasswd", "....//....//etc/passwd", "..%252f..%252f"])->random();
                    break;
                case 'Brute Force':
                    $pola = collect(["wp-login.php", ".env", "phpmyadmin/", "/admin/", "xmlrpc.php", "wp-admin", "config.php.bak"])->random();
                    break;
                default:
                    $pola = collect(["eval(base64_decode(", "cmd.exe /c", "wget http://", "system('id')", "passthru(", "base64_decode("])->random();
            }

            $riskLevel = $riskLevels[array_rand($riskLevels)];
            $action = ($riskLevel === 'Critical' || $riskLevel === 'High')
                ? collect(['Blocked', 'Dropped'])->random()
                : collect(['Allowed', 'Logged'])->random();

            // Make some IPs repeat for realistic patterns
            $ipBase = $ipPools[array_rand($ipPools)];
            $ip = rand(0, 1) ? $ipBase . rand(1, 255) : rand(1, 223) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(1, 254);

            $data[] = [
                'ip_address' => $ip,
                'kategori' => $kategori,
                'pola_terdeteksi' => $pola,
                'endpoint' => $endpoints[array_rand($endpoints)],
                'origin' => $origins[array_rand($origins)],
                'risk_level' => $riskLevel,
                'action_taken' => $action,
                'created_at' => $date,
                'updated_at' => $date,
            ];
        }

        // Batch insert all records
        foreach (array_chunk($data, 50) as $chunk) {
            AttackLog::insert($chunk);
        }

        // Also add some sample manual actions
        if (\App\Models\ManualAction::count() === 0) {
            \App\Models\ManualAction::create([
                'ip_address' => '103.10.0.45',
                'action_type' => 'block',
                'reason' => 'Terdeteksi melakukan brute force pada halaman login',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ]);
            \App\Models\ManualAction::create([
                'ip_address' => '180.240.12.78',
                'action_type' => 'drop',
                'reason' => 'Scanning port berulang pada server database',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ]);
            \App\Models\ManualAction::create([
                'ip_address' => '192.168.1.50',
                'action_type' => 'whitelist',
                'reason' => 'IP internal server farmasi - false positive',
                'created_at' => Carbon::now()->subHours(6),
                'updated_at' => Carbon::now()->subHours(6),
            ]);
        }
    }
}
