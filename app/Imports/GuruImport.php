<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class GuruImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
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
        
        Log::info('GuruImport::model called with row:', $preparedRow);
        Log::info('Is preview mode: ' . ($this->isPreview ? 'true' : 'false'));
        
        if ($this->isPreview) {
            $previewRow = [
                'nik' => $preparedRow['nik'] ?? '',
                'nama_guru' => $preparedRow['nama_guru'] ?? '',
                'email' => $preparedRow['email'] ?? '',
                'password' => $preparedRow['password'] ?? '',
                'status' => 'pending'
            ];
            
            Log::info('Adding to preview data:', $previewRow);
            $this->previewData[] = $previewRow;
            return null;
        }

        try {
            Log::info('Creating guru with data:', $preparedRow);
            
            $guru = Guru::create([
                'nik' => $preparedRow['nik'],
                'nama_guru' => $preparedRow['nama_guru'],
                'email' => $preparedRow['email'] ?? null,
                'password' => Hash::make($preparedRow['password'] ?? 'password123'),
            ]);

            Log::info('Guru created successfully');
            $this->successCount++;
            return $guru;
        } catch (\Exception $e) {
            Log::error('Error creating guru: ' . $e->getMessage());
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
            'nik' => 'required|string|max:20|unique:guru,nik',
            'nama_guru' => 'required|string|max:255',
            'email' => 'nullable|email|max:255|unique:guru,email',
            'password' => 'required|string|min:6',
        ];
    }

    public function prepareForValidation($data, $index)
    {
        // Convert numeric NIK to string if needed
        if (isset($data['nik']) && is_numeric($data['nik'])) {
            $data['nik'] = (string)$data['nik'];
        }
        
        return $data;
    }

    public function customValidationMessages()
    {
        return [
            'nik.required' => 'NIK wajib diisi',
            'nik.unique' => 'NIK sudah terdaftar',
            'nama_guru.required' => 'Nama guru wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 6 karakter',
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