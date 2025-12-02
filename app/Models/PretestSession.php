<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PretestSession extends Model
{
    use HasFactory;

    protected $table = 'pretest_session';

    protected $fillable = [
        'bank_soal_id',
        'guru_id',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Ensure only one pretest session per bank_soal
        static::creating(function ($pretestSession) {
            // Check if there's already an active session for this bank_soal
            $existingSession = static::where('bank_soal_id', $pretestSession->bank_soal_id)
                ->whereIn('status', ['waiting', 'running'])
                ->first();
            
            if ($existingSession) {
                throw new \Exception("Sudah ada sesi pretest aktif untuk bank soal ini.");
            }
        });
    }

    /**
     * Get the bank soal that owns the pretest session.
     */
    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    /**
     * Get the guru who created the pretest session.
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class, 'guru_id', 'id_guru');
    }

    /**
     * Get the pesertas for the pretest session.
     */
    public function pesertas()
    {
        return $this->hasMany(PretestPeserta::class, 'session_id');
    }

    /**
     * Get the soal timers for the pretest session.
     */
    public function soalTimers()
    {
        return $this->hasMany(PretestSoalTimer::class, 'session_id');
    }

    /**
     * Get the logs for the pretest session.
     */
    public function logs()
    {
        return $this->hasMany(PretestLog::class, 'session_id');
    }

    /**
     * Get the hasil for the pretest session.
     */
    public function hasil()
    {
        return $this->hasMany(PretestHasil::class, 'session_id');
    }
}
