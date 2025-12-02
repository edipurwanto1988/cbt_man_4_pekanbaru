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
        Schema::create('posttest_cheat_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('peserta_id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->char('nisn', 16);
            $table->enum('jenis_kecurangan', ['minimize', 'tab_change', 'focus_loss', 'other']);
            $table->text('deskripsi')->nullable();
            $table->dateTime('timestamp')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->boolean('is_blocked')->default(0);
            $table->boolean('is_unblocked')->default(0);
            $table->unsignedBigInteger('unblock_by')->nullable();
            $table->dateTime('unblock_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('peserta_id')->references('id')->on('posttest_peserta')->onDelete('cascade');
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            $table->foreign('unblock_by')->references('id_guru')->on('guru')->onDelete('set null');
            
            // Indexes
            $table->index(['peserta_id', 'bank_soal_id']);
            $table->index('jenis_kecurangan');
            $table->index('timestamp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posttest_cheat_log');
    }
};