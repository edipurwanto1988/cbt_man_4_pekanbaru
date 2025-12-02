<?php

namespace App\Imports;

use App\Models\Rombel;
use App\Models\TahunAjaran;
use App\Models\TingkatKelas;
use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class RombelImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    public $errors = [];
    public $successCount = 0;
    public $failCount = 0;
    public $previewData = [];
    public $isPreview = false;

    public function __construct($isPreview = false)
    {
        $this->isPreview = $isPreview;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Prepare data for validation
        $preparedRow = $this->prepareForValidation($row, $this->isPreview ? 0 : $this->successCount + $this->failCount + 1);
        
        Log::info('RombelImport::model called with row:', $preparedRow);
        Log::info('Is preview mode: ' . ($this->isPreview ? 'true' : 'false'));
        
        if ($this->isPreview) {
            $previewRow = [
                'tahun_ajaran_id' => $preparedRow['tahun_ajaran_id'] ?? '',
                'tingkat_id' => $preparedRow['tingkat_id'] ?? '',
                'kode_kelas' => $preparedRow['kode_kelas'] ?? '',
                'nama_rombel' => $preparedRow['nama_rombel'] ?? '',
                'wali_kelas_id' => $preparedRow['wali_kelas_id'] ?? '',
                'status' => 'pending'
            ];
            
            Log::info('Adding to preview data:', $previewRow);
            $this->previewData[] = $previewRow;
            return null;
        }

        try {
            Log::info('Creating rombel with data:', $preparedRow);
            
            $rombel = Rombel::create([
                'tahun_ajaran_id' => $preparedRow['tahun_ajaran_id'],
                'tingkat_id' => $preparedRow['tingkat_id'],
                'kode_kelas' => $preparedRow['kode_kelas'],
                'nama_rombel' => $preparedRow['nama_rombel'],
                'wali_kelas_id' => $preparedRow['wali_kelas_id'] ?? null,
            ]);

            Log::info('Rombel created successfully');
            $this->successCount++;
            return $rombel;
        } catch (\Exception $e) {
            Log::error('Error creating rombel: ' . $e->getMessage());
            $this->errors[] = [
                'row' => $preparedRow,
                'error' => $e->getMessage()
            ];
            $this->failCount++;
            return null;
        }
    }

    public function rules(): array
    {
        return [
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'tingkat_id' => 'required|exists:tingkat_kelas,id',
            'kode_kelas' => 'required|in:A,B,C,D',
            'nama_rombel' => 'required|string|max:255',
            'wali_kelas_id' => 'nullable|exists:guru,id_guru',
        ];
    }

    public function prepareForValidation($data, $index)
    {
        // Convert numeric IDs to string if needed
        if (isset($data['tahun_ajaran_id']) && is_numeric($data['tahun_ajaran_id'])) {
            $data['tahun_ajaran_id'] = (string)$data['tahun_ajaran_id'];
        }
        
        if (isset($data['tingkat_id']) && is_numeric($data['tingkat_id'])) {
            $data['tingkat_id'] = (string)$data['tingkat_id'];
        }
        
        if (isset($data['wali_kelas_id']) && is_numeric($data['wali_kelas_id'])) {
            $data['wali_kelas_id'] = (string)$data['wali_kelas_id'];
        }
        
        return $data;
    }

    public function customValidationMessages()
    {
        return [
            'tahun_ajaran_id.required' => 'Tahun ajaran wajib diisi',
            'tahun_ajaran_id.exists' => 'Tahun ajaran tidak ditemukan',
            'tingkat_id.required' => 'Tingkat kelas wajib diisi',
            'tingkat_id.exists' => 'Tingkat kelas tidak ditemukan',
            'kode_kelas.required' => 'Kode kelas wajib diisi',
            'kode_kelas.in' => 'Kode kelas harus A, B, C, atau D',
            'nama_rombel.required' => 'Nama rombel wajib diisi',
            'wali_kelas_id.exists' => 'Wali kelas tidak ditemukan',
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = [
                'row' => $failure->row(),
                'attribute' => $failure->attribute(),
                'value' => $failure->values(),
                'errors' => $failure->errors()
            ];
            $this->failCount++;
        }
    }

    public function onError(\Throwable $e)
    {
        $this->errors[] = [
            'error' => $e->getMessage()
        ];
        $this->failCount++;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }

    public function getFailCount()
    {
        return $this->failCount;
    }

    public function getPreviewData()
    {
        return $this->previewData;
    }
}