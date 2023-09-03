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
        Schema::table('product_categories', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'business_id',
                'user_id',
            ]);

            $table->unsignedBigInteger("business_category_id")->after("name")->nullable();

            $table->foreign("business_category_id")
                ->references("id")
                ->on("business_categories")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_categories', function (Blueprint $table) {
            $table->unsignedBigInteger("business_id")->after("name")->nullable();
            $table->unsignedBigInteger("user_id")->after("business_id")->nullable();

            $table->foreign("business_id")
                ->references("id")
                ->on("business")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->dropForeign(['business_category_id']);
            $table->dropColumn(['business_category_id']);

        });
    }
};
