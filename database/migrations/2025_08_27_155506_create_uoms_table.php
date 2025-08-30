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
        Schema::create('uoms', function (Blueprint $table) {
            $table->id('uomId'); // primary key
            $table->string('uomKode')->unique(); // misal: PCS, KG, DUS
            $table->string('uomName'); // misal: Pieces, Kilogram, Dus
            $table->integer('konvPcs')->default(1); // konversi ke pcs
            $table->timestamps();
        });

        // Relasi ke tabel products
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('uomId')->nullable()->after('id');
            $table->foreign('uomId')->references('uomId')->on('uoms')
                  ->onUpdate('cascade')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // hapus relasi dari products
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['uomId']);
            $table->dropColumn('uomId');
        });

        Schema::dropIfExists('uoms');
    }
};
