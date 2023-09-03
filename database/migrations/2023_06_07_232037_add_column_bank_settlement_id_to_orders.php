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
            $table->string("owner_bank_settlement_id")->after("doku_token_id")->nullable();
            $table->string("agen_bank_settlement_id")->after("owner_bank_settlement_id")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'owner_bank_settlement_id',
                'agen_bank_settlement_id',
            ]);
        });
    }
};
