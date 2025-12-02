<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\RombelDetail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class RombelSiswaImport implements ToCollection, WithHeadingRow
{
    protected $rombelId;
    protected $errors = [];
    protected $validCount = 0;

    public function __construct($rombelId)
    {
        $this->rombelId = $rombelId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = (int)$index + 2; // Excel row numbers start from 1, plus header row
            
            try {
                // Skip empty rows
                if (empty($row['nisn'])) {
                    continue;
                }

                // Clean NISN
                $nisn = trim($row['nisn']);
                
                // Check if NISN exists in siswa table
                $siswa = Siswa::where('nisn', $nisn)->first();
                
                if (!$siswa) {
                    $this->errors[] = "Baris {$rowNumber}: NISN {$nisn} tidak ditemukan di database master siswa";
                    continue;
                }

                // Check if siswa already exists in this rombel
                $existingRombelDetail = RombelDetail::where('rombel_id', $this->rombelId)
                    ->where('nisn', $nisn)
                    ->first();
                
                if ($existingRombelDetail) {
                    $this->errors[] = "Baris {$rowNumber}: Siswa dengan NISN {$nisn} sudah ada di rombel ini";
                    continue;
                }

                // If we get here, the data is valid
                $this->validCount++;
                
            } catch (\Exception $e) {
                $this->errors[] = "Baris {$rowNumber}: Error processing data - " . $e->getMessage();
                Log::error("Error processing row {$rowNumber}: " . $e->getMessage());
            }
        }
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getValidCount()
    {
        return $this->validCount;
    }

    public function processImport(Collection $rows)
    {
        $importedCount = 0;
        
        foreach ($rows as $index => $row) {
            $rowNumber = (int)$index + 2;
            
            try {
                // Skip empty rows
                if (empty($row['nisn'])) {
                    continue;
                }

                // Clean NISN
                $nisn = trim($row['nisn']);
                
                // Check if NISN exists in siswa table
                $siswa = Siswa::where('nisn', $nisn)->first();
                
                if (!$siswa) {
                    continue;
                }

                // Check if siswa already exists in this rombel
                $existingRombelDetail = RombelDetail::where('rombel_id', $this->rombelId)
                    ->where('nisn', $nisn)
                    ->first();
                
                if ($existingRombelDetail) {
                    continue;
                }

                // Create rombel detail
                RombelDetail::create([
                    'rombel_id' => $this->rombelId,
                    'nisn' => $nisn,
                ]);
                
                $importedCount++;
                
            } catch (\Exception $e) {
                Log::error("Error importing row {$rowNumber}: " . $e->getMessage());
            }
        }
        
        return $importedCount;
    }
}