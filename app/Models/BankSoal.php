<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSoal extends Model
{
    use HasFactory;

    protected $table = 'bank_soals';

    protected $fillable = [
        'kode_bank',
        'tahun_ajaran_id',
        'type_test',
        'mapel_id',
        'created_by',
        'pengawas_id',
        'nama_bank',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_menit',
        'max_time',
        'bobot_benar_default',
        'bobot_salah_default',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'datetime',
        'tanggal_selesai' => 'datetime',
        'bobot_benar_default' => 'decimal:2',
        'bobot_salah_default' => 'decimal:2',
    ];

    /**
     * Get the tahun ajaran that owns the bank soal.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Get the mata pelajaran that owns the bank soal.
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    /**
     * Get the creator (guru) that owns the bank soal.
     */
    public function creator()
    {
        return $this->belongsTo(Guru::class, 'created_by', 'id_guru');
    }

    /**
     * Get the pengawas (guru) that owns the bank soal.
     */
    public function pengawas()
    {
        return $this->belongsTo(Guru::class, 'pengawas_id', 'id_guru');
    }

    /**
     * Get the pertanyaan soals for the bank soal.
     */
    public function pertanyaanSoals()
    {
        return $this->hasMany(PertanyaanSoal::class);
    }

    /**
     * Get the rombels for the bank soal.
     */
    public function bankSoalRombels()
    {
        return $this->hasMany(BankSoalRombel::class);
    }

    /**
     * Get the rombels that belong to the bank soal.
     */
    public function rombels()
    {
        return $this->belongsToMany(Rombel::class, 'bank_soal_rombel', 'bank_soal_id', 'rombel_id');
    }

    /**
     * Get the pretest session for the bank soal (One-to-One).
     */
    public function pretestSession()
    {
        return $this->hasOne(PretestSession::class, 'bank_soal_id');
    }
}