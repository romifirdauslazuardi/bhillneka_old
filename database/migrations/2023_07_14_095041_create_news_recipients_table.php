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
        Schema::create('news_recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("news_id");
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("author_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("news_id")
                ->references("id")
                ->on("news")
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
        Schema::dropIfExists('news_recipients');
    }
};
