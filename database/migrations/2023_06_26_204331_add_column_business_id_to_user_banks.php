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
        Schema::table('user_banks', function (Blueprint $table) {
            $table->unsignedBigInteger("business_id")->after("bank_id")->nullable();

            $table->foreign("business_id")
                ->references("id")
                ->on("business")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_banks', function (Blueprint $table) {
            $table->dropForeign(['business_id']);
            $table->dropColumn(['business_id']);
        });
    }
};
