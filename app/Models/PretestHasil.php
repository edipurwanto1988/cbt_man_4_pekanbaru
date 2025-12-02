<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PretestHasil extends Model
{
    use HasFactory;

    protected $table = 'pretest_hasil';

    protected $fillable = [
        'session_id',
        'bank_soal_id',
        'nisn',
        'total_benar',
        'total_salah',
        'total_poin',
        'total_waktu_respon',
        'peringkat',
    ];

    protected $casts = [
        'total_benar' => 'integer',
        'total_salah' => 'integer',
        'total_poin' => 'decimal:2',
        'total_waktu_respon' => 'integer',
        'peringkat' => 'integer',
    ];

    /**
     * Get the pretest session that owns the hasil.
     */
    public function pretestSession()
    {
        return $this->belongsTo(PretestSession::class, 'session_id');
    }

    /**
     * Get the bank soal that owns the hasil.
     */
    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    /**
     * Get the siswa that owns the hasil.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}