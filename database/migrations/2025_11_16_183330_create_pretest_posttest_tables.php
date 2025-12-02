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
        // 1. pretest_session
        if (!Schema::hasTable('pretest_session')) {
            Schema::create('pretest_session', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->unsignedBigInteger('guru_id');
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();
            $table->enum('status', ['waiting', 'running', 'finished'])->default('waiting');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('guru_id')->references('id_guru')->on('guru')->onDelete('cascade');
            });
        }

        // 2. pretest_peserta
        if (!Schema::hasTable('pretest_peserta')) {
            Schema::create('pretest_peserta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->char('nisn', 16);
            $table->enum('status', ['waiting', 'active', 'finished'])->default('waiting');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('session_id')->references('id')->on('pretest_session')->onDelete('cascade');
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            
            // Indexes
            $table->index(['session_id', 'nisn']);
            });
        }

        // 3. pretest_soal_timer
        if (!Schema::hasTable('pretest_soal_timer')) {
            Schema::create('pretest_soal_timer', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('pertanyaan_id');
            $table->integer('urutan_soal');
            $table->dateTime('waktu_mulai');
            $table->dateTime('waktu_berakhir');
            $table->enum('status', ['waiting', 'running', 'finished'])->default('waiting');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('session_id')->references('id')->on('pretest_session')->onDelete('cascade');
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan_soals')->onDelete('cascade');
            
            // Indexes
            $table->index(['session_id', 'urutan_soal']);
            });
        }

        // 4. pretest_log
        if (!Schema::hasTable('pretest_log')) {
            Schema::create('pretest_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->char('nisn', 16);
            $table->unsignedBigInteger('pertanyaan_id');
            $table->unsignedBigInteger('jawaban_id')->nullable();
            $table->boolean('benar')->default(0);
            $table->integer('waktu_respon');
            $table->decimal('poin', 10, 2)->default(0);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('session_id')->references('id')->on('pretest_session')->onDelete('cascade');
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan_soals')->onDelete('cascade');
            $table->foreign('jawaban_id')->references('id')->on('jawaban_soals')->onDelete('set null');
            
            // Indexes
            $table->index(['session_id', 'nisn', 'pertanyaan_id']);
            });
        }

        // 5. pretest_hasil
        if (!Schema::hasTable('pretest_hasil')) {
            Schema::create('pretest_hasil', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->char('nisn', 16);
            $table->integer('total_benar')->default(0);
            $table->integer('total_salah')->default(0);
            $table->decimal('total_poin', 10, 2)->default(0);
            $table->integer('total_waktu_respon')->default(0);
            $table->integer('peringkat')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('session_id')->references('id')->on('pretest_session')->onDelete('cascade');
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            
            // Indexes
            $table->index(['session_id', 'nisn']);
            $table->index('peringkat');
            });
        }

        // 6. posttest_peserta
        if (!Schema::hasTable('posttest_peserta')) {
            Schema::create('posttest_peserta', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->char('nisn', 16);
            $table->dateTime('start_time');
            $table->dateTime('end_time')->nullable();
            $table->integer('sisa_detik')->default(0);
            $table->enum('status', ['ongoing', 'finished', 'forfeited'])->default('ongoing');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            
            // Indexes
            $table->index(['bank_soal_id', 'nisn']);
            });
        }

        // 7. posttest_log
        if (!Schema::hasTable('posttest_log')) {
            Schema::create('posttest_log', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('peserta_id');
            $table->unsignedBigInteger('pertanyaan_id');
            $table->unsignedBigInteger('jawaban_id')->nullable();
            $table->boolean('is_ragu')->default(0);
            $table->timestamp('last_update')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('peserta_id')->references('id')->on('posttest_peserta')->onDelete('cascade');
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan_soals')->onDelete('cascade');
            $table->foreign('jawaban_id')->references('id')->on('jawaban_soals')->onDelete('set null');
            
            // Indexes
            $table->index(['peserta_id', 'pertanyaan_id']);
            });
        }

        // 8. posttest_hasil
        if (!Schema::hasTable('posttest_hasil')) {
            Schema::create('posttest_hasil', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->char('nisn', 16);
            $table->integer('total_benar')->default(0);
            $table->integer('total_salah')->default(0);
            $table->integer('total_kosong')->default(0);
            $table->decimal('nilai_akhir', 6, 2)->default(0);
            $table->integer('waktu_pengerjaan')->default(0);
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            
            // Indexes
            $table->index(['bank_soal_id', 'nisn']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in reverse order to handle foreign key constraints
        Schema::dropIfExists('posttest_hasil');
        Schema::dropIfExists('posttest_log');
        Schema::dropIfExists('posttest_peserta');
        Schema::dropIfExists('pretest_hasil');
        Schema::dropIfExists('pretest_log');
        Schema::dropIfExists('pretest_soal_timer');
        Schema::dropIfExists('pretest_peserta');
        Schema::dropIfExists('pretest_session');
    }
};