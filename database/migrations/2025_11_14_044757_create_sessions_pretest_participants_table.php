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
        Schema::create('sessions_pretest_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('pretest_sessions');
            $table->string('nisn', 20);
            $table->string('avatar_url')->nullable();
            $table->decimal('skor_total', 8, 2)->default(0);
            $table->integer('rank')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions_pretest_participants');
    }
};
