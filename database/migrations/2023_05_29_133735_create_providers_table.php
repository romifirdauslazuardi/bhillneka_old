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
        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string("name",255);
            $table->string("client_id",255)->nullable();
            $table->string("secret_key",255)->nullable();
            $table->text("note")->nullable();
            $table->integer("type")->default(1)->comment("1.Upload Manual;2.Doku");
            $table->integer("status")->default(0)->comment("1.Aktif;2.Tidak Aktif");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('providers');
    }
};
