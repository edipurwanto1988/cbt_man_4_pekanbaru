<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosttestPeserta extends Model
{
    use HasFactory;

    protected $table = 'posttest_peserta';

    protected $fillable = [
        'bank_soal_id',
        'nisn',
        'start_time',
        'end_time',
        'sisa_detik',
        'cheat_status',
        'cheat_reason',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'sisa_detik' => 'integer',
    ];

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
     * Get the posttest logs for the peserta.
     */
    public function posttestLogs()
    {
        return $this->hasMany(PosttestLog::class, 'nisn', 'nisn');
    }

    /**
     * Get the posttest hasil for the peserta.
     */
    public function posttestHasil()
    {
        return $this->hasOne(PosttestHasil::class, 'bank_soal_id', 'bank_soal_id')
            ->where('nisn', $this->nisn);
    }
}