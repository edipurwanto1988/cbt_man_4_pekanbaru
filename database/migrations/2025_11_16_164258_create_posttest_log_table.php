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
        Schema::create('posttest_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            // identitas siswa
            $table->string('nisn', 20)->nullable();        // FK ke siswa.nisn
            
            // identitas ujian & soal
            $table->unsignedBigInteger('bank_soal_id');
            $table->unsignedBigInteger('pertanyaan_id');
            
            // jenis jawaban siswa
            $table->char('jawaban_pilihan', 1)->nullable();          // A/B/C/D/E
            $table->tinyInteger('jawaban_benar_salah')->nullable();   // 1 = benar, 0 = salah
            $table->text('jawaban_esai')->nullable();                // jawaban esai bebas
            
            // hasil setelah diperiksa
            $table->decimal('skor', 5, 2)->default(0);           // nilai per nomor
            $table->tinyInteger('is_benar')->nullable();              // 1 benar, 0 salah (PG/BS)
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan_soals')->onDelete('cascade');
            
            // Indexes
            $table->index(['nisn', 'bank_soal_id']);
            $table->index(['pertanyaan_id', 'bank_soal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posttest_log');
    }
};