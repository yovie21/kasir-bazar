<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_uom_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('uom_id');
            $table->foreign('uom_id')->references('uomId')->on('uoms');
            $table->integer('price_cents');
            $table->decimal('konv_to_base', 10, 2)->default(1);
            $table->boolean('is_base')->default(false);
            $table->timestamps();
            
            $table->unique(['product_id', 'uom_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_uom_prices');
    }
};