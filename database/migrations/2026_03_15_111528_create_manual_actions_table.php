<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
  public function up()
{
    Schema::create('manual_actions', function (Blueprint $table) {
        $table->id();
        $table->string('ip_address');
        $table->string('action_type'); // block, whitelist, drop
        $table->text('reason');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_actions');
    }
};
