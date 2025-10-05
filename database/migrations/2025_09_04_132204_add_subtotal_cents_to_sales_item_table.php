<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_item', function (Blueprint $table) {
            $table->bigInteger('subtotal_cents')->default(0)->after('price_cents');
        });
    }

    public function down(): void
    {
        Schema::table('sales_item', function (Blueprint $table) {
            $table->dropColumn('subtotal_cents');
        });
    }
};
