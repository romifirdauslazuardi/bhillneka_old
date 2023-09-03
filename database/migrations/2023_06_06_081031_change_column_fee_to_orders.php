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
            $table->dropColumn(["fee"]);
            $table->decimal("owner_fee")->after("discount")->default(0);
            $table->decimal("agen_fee")->after("owner_fee")->default(0);
            $table->string("doku_service_id",255)->after("note")->nullable();
            $table->string("doku_acquirer_id",255)->after("doku_service_id")->nullable();
            $table->string("doku_channel_id",255)->after("doku_acquirer_id")->nullable();
            $table->integer("payment_due_date")->after("doku_channel_id")->nullable();
            $table->string("expired_date",255)->after("payment_due_date")->nullable();
            $table->string("payment_url",255)->after("expired_date")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal("fee")->after("discount")->default(0);
            $table->dropColumn([
                'age_fee',
                'owner_fee',
                'doku_service_id',
                'doku_acquirer_id',
                'doku_channel_id',
                'payment_due_date',
                'expired_date',
                'payment_url',
            ]);
        });
    }
};
