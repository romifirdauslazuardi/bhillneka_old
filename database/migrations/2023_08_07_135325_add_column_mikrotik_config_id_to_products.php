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
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger("mikrotik_config_id")->after("id")->nullable();

            $table->foreign("mikrotik_config_id")
                ->references("id")
                ->on("mikrotik_configs")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(["mikrotik_config_id"]);
            $table->dropColumn(["mikrotik_config_id"]);
        });
    }
};
