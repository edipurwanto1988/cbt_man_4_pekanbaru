<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use App\Models\TahunAjaran;
use App\Models\TingkatKelas;
use App\Models\Guru;

class RombelTemplateExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        Log::info('RombelTemplateExport: collection() method called');
        
        // Get sample data for the template
        $tahunAjaran = TahunAjaran::first();
        $tingkatKelas = TingkatKelas::first();
        $guru = Guru::first();
        
        $data = new Collection([
            [
                'tahun_ajaran_id' => $tahunAjaran ? $tahunAjaran->id : '1',
                'tingkat_id' => $tingkatKelas ? $tingkatKelas->id : '1',
                'kode_kelas' => 'A',
                'nama_rombel' => 'Contoh Rombel',
                'wali_kelas_id' => $guru ? $guru->id_guru : '',
            ],
            [
                'tahun_ajaran_id' => $tahunAjaran ? $tahunAjaran->id : '1',
                'tingkat_id' => $tingkatKelas ? $tingkatKelas->id : '1',
                'kode_kelas' => 'B',
                'nama_rombel' => 'Contoh Rombel Lain',
                'wali_kelas_id' => $guru ? $guru->id_guru : '',
            ],
        ]);
        
        Log::info('RombelTemplateExport: sample data created with ' . $data->count() . ' rows');
        
        return $data;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        Log::info('RombelTemplateExport: headings() method called');
        
        $headings = [
            'tahun_ajaran_id',
            'tingkat_id',
            'kode_kelas',
            'nama_rombel',
            'wali_kelas_id',
        ];
        
        Log::info('RombelTemplateExport: headings created: ' . implode(', ', $headings));
        
        return $headings;
    }
}