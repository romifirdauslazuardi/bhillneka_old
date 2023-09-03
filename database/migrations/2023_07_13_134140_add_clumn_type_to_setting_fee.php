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
        Schema::table('setting_fee', function (Blueprint $table) {
            $table->string("mark",255)->after("id")->nullable();
            $table->decimal('limit', 16, 4)->after("mark")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('setting_fee', function (Blueprint $table) {
            $table->dropColumn([
                "mark",
                "limit",
            ]);
        });
    }
};
