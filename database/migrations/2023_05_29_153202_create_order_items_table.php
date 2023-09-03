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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->unsignedBigInteger("product_id");
            $table->string("product_code");
            $table->string("product_name");
            $table->decimal('product_price', 16, 4)->default(0);
            $table->decimal('qty', 16, 4)->default(0);
            $table->decimal('discount', 16, 4)->default(0);
            $table->decimal('total', 16, 4)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("order_id")
                ->references("id")
                ->on("orders")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("product_id")
                ->references("id")
                ->on("products")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
