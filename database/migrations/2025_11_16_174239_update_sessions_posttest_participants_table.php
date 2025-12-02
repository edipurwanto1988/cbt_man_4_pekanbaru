<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key if it exists
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        
        Schema::table('sessions_posttest_participants', function (Blueprint $table) {
            // Check if session_id column exists before dropping it
            if (Schema::hasColumn('sessions_posttest_participants', 'session_id')) {
                $table->dropColumn('session_id');
            }
            
            // Add the bank_soal_id column if it doesn't exist
            if (!Schema::hasColumn('sessions_posttest_participants', 'bank_soal_id')) {
                $table->unsignedBigInteger('bank_soal_id')->after('id');
                $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            }
        });
        
        DB::statement("SET FOREIGN_KEY_CHECKS=1");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key if it exists
        DB::statement("SET FOREIGN_KEY_CHECKS=0");
        
        Schema::table('sessions_posttest_participants', function (Blueprint $table) {
            // Check if bank_soal_id column exists before dropping it
            if (Schema::hasColumn('sessions_posttest_participants', 'bank_soal_id')) {
                $table->dropColumn('bank_soal_id');
            }
            
            // Add back the session_id column if it doesn't exist
            if (!Schema::hasColumn('sessions_posttest_participants', 'session_id')) {
                $table->unsignedBigInteger('session_id')->after('id');
                $table->foreign('session_id')->references('id')->on('sessions_posttest')->onDelete('cascade');
            }
        });
        
        DB::statement("SET FOREIGN_KEY_CHECKS=1");
    }
};