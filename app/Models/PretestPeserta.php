<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PretestPeserta extends Model
{
    use HasFactory;

    protected $table = 'pretest_peserta';

    protected $fillable = [
        'session_id',
        'bank_soal_id',
        'nisn',
        'status',
    ];

    /**
     * Get the pretest session that owns the peserta.
     */
    public function pretestSession()
    {
        return $this->belongsTo(PretestSession::class, 'session_id');
    }

    /**
     * Get the bank soal that owns the peserta.
     */
    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    /**
     * Get the siswa that owns the peserta.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    /**
     * Get the pretest logs for the peserta.
     */
    public function pretestLogs()
    {
        return $this->hasMany(PretestLog::class, 'session_id', 'session_id')
            ->where('nisn', $this->nisn);
    }

    /**
     * Get the pretest hasil for the peserta.
     */
    public function pretestHasil()
    {
        return $this->hasOne(PretestHasil::class, 'session_id', 'session_id')
            ->where('nisn', $this->nisn);
    }
}