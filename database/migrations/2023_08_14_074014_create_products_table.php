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
            $table->string("name");
            $table->text("desc");
            $table->string("image");
            $table->integer ("react")->default(0);
            $table->double('original_price', 15, 8);
            $table->double('price_after_discount', 15, 8)->nullable();
            $table->integer('stock')->default(0);
            $table->string("tag")->nullable();
            $table->unsignedBigInteger('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->foreignId('cate_id')->constrained();
            $table->timestamps();
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
