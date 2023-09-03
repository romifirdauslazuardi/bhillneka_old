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
        Schema::table('products', function (Blueprint $table) {
            $table->string("service",255)->after("mikrotik")->nullable();
            $table->string("server",255)->after("service")->nullable();
            $table->string("profile",255)->after("server")->nullable();
            $table->string("time_limit",255)->after("profile")->nullable();
            $table->string("comment",255)->after("time_limit")->nullable();
            $table->string("local_address",255)->after("comment")->nullable();
            $table->string("remote_address",255)->after("local_address")->nullable();
            $table->string("address",255)->after("remote_address")->nullable();
            $table->string("mac_address",255)->after("address")->nullable();
            $table->date("expired_date")->after("mac_address")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                "service",
                "server",
                "profile",
                "time_limit",
                "comment",
                "local_address",
                "remote_address",
                "address",
                "mac_address",
                "expired_date",
            ]);
        });
    }
};
