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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string("name",255);
            $table->unsignedBigInteger("business_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("author_id");
            $table->timestamps();
            $table->softDeletes();

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

            $table->foreign("author_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
