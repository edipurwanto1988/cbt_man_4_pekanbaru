<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tingkat_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10); // X, XI, XII
            $table->string('nama', 50); // contoh: Kelas 10, Kelas 11, Kelas 12
            $table->timestamps();
        });

        // Insert default data
        DB::table('tingkat_kelas')->insert([
            ['kode' => 'X',   'nama' => 'Kelas 10', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'XI',  'nama' => 'Kelas 11', 'created_at' => now(), 'updated_at' => now()],
            ['kode' => 'XII', 'nama' => 'Kelas 12', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tingkat_kelas');
    }
};
