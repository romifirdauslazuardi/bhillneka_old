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
            $table->dropColumn(["expired_date"]);
            $table->integer("expired_month")->after("mac_address")->nullable();
            $table->unsignedBigInteger('category_id');
            $table->foreign("category_id")
                ->references("id")
                ->on("product_categories")
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
            $table->dropForeign('category_id');
            $table->date("expired_date")->after("mac_address")->nullable();
            $table->dropColumn(["expired_month"]);
        });
    }
};
