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
        Schema::create('business', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("location");
            $table->string("description")->nullable();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("category_id");
            $table->char("village_code",10);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("category_id")
                ->references("id")
                ->on("business_categories")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("village_code")
                ->references("code")
                ->on("indonesia_villages")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business');
    }
};
