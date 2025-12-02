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
        Schema::create('jawaban_soals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('pertanyaan_id');
            $table->char('opsi', 1)->nullable();
            $table->text('isi_jawaban')->nullable();
            $table->string('gambar_jawaban', 255)->nullable();
            $table->boolean('is_benar')->default(0);
            $table->timestamps();
            
            // Add foreign key constraint
            $table->foreign('pertanyaan_id')->references('id')->on('pertanyaan_soals')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_soals');
    }
};
