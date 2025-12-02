<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Siswa;
use App\Models\BankSoal;
use App\Models\MataPelajaran;
use App\Models\Rombel;
use App\Models\RombelDetail;
use App\Models\BankSoalRombel;
use App\Models\Guru;

class SampleExamDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create sample guru
        $guru = Guru::first();
        if (!$guru) {
            $guru = Guru::create([
                'nama_guru' => 'Budi Santoso',
                'nik' => '123456789',
                'email' => 'budi@example.com',
                'password' => Hash::make('password123'),
                'role' => 'guru'
            ]);
        }

        // Get or create sample mata pelajaran
        $mapel = MataPelajaran::firstOrCreate([
            'nama_mapel' => 'Matematika'
        ]);

        // Get or create sample rombel
        $rombel = Rombel::firstOrCreate([
            'nama_rombel' => 'XII-IPA-1'
        ], [
            'tahun_ajaran_id' => 1,
            'kode_kelas' => 'A',
            'wali_kelas_id' => $guru->id_guru,
            'tingkat_id' => 3
        ]);

        // Assign student to rombel
        RombelDetail::create([
            'rombel_id' => $rombel->id,
            'nisn' => '1234567890'
        ]);

        // Create sample bank soal
        $bankSoal = BankSoal::firstOrCreate([
            'kode_bank' => 'MATH-PRETEST-001'
        ], [
            'tahun_ajaran_id' => 1,
            'type_test' => 'Pretest',
            'mapel_id' => $mapel->id,
            'created_by' => $guru->id_guru,
            'pengawas_id' => $guru->id_guru,
            'nama_bank' => 'Pretest Matematika XII IPA 1',
            'tanggal_mulai' => now()->subHour(),
            'tanggal_selesai' => now()->addHours(2),
            'durasi_menit' => 60,
            'bobot_benar_default' => 1.0,
            'bobot_salah_default' => 0.0,
            'status' => 'Aktif'
        ]);

        // Assign bank soal to rombel (check if already exists)
        $existingAssignment = BankSoalRombel::where('bank_soal_id', $bankSoal->id)
            ->where('rombel_id', $rombel->id)
            ->first();
            
        if (!$existingAssignment) {
            BankSoalRombel::create([
                'bank_soal_id' => $bankSoal->id,
                'rombel_id' => $rombel->id
            ]);
        }

        $this->command->info('Sample exam data created successfully!');
        $this->command->info('Student NISN: 1234567890');
        $this->command->info('Password: password123');
        $this->command->info('Exam Code: MATH-PRETEST-001');
    }
}