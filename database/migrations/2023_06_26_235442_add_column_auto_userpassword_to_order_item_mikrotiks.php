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
            $table->integer("auto_userpassword")->after("id")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_item_mikrotiks', function (Blueprint $table) {
            $table->dropColumn([
                'auto_userpassword'
            ]);
        });
    }
};
