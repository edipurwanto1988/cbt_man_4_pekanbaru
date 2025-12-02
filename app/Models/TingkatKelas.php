<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TingkatKelas extends Model
{
    use HasFactory;

    protected $table = 'tingkat_kelas';

    protected $fillable = [
        'kode',
        'nama',
    ];

    /**
     * Get the rombels for the tingkat kelas.
     */
    public function rombels()
    {
        return $this->hasMany(Rombel::class, 'tingkat_id');
    }
}