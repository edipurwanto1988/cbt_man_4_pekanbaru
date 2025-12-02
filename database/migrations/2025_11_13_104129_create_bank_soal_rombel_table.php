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
        Schema::create('bank_soal_rombel', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_soal_id');
            $table->unsignedBigInteger('rombel_id');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('rombel_id')->references('id')->on('rombel')->onDelete('cascade');
            
            // Unique constraint to prevent duplicate assignments
            $table->unique(['bank_soal_id', 'rombel_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_soal_rombel');
    }
};
