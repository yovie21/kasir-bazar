<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sales_item', function (Blueprint $table) {
            // tambahkan kolom uomId
            $table->unsignedBigInteger('uomId')->after('product_id')->nullable();

            // jika tabel uoms punya PK = id atau uomId, sesuaikan di sini
            $table->foreign('uomId')->references('uomId')->on('uoms')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('sales_item', function (Blueprint $table) {
            $table->dropForeign(['uomId']);
            $table->dropColumn('uomId');
        });
    }
};
