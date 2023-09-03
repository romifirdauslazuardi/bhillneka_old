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
            $table->dropForeign(['unit_id']);
            $table->dropColumn(['unit_id']);

            $table->string("unit")->after("category_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger("unit_id");
            $table->dropColumn(['unit']);

            $table->foreign("unit_id")
                ->references("id")
                ->on("units")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }
};
