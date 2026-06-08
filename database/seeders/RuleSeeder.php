<?php

namespace Database\Seeders;

use App\Models\Rule;
use Illuminate\Database\Seeder;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // SQL Injection
            [
                'kategori' => 'SQL Injection',
                'pola' => 'UNION SELECT',
                'deskripsi' => 'Mencegah eksekusi penggabungan tabel database secara ilegal.',
            ],
            [
                'kategori' => 'SQL Injection',
                'pola' => 'OR 1=1',
                'deskripsi' => 'Mencegah bypass login authentication menggunakan logika boolean.',
            ],
            [
                'kategori' => 'SQL Injection',
                'pola' => 'DROP TABLE',
                'deskripsi' => 'Mencegah penghapusan tabel database oleh peretas.',
            ],
            [
                'kategori' => 'SQL Injection',
                'pola' => 'INSERT INTO',
                'deskripsi' => 'Mencegah injeksi query INSERT yang mencurigakan.',
            ],
            [
                'kategori' => 'SQL Injection',
                'pola' => 'WAITFOR DELAY',
                'deskripsi' => 'Mencegah serangan blind SQL injection berbasis waktu.',
            ],

            // XSS
            [
                'kategori' => 'XSS',
                'pola' => '<script>',
                'deskripsi' => 'Mencegah injeksi kode JavaScript murni ke dalam form atau URL.',
            ],
            [
                'kategori' => 'XSS',
                'pola' => 'javascript:alert',
                'deskripsi' => 'Mencegah eksekusi alert box berbahaya via URL scheme.',
            ],
            [
                'kategori' => 'XSS',
                'pola' => 'onerror=',
                'deskripsi' => 'Mencegah eksekusi payload XSS melalui manipulasi tag image HTML.',
            ],
            [
                'kategori' => 'XSS',
                'pola' => 'document.cookie',
                'deskripsi' => 'Mencegah pencurian cookie melalui JavaScript.',
            ],

            // Path Traversal
            [
                'kategori' => 'Path Traversal',
                'pola' => '../etc/passwd',
                'deskripsi' => 'Mencegah pembacaan file konfigurasi user pada server Linux.',
            ],
            [
                'kategori' => 'Path Traversal',
                'pola' => '..\\..\\windows\\system32',
                'deskripsi' => 'Mencegah eksploitasi direktori root pada server OS Windows.',
            ],
            [
                'kategori' => 'Path Traversal',
                'pola' => '....//....//....//etc/shadow',
                'deskripsi' => 'Mencegah variasi path traversal dengan encoding.',
            ],

            // Brute Force & Scanning
            [
                'kategori' => 'Brute Force',
                'pola' => 'wp-login.php',
                'deskripsi' => 'Memblokir bot yang melakukan pemindaian halaman login WordPress.',
            ],
            [
                'kategori' => 'Brute Force',
                'pola' => '.env',
                'deskripsi' => 'Mencegah peretas mengunduh file environment Laravel yang berisi kredensial.',
            ],
            [
                'kategori' => 'Brute Force',
                'pola' => 'phpmyadmin/',
                'deskripsi' => 'Memblokir akses bot scanning ke halaman antarmuka database server.',
            ],
            [
                'kategori' => 'Brute Force',
                'pola' => 'xmlrpc.php',
                'deskripsi' => 'Mencegah eksploitasi xmlrpc pada WordPress untuk brute force.',
            ],

            // Lainnya (RCE, Malicious Commands)
            [
                'kategori' => 'Lainnya',
                'pola' => 'eval(',
                'deskripsi' => 'Mencegah Remote Code Execution (RCE) via fungsi PHP berbahaya.',
            ],
            [
                'kategori' => 'Lainnya',
                'pola' => 'base64_decode(',
                'deskripsi' => 'Mencegah eksekusi payload obfuscated (kode tersembunyi) dari hacker.',
            ],
            [
                'kategori' => 'Lainnya',
                'pola' => 'cmd.exe',
                'deskripsi' => 'Mencegah injeksi perintah command prompt secara remote.',
            ],
            [
                'kategori' => 'Lainnya',
                'pola' => 'wget http://',
                'deskripsi' => 'Mencegah pengunduhan file berbahaya dari server eksternal.',
            ],
        ];

        // Tambahkan timestamp manual agar konsisten
        foreach ($rules as &$rule) {
            $rule['created_at'] = now();
            $rule['updated_at'] = now();
        }

        Rule::insert($rules);
    }
}
