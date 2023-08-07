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
        Schema::table('mikrotik_configs', function (Blueprint $table) {
            $table->string("name")->after("id")->nullable();
            $table->text("description")->after("name")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mikrotik_configs', function (Blueprint $table) {
            $table->dropColumn([
                "name",
                "description"
            ]);
        });
    }
};
