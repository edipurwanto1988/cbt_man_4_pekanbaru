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
        Schema::dropIfExists('posttest_log');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't recreate the table as it was intentionally dropped
    }
};