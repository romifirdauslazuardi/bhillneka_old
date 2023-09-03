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
            $table->integer("type_fee")->after("discount")->default(1)->comment("1.Percentage;2.Fixed");
            $table->decimal('total_owner_fee', 16, 4)->after("agen_fee")->default(0);
            $table->decimal('total_agen_fee', 16, 4)->after("total_owner_fee")->default(0);
            $table->integer("customer_type_fee")->after("total_agen_fee")->default(1)->comment("1.Percentage;2.Fixed");
            $table->decimal('customer_value_fee', 16, 4)->after("customer_type_fee")->default(0);
            $table->decimal('customer_total_fee', 16, 4)->after("customer_value_fee")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                "type_fee",
                "total_owner_fee",
                "total_agen_fee",
                "customer_type_fee",
                "customer_value_fee",
                "customer_total_fee"
            ]);
        });
    }
};
