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
        Schema::table('rombel', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['wali_kelas']);
            
            // Rename the column from wali_kelas to wali_kelas_id
            $table->renameColumn('wali_kelas', 'wali_kelas_id');
            
            // Re-add the foreign key constraint with the new column name
            $table->foreign('wali_kelas_id')->references('id_guru')->on('guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rombel', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['wali_kelas_id']);
            
            // Rename the column back from wali_kelas_id to wali_kelas
            $table->renameColumn('wali_kelas_id', 'wali_kelas');
            
            // Re-add the foreign key constraint with the original column name
            $table->foreign('wali_kelas')->references('id_guru')->on('guru');
        });
    }
};
