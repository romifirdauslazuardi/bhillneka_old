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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer("repeat_order_at")->after("table_id")->nullable();
            $table->integer("repeat_order_status")->after("repeat_order_at")->default(0)->comment("1.Aktif;2.Tidak Aktif");
            $table->unsignedBigInteger("order_id")->after("repeat_order_at")->nullable();

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
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn([
                'repeat_order_at',
                'repeat_order_status',
                'order_id'
            ]);
        });
    }
};
