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
            $table->integer("expired_month")->after("expired_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item_mikrotiks', function (Blueprint $table) {
            $table->dropColumn(["expired_month"]);
        });
    }
};
