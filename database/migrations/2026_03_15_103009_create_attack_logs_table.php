<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attack_logs', function (Blueprint $table) {
            $table->id();
            $table->string('ip_address', 45); // Support IPv4 & IPv6
            $table->string('kategori');       // Contoh: SQL Injection, XSS
            $table->text('pola_terdeteksi');  // String/pola yang memicu IDS
            $table->string('endpoint');       // URL yang diserang (ex: /login)
            $table->string('origin')->nullable(); // Negara/Asal (opsional)
            $table->enum('risk_level', ['Low', 'Medium', 'High', 'Critical'])->default('Low');
            $table->enum('action_taken', ['Allowed', 'Logged', 'Blocked', 'Dropped'])->default('Logged');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attack_logs');
    }
};