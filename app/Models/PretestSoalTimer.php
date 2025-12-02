<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PretestSoalTimer extends Model
{
    use HasFactory;

    protected $table = 'pretest_soal_timer';

    protected $fillable = [
        'session_id',
        'pertanyaan_id',
        'urutan_soal',
        'waktu_mulai',
        'waktu_berakhir',
        'status',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'waktu_mulai',
        'waktu_berakhir',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_berakhir' => 'datetime',
    ];

    /**
     * Get the pretest session that owns the timer.
     */
    public function pretestSession()
    {
        return $this->belongsTo(PretestSession::class, 'session_id');
    }

    /**
     * Get the question that this timer is for.
     */
    public function pertanyaanSoal()
    {
        return $this->belongsTo(PertanyaanSoal::class, 'pertanyaan_id');
    }
}