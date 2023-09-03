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
        Schema::table('users', function (Blueprint $table) {
            $table->string("code",255)->after("id")->nullable();
            $table->string("phone",255)->nullable(true)->change();
            $table->integer("provider")->after("avatar")->default(1)->comment("1.Manual;2.Google");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string("false",255)->nullable(true)->change();
            $table->dropColumn(['code']);
            $table->dropColumn(['provider']);
        });
    }
};
