<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;

class SiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $siswaData = [
            [
                'nisn' => '1234567890',
                'nama_siswa' => 'Ahmad Rizki',
                'jenis_kelamin' => 'L',
                'password' => Hash::make('password123'),
            ],
            [
                'nisn' => '2345678901',
                'nama_siswa' => 'Siti Nurhaliza',
                'jenis_kelamin' => 'P',
                'password' => Hash::make('password123'),
            ],
            [
                'nisn' => '3456789012',
                'nama_siswa' => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'password' => Hash::make('password123'),
            ],
            [
                'nisn' => '4567890123',
                'nama_siswa' => 'Dewi Lestari',
                'jenis_kelamin' => 'P',
                'password' => Hash::make('password123'),
            ],
            [
                'nisn' => '5678901234',
                'nama_siswa' => 'Rudi Hermawan',
                'jenis_kelamin' => 'L',
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($siswaData as $siswa) {
            Siswa::create($siswa);
        }
    }
}