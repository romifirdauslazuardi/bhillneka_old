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
            $table->string("address",255)->after("remote_address")->nullable();
            $table->string("mac_address",255)->after("address")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item_mikrotiks', function (Blueprint $table) {
            $table->dropColumn([
                'address',
                'mac_address'
            ]);
        });
    }
};
