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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string("code",255);
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("customer_id")->nullable();
            $table->decimal('discount', 16, 4)->default(0);
            $table->decimal('fee', 16, 4)->default(0);
            $table->unsignedBigInteger("provider_id");
            $table->text("note")->nullable();
            $table->string("status");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("customer_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("provider_id")
                ->references("id")
                ->on("providers")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
