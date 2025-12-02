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
        Schema::create('posttest_cheat_notif', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('peserta_id');
            $table->unsignedBigInteger('bank_soal_id');
            $table->char('nisn', 16);
            $table->text('pesan');
            $table->enum('status', ['unread', 'read'])->default('unread');
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('peserta_id')->references('id')->on('posttest_peserta')->onDelete('cascade');
            $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            
            // Indexes
            $table->index(['peserta_id', 'bank_soal_id']);
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posttest_cheat_notif');
    }
};