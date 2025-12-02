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
        Schema::create('bank_soals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_bank', 50)->unique();
            $table->unsignedBigInteger('mapel_id');
            $table->char('created_by', 16);
            $table->char('pengawas_id', 16)->nullable();
            $table->string('nama_bank', 255);
            $table->dateTime('tanggal_mulai')->nullable();
            $table->dateTime('tanggal_selesai')->nullable();
            $table->integer('durasi_menit')->default(60);
            $table->decimal('bobot_benar_default', 5, 2)->default(1.00);
            $table->decimal('bobot_salah_default', 5, 2)->default(0.00);
            $table->enum('status', ['draft', 'aktif', 'selesai'])->default('draft');
            $table->timestamps();
            
            // Add foreign key constraints if needed
            $table->foreign('mapel_id')->references('id')->on('mata_pelajaran');
            $table->foreign('created_by')->references('id_guru')->on('guru');
            $table->foreign('pengawas_id')->references('id_guru')->on('guru');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soals');
    }
};
