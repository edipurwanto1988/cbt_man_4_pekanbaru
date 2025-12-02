<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PretestLog extends Model
{
    use HasFactory;

    protected $table = 'pretest_log';

    protected $fillable = [
        'session_id',
        'bank_soal_id',
        'nisn',
        'pertanyaan_id',
        'jawaban_id',
        'benar',
        'waktu_respon',
        'poin',
    ];

    protected $casts = [
        'benar' => 'boolean',
        'waktu_respon' => 'integer',
        'poin' => 'decimal:2',
    ];

    /**
     * Get the pretest session that owns the log.
     */
    public function pretestSession()
    {
        return $this->belongsTo(PretestSession::class, 'session_id');
    }

    /**
     * Get the bank soal that owns the log.
     */
    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    /**
     * Get the siswa that owns the log.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    /**
     * Get the pertanyaan soal that owns the log.
     */
    public function pertanyaanSoal()
    {
        return $this->belongsTo(PertanyaanSoal::class);
    }

    /**
     * Get the jawaban soal that owns the log.
     */
    public function jawabanSoal()
    {
        return $this->belongsTo(JawabanSoal::class);
    }
}