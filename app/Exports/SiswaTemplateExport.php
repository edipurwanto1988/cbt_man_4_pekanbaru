<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SiswaTemplateExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        Log::info('SiswaTemplateExport: collection() method called');
        
        $data = new Collection([
            ['1234567890', 'Contoh Siswa', 'L', 'contoh@email.com', 'password123'],
            ['0987654321', 'Contoh Siswi', 'P', 'siswi@email.com', 'password123'],
        ]);
        
        Log::info('SiswaTemplateExport: sample data created with ' . $data->count() . ' rows');
        
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        Log::info('SiswaTemplateExport: headings() method called');
        
        $headings = [
            'nisn',
            'nama_siswa',
            'jenis_kelamin',
            'email',
            'password',
        ];
        
        Log::info('SiswaTemplateExport: headings created: ' . implode(', ', $headings));
        
        return $headings;
    }
}
