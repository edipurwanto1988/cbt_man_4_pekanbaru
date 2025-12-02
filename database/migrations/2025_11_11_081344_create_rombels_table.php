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
        Schema::create('rombels', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tingkat kelas
            $table->foreignId('tingkat_id')->constrained('tingkat_kelas');

            // Huruf paralel kelas A/B/C
            $table->string('kode', 5); // A, B, C, D
            $table->string('nama_rombel'); // XA, XB, XI IPA 1

            // Tahun ajaran
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran');

            // ✅ Relasi wali kelas → tabel guru (id_guru)
            $table->unsignedBigInteger('wali_kelas_id')->nullable();
            $table->foreign('wali_kelas_id')
                  ->references('id_guru')
                  ->on('guru')
                  ->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rombels');
    }
};
