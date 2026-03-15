<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rule;

class RuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            [
                'kategori' => 'SQL Injection',
                'pola' => 'UNION SELECT',
                'deskripsi' => 'Mencegah eksekusi penggabungan tabel database secara ilegal.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'SQL Injection',
                'pola' => 'OR 1=1',
                'deskripsi' => 'Mencegah bypass login authentication menggunakan logika boolean.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'SQL Injection',
                'pola' => 'DROP TABLE',
                'deskripsi' => 'Mencegah penghapusan tabel database oleh peretas.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'kategori' => 'XSS',
                'pola' => '<script>',
                'deskripsi' => 'Mencegah injeksi kode JavaScript murni ke dalam form atau URL.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'XSS',
                'pola' => 'javascript:alert',
                'deskripsi' => 'Mencegah eksekusi alert box berbahaya via URL scheme.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'XSS',
                'pola' => 'onerror=',
                'deskripsi' => 'Mencegah eksekusi payload XSS melalui manipulasi tag image HTML.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kategori' => 'Path Traversal',
                'pola' => '../etc/passwd',
                'deskripsi' => 'Mencegah pembacaan file konfigurasi user pada server Linux.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Path Traversal',
                'pola' => '..\\..\\windows\\system32',
                'deskripsi' => 'Mencegah eksploitasi direktori root pada server OS Windows.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kategori' => 'Brute Force',
                'pola' => 'wp-login.php',
                'deskripsi' => 'Memblokir bot yang melakukan pemindaian halaman login WordPress.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Brute Force',
                'pola' => '.env',
                'deskripsi' => 'Mencegah peretas mengunduh file environment Laravel yang berisi kredensial.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Brute Force',
                'pola' => 'phpmyadmin/',
                'deskripsi' => 'Memblokir akses bot scanning ke halaman antarmuka database server.',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'kategori' => 'Lainnya',
                'pola' => 'eval(',
                'deskripsi' => 'Mencegah Remote Code Execution (RCE) via fungsi PHP berbahaya.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Lainnya',
                'pola' => 'base64_decode(',
                'deskripsi' => 'Mencegah eksekusi payload obfuscated (kode tersembunyi) dari hacker.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kategori' => 'Lainnya',
                'pola' => 'cmd.exe',
                'deskripsi' => 'Mencegah injeksi perintah command prompt secara remote.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        Rule::insert($rules);
    }
}