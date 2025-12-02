<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PertanyaanSoal extends Model
{
    use HasFactory;

    protected $table = 'pertanyaan_soals';

    protected $fillable = [
        'bank_soal_id',
        'jenis_soal',
        'pertanyaan',
        'gambar_soal',
        'bobot_benar',
        'bobot_salah',
    ];

    protected $casts = [
        'bobot_benar' => 'decimal:2',
        'bobot_salah' => 'decimal:2',
    ];

    /**
     * Get the bank soal that owns the pertanyaan soal.
     */
    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    /**
     * Get the jawaban soals for the pertanyaan soal.
     */
    public function jawabanSoals()
    {
        return $this->hasMany(JawabanSoal::class, 'pertanyaan_id');
    }

    /**
     * Get the pretest logs for the pertanyaan soal.
     */
    public function pretestLogs()
    {
        return $this->hasMany(PretestLog::class, 'pertanyaan_id');
    }
}