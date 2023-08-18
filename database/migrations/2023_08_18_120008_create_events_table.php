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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->text("desc");
            $table->string("image");
            $table->double('original_price', 15, 8);
            $table->double('price_after_discount', 15, 8);
            $table->integer('stock')->default(0);
            $table->string("tag");
            $table->foreignId('seller_id')->constrained();
            $table->foreignId('cate_id')->constrained();
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
