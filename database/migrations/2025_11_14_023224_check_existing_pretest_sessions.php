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
        // Check if pretest_sessions table exists and has data
        if (Schema::hasTable('pretest_sessions')) {
            $hasData = DB::table('pretest_sessions')->exists();
        } else {
            $hasData = false;
        }
        
        if ($hasData) {
            // Table exists and has data, do nothing
            return;
        }
        
        // If table doesn't exist, create it
        Schema::create('pretest_sessions', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->string('kode_sesi', 10);
            $table->string('qr_image', 255)->nullable();
            $table->char('created_by', 16);
            $table->enum('status', ['open', 'running', 'finished'])->default('open');
            $table->unsignedBigInteger('soal_aktif_id')->nullable();
            $table->integer('step_soal')->default(0);
            $table->integer('total_soal')->default(0);
            $table->dateTime('mulai_at')->nullable();
            $table->dateTime('selesai_at')->nullable();
            $table->timestamps();
            
            // Note: Foreign keys removed temporarily to avoid constraint issues
            // Can be added later with separate migrations if needed
            
            // Indexes
            $table->index('bank_soal_id');
            $table->index('kode_sesi');
            $table->index('created_by');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pretest_sessions');
    }
};
