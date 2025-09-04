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
        Schema::table('product_uom_prices', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('is_base');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_uom_prices', function (Blueprint $table) {
            $table->dropColumn('stock');
        });
    }
};
