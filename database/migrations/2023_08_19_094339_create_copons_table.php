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
        Schema::create('copons', function (Blueprint $table) {
            $table->id();
            $table->string("copon_code");
            $table->integer('rate_of_discount');
            $table->double('max_amount', 15, 8);
            $table->double('min_amount', 15, 8);
            $table->foreignId('product_id')->constrained();
            $table->foreignId('seller_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('copons');
    }
};
