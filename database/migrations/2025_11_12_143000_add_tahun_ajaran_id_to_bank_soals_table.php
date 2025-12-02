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
        Schema::table('bank_soals', function (Blueprint $table) {
            // Add tahun_ajaran_id column after kode_bank
            $table->foreignId('tahun_ajaran_id')->after('kode_bank')->constrained('tahun_ajaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bank_soals', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['tahun_ajaran_id']);
            
            // Then drop the column
            $table->dropColumn('tahun_ajaran_id');
        });
    }
};