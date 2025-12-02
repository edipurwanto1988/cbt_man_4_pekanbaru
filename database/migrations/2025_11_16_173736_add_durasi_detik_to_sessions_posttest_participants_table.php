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
        Schema::table('sessions_posttest_participants', function (Blueprint $table) {
            $table->integer('durasi_detik')->default(0)->after('skor_total');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sessions_posttest_participants', function (Blueprint $table) {
            $table->dropColumn('durasi_detik');
        });
    }
};