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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string("code",255);
            $table->string("name",255);
            $table->string("slug",255);
            $table->decimal('price', 16, 4)->default(0);
            $table->text("description")->nullable();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("category_id");
            $table->unsignedBigInteger("unit_id");
            $table->integer("is_using_stock")->default(0)->comment("1.Ya;2.Tidak");
            $table->integer("status")->default(0)->comment("1.Aktif;2.Tidak Aktif");
            $table->timestamps();
            $table->softDeletes();

            $table->foreign("user_id")
                ->references("id")
                ->on("users")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("category_id")
                ->references("id")
                ->on("product_categories")
                ->onDelete("cascade")
                ->onUpdate("cascade");

            $table->foreign("unit_id")
                ->references("id")
                ->on("units")
                ->onDelete("cascade")
                ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
