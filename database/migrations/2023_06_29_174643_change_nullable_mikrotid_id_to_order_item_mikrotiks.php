<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('order_item_mikrotiks', function (Blueprint $table) {
            $table->string("mikrotik_id",255)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item_mikrotiks', function (Blueprint $table) {
            $table->string("mikrotik_id",255)->nullable(false)->change();
        });
    }
};
