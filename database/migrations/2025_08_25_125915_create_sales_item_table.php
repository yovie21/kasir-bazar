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
        Schema::create('sales_item', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('sale_id')->index();
            $table->unsignedBigInteger('product_id')->index();
            $table->integer('qty');
            $table->integer('price_cents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_item');
    }
};
