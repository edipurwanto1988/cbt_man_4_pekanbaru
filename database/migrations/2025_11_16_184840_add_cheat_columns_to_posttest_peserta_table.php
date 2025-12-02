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
        Schema::table('posttest_peserta', function (Blueprint $table) {
            $table->enum('cheat_status', ['normal', 'blocked'])->default('normal')->after('status');
            $table->string('cheat_reason', 255)->nullable()->after('cheat_status');
            $table->unsignedBigInteger('cheat_unblock_by')->nullable()->after('cheat_reason');
            $table->dateTime('cheat_unblock_at')->nullable()->after('cheat_unblock_by');
            
            // Add foreign key constraint for cheat_unblock_by
            $table->foreign('cheat_unblock_by')->references('id_guru')->on('guru')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posttest_peserta', function (Blueprint $table) {
            $table->dropForeign(['cheat_unblock_by']);
            $table->dropColumn(['cheat_status', 'cheat_reason', 'cheat_unblock_by', 'cheat_unblock_at']);
        });
    }
};