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
        Schema::create('pretest_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->string('jawaban_nisn', 20);
            $table->unsignedBigInteger('pertanyaan_id');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_jawab');
            $table->integer('durasi_detik')->storedAs('TIMESTAMPDIFF(SECOND, waktu_mulai, waktu_jawab)');
            $table->decimal('skor_kecepatan', 6, 2)->default(0.00);
            $table->decimal('skor_final', 6, 2)->default(0.00);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('bank_soal_id', 'fk_pretestlog_banksoal')
                  ->references('id')->on('bank_soals')
                  ->onDelete('cascade');
                  
            $table->foreign('pertanyaan_id', 'fk_pretestlog_pertanyaan')
                  ->references('id')->on('pertanyaan_soals')
                  ->onDelete('cascade');
                  
            $table->foreign('jawaban_nisn', 'fk_pretestlog_siswa')
                  ->references('nisn')->on('siswa')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pretest_logs');
    }
};