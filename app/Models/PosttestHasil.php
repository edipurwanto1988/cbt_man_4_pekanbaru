<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosttestHasil extends Model
{
    use HasFactory;

    protected $table = 'posttest_hasil';

    protected $fillable = [
        'bank_soal_id',
        'nisn',
        'total_benar',
        'total_salah',
        'total_kosong',
        'nilai_akhir',
        'waktu_pengerjaan',
    ];

    protected $casts = [
        'total_benar' => 'integer',
        'total_salah' => 'integer',
        'total_kosong' => 'integer',
        'nilai_akhir' => 'decimal:2',
        'waktu_pengerjaan' => 'integer',
    ];

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