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
        Schema::table('posttest_log', function (Blueprint $table) {
            // Add nisn column if it doesn't exist
            if (!Schema::hasColumn('posttest_log', 'nisn')) {
                $table->string('nisn', 20)->nullable()->after('id');
                
                // Add foreign key for nisn
                $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
                
                // Add index for nisn
                $table->index(['nisn', 'bank_soal_id']);
            }
            
            // Add bank_soal_id column if it doesn't exist
            if (!Schema::hasColumn('posttest_log', 'bank_soal_id')) {
                $table->unsignedBigInteger('bank_soal_id')->nullable()->after('nisn');
                
                // Add foreign key for bank_soal_id
                $table->foreign('bank_soal_id')->references('id')->on('bank_soals')->onDelete('cascade');
            }
            
            // Add other columns if they don't exist
            $columnsToAdd = [
                'pertanyaan_id' => ['type' => 'unsignedBigInteger', 'after' => 'bank_soal_id'],
                'jawaban_pilihan' => ['type' => 'char', 'length' => 1, 'nullable' => true, 'after' => 'pertanyaan_id'],
                'jawaban_benar_salah' => ['type' => 'tinyInteger', 'nullable' => true, 'after' => 'jawaban_pilihan'],
                'jawaban_esai' => ['type' => 'text', 'nullable' => true, 'after' => 'jawaban_benar_salah'],
                'skor' => ['type' => 'decimal', 'precision' => 5, 'scale' => 2, 'default' => 0, 'after' => 'jawaban_esai'],
                'is_benar' => ['type' => 'tinyInteger', 'nullable' => true, 'after' => 'skor'],
                'durasi_detik' => ['type' => 'integer', 'nullable' => true, 'after' => 'is_benar'],
            ];
            
            foreach ($columnsToAdd as $columnName => $columnOptions) {
                if (!Schema::hasColumn('posttest_log', $columnName)) {
                    if (isset($columnOptions['type'])) {
                        $type = $columnOptions['type'];
                        $column = $table->$type($columnName);
                        
                        if (isset($columnOptions['length'])) {
                            $column = $column->length($columnOptions['length']);
                        }
                        
                        if (isset($columnOptions['precision']) && isset($columnOptions['scale'])) {
                            $column = $column->precision($columnOptions['precision'])->scale($columnOptions['scale']);
                        }
                        
                        if (isset($columnOptions['default'])) {
                            $column = $column->default($columnOptions['default']);
                        }
                        
                        if (isset($columnOptions['nullable']) && $columnOptions['nullable']) {
                            $column = $column->nullable();
                        }
                        
                        if (isset($columnOptions['after'])) {
                            $column = $column->after($columnOptions['after']);
                        }
                    }
                }
            }
            
            // Drop peserta_id column if it exists
            if (Schema::hasColumn('posttest_log', 'peserta_id')) {
                // Drop foreign key constraint first
                $table->dropForeign(['peserta_id']);
                // Then drop the column
                $table->dropColumn('peserta_id');
            }
            
            // Drop other columns that are no longer needed
            $columnsToDrop = ['jawaban_id', 'is_ragu', 'last_update'];
            foreach ($columnsToDrop as $columnName) {
                if (Schema::hasColumn('posttest_log', $columnName)) {
                    if ($columnName === 'jawaban_id') {
                        // Drop foreign key constraint first
                        $table->dropForeign(['jawaban_id']);
                    }
                    $table->dropColumn($columnName);
                }
            }
            
            // Add indexes for the new columns
            if (!Schema::hasIndex('posttest_log', ['pertanyaan_id', 'bank_soal_id'])) {
                $table->index(['pertanyaan_id', 'bank_soal_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('posttest_log', function (Blueprint $table) {
            // Add back peserta_id column
            if (!Schema::hasColumn('posttest_log', 'peserta_id')) {
                $table->unsignedBigInteger('peserta_id')->nullable()->after('id');
                $table->foreign('peserta_id')->references('id')->on('posttest_peserta')->onDelete('cascade');
                $table->index(['peserta_id', 'pertanyaan_id']);
            }
            
            // Add back other columns
            $columnsToAdd = [
                'jawaban_id' => ['type' => 'unsignedBigInteger', 'nullable' => true, 'after' => 'pertanyaan_id'],
                'is_ragu' => ['type' => 'boolean', 'default' => 0, 'after' => 'jawaban_id'],
                'last_update' => ['type' => 'timestamp', 'nullable' => true, 'after' => 'is_ragu'],
            ];
            
            foreach ($columnsToAdd as $columnName => $columnOptions) {
                if (!Schema::hasColumn('posttest_log', $columnName)) {
                    if (isset($columnOptions['type'])) {
                        $type = $columnOptions['type'];
                        $column = $table->$type($columnName);
                        
                        if (isset($columnOptions['default'])) {
                            $column = $column->default($columnOptions['default']);
                        }
                        
                        if (isset($columnOptions['nullable']) && $columnOptions['nullable']) {
                            $column = $column->nullable();
                        }
                        
                        if (isset($columnOptions['after'])) {
                            $column = $column->after($columnOptions['after']);
                        }
                    }
                }
            }
            
            // Add foreign key for jawaban_id
            if (Schema::hasColumn('posttest_log', 'jawaban_id')) {
                $table->foreign('jawaban_id')->references('id')->on('jawaban_soals')->onDelete('set null');
            }
            
            // Drop nisn column if it exists
            if (Schema::hasColumn('posttest_log', 'nisn')) {
                $table->dropForeign(['nisn']);
                $table->dropColumn('nisn');
            }
            
            // Drop bank_soal_id column if it exists
            if (Schema::hasColumn('posttest_log', 'bank_soal_id')) {
                $table->dropForeign(['bank_soal_id']);
                $table->dropColumn('bank_soal_id');
            }
            
            // Drop other columns that were added
            $columnsToDrop = ['jawaban_pilihan', 'jawaban_benar_salah', 'jawaban_esai', 'skor', 'is_benar', 'durasi_detik'];
            foreach ($columnsToDrop as $columnName) {
                if (Schema::hasColumn('posttest_log', $columnName)) {
                    $table->dropColumn($columnName);
                }
            }
        });
    }
};