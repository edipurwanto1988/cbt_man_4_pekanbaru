<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionsPretestParticipant extends Model
{
    use HasFactory;

    protected $table = 'pretest_peserta';

    protected $fillable = [
        'session_id',
        'bank_soal_id',
        'nisn',
        'status',
    ];

    protected $casts = [
        'skor_total' => 'decimal:2',
    ];

    /**
     * Get the pretest session that owns the participant.
     */
    public function pretestSession()
    {
        return $this->belongsTo(PretestSession::class, 'session_id');
    }

    /**
     * Get the student that owns the participant.
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
