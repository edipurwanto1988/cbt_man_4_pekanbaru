<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosttestLog extends Model
{
    use HasFactory;

    protected $table = 'posttest_log';

    protected $fillable = [
        'nisn',
        'bank_soal_id',
        'pertanyaan_id',
        'jawaban_pilihan',
        'jawaban_benar_salah',
        'jawaban_esai',
        'skor',
        'is_benar',
        'durasi_detik',
    ];

    protected $casts = [
        'jawaban_benar_salah' => 'integer',
        'skor' => 'float',
        'is_benar' => 'integer',
        'durasi_detik' => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    public function pertanyaan()
    {
        return $this->belongsTo(PertanyaanSoal::class, 'pertanyaan_id');
    }
}