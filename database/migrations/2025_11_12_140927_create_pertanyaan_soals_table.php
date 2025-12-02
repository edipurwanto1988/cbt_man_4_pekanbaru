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
        Schema::create('pertanyaan_soals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->string('kode_soal', 50);
            $table->enum('jenis_soal', ['pilihan_ganda', 'esai', 'benar_salah']);
            $table->text('pertanyaan');
            $table->string('gambar_soal', 255)->nullable();
            $table->decimal('bobot_benar', 5, 2)->nullable();
            $table->decimal('bobot_salah', 5, 2)->nullable();
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pertanyaan_soals');
    }
};
