<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class GuruTemplateExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        Log::info('GuruTemplateExport::collection() method called');
        
        $data = new Collection([
            ['1987654321', 'Contoh Guru', 'contoh@guru.com', 'password123'],
            ['1234567890', 'Contoh Guruwi', 'guruwi@email.com', 'password123'],
        ]);
        
        Log::info('Guru template data created with ' . $data->count() . ' rows');
        
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        Log::info('GuruTemplateExport::headings() method called');
        
        $headings = [
            'nik',
            'nama_guru',
            'email',
            'password',
        ];
        
        Log::info('Guru template headings created: ' . implode(', ', $headings));
        
        return $headings;
    }
}