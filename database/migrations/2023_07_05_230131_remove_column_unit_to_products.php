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
            $table->dropForeign(["category_id"]);
            $table->dropColumn([
                "category_id",
                "unit"
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger("category_id")->after("user_id")->nullable();
            $table->string("unit",255)->after("category_id");

            $table->foreign("category_id")
                ->references("id")
                ->on("product_categories")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }
};
