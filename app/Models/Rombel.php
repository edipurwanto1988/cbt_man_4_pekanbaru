<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    use HasFactory;

    protected $table = 'rombel';

    protected $fillable = [
        'tahun_ajaran_id',
        'tingkat_id',
        'kode_kelas',
        'nama_rombel',
        'wali_kelas_id',
    ];

    /**
     * Get the tingkat kelas that owns the rombel.
     */
    public function tingkatKelas()
    {
        return $this->belongsTo(TingkatKelas::class, 'tingkat_id');
    }

    /**
     * Get the tahun ajaran that owns the rombel.
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    /**
     * Get the wali kelas that owns the rombel.
     */
    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'wali_kelas_id', 'id_guru');
    }

    /**
     * Get the rombel details for the rombel.
     */
    public function rombelDetails()
    {
        return $this->hasMany(RombelDetail::class, 'rombel_id');
    }

    /**
     * Get the rombel mapel for the rombel.
     */
    public function rombelMapels()
    {
        return $this->hasMany(RombelMapel::class, 'rombel_id');
    }

    /**
     * Get the bank soals for the rombel.
     */
    public function bankSoalRombels()
    {
        return $this->hasMany(BankSoalRombel::class, 'rombel_id');
    }

    /**
     * Get the bank soals that belong to the rombel.
     */
    public function bankSoals()
    {
        return $this->belongsToMany(BankSoal::class, 'bank_soal_rombel', 'rombel_id', 'bank_soal_id');
    }

    /**
     * Get the siswas that belong to the rombel.
     */
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }

    /**
     * Check if rombel can be deleted (no related data)
     */
    public function canBeDeleted()
    {
        return $this->rombelDetails()->count() === 0 && $this->rombelMapels()->count() === 0;
    }

}