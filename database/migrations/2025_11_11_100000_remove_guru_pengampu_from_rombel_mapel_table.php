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
        Schema::table('rombel_mapel', function (Blueprint $table) {
            $table->dropForeign(['guru_pengampu']);
            $table->dropColumn('guru_pengampu');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rombel_mapel', function (Blueprint $table) {
            $table->foreignId('guru_pengampu')->constrained('guru', 'id_guru');
        });
    }
};