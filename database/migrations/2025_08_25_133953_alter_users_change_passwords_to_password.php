<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ubah kolom username menjadi email
            $table->renameColumn('passwords', 'password');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Balik lagi ke username jika rollback
            $table->renameColumn('passwords', 'password');

        });
    }
};
