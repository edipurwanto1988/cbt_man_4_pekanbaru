<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JawabanSoal extends Model
{
    use HasFactory;

    protected $table = 'jawaban_soals';

    protected $fillable = [
        'pertanyaan_id',
        'opsi',
        'isi_jawaban',
        'gambar_jawaban',
        'is_benar',
    ];

    protected $casts = [
        'is_benar' => 'boolean',
    ];

    /**
     * Get the pertanyaan soal that owns the jawaban soal.
     */
    public function pertanyaanSoal()
    {
        return $this->belongsTo(PertanyaanSoal::class, 'pertanyaan_id');
    }
}