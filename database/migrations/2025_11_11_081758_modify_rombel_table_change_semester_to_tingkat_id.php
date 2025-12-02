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
            // Drop the semester column
            $table->dropColumn('semester');
            
            // Add tingkat_id column with foreign key relationship
            $table->foreignId('tingkat_id')->after('tahun_ajaran_id')->constrained('tingkat_kelas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rombel', function (Blueprint $table) {
            // Drop the foreign key and tingkat_id column
            $table->dropForeign(['tingkat_id']);
            $table->dropColumn('tingkat_id');
            
            // Add back the semester column
            $table->enum('semester', ['Ganjil', 'Genap'])->after('tahun_ajaran_id');
        });
    }
};
