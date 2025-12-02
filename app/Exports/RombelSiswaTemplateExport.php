<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class RombelSiswaTemplateExport implements FromCollection, WithHeadings, WithTitle
{
    protected $rombel;

    public function __construct($rombel)
    {
        $this->rombel = $rombel;
    }

    public function collection()
    {
        return collect([
            [
                'nisn' => 'Contoh: 1234567890',
            ]
        ]);
    }

    public function headings(): array
    {
        return [
            'NISN',
        ];
    }

    public function title(): string
    {
        return 'Template Import Siswa Rombel ' . $this->rombel->nama_rombel;
    }
}