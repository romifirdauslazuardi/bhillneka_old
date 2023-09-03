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
        Schema::create('order_dokus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("order_id");
            $table->string("target");
            $table->json("data");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("order_id")
                ->references("id")
                ->on("orders")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_dokus');
    }
};
