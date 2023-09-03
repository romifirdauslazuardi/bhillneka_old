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
        Schema::create('setting_customer_fee', function (Blueprint $table) {
            $table->id();
            $table->string("mark",255);
            $table->decimal('limit', 16, 4)->default(0);
            $table->integer("type")->default(1)->comment("1.Percentage;2.Fixed");
            $table->decimal('value', 16, 4)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_customer_fee');
    }
};
