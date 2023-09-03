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
            $table->string("local_address",255)->after("comment")->nullable();
            $table->string("remote_address",255)->after("local_address")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item_mikrotiks', function (Blueprint $table) {
            $table->dropColumn([
                'local_address',
                'remote_address',
            ]);
        });
    }
};
