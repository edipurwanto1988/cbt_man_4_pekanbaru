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
        // Revert the rename operation - keep the sessions table as is
        // Schema::rename('sessions', 'sessions_pretest');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::rename('sessions_pretest', 'sessions');
    }
};