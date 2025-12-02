<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosttestCheatLog extends Model
{
    use HasFactory;

    protected $table = 'posttest_cheat_log';

    protected $fillable = [
        'peserta_id',
        'bank_soal_id',
        'nisn',
        'jenis_kecurangan',
        'deskripsi',
        'timestamp',
        'is_blocked',
        'is_unblocked',
        'unblock_by',
        'unblock_at',
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'is_blocked' => 'boolean',
        'is_unblocked' => 'boolean',
        'unblock_at' => 'datetime',
    ];

    /**
     * Get the peserta that owns the cheat log.
     */
    public function peserta()
    {
        return $this->belongsTo(SessionsPosttestParticipant::class, 'peserta_id');
    }

    /**
     * Get the bank soal that owns the cheat log.
     */
    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    /**
     * Get the siswa that owns the cheat log.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn');
    }

    /**
     * Get the guru who unblocked the cheat log.
     */
    public function unblocker()
    {
        return $this->belongsTo(Guru::class, 'unblock_by');
    }
}