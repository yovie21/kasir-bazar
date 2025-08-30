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
        Schema::create('sales', function (Blueprint $table) {
             $table->id();
            $table->unsignedBigInteger('cashier_id')->index();
            $table->string('no_trans')->unique();
            $table->integer('subtotal_cents');
            $table->integer('discount_cents')->default(0);
            $table->integer('total_cents');
            $table->integer('paid_cents');
            $table->integer('change_cents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
