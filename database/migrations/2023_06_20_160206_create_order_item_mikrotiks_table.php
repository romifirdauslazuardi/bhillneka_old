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
        Schema::create('order_item_mikrotiks', function (Blueprint $table) {
            $table->id();
            $table->string("mikrotik_id");
            $table->unsignedBigInteger("order_item_id");
            $table->string("name",255)->nullable();
            $table->string("username",255)->nullable();
            $table->string("password",255)->nullable();
            $table->string("service",255)->nullable();
            $table->string("server",255)->nullable();
            $table->string("profile",255)->nullable();
            $table->string("time_limit",255)->nullable();
            $table->string("comment",255)->nullable();
            $table->string("disabled",255);
            $table->integer("type")->default(1)->comment("1.PPPO;2.Hotspot");
            $table->unsignedBigInteger("author_id");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("order_item_id")
                ->references("id")
                ->on("order_items")
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
        Schema::dropIfExists('order_item_mikrotiks');
    }
};
