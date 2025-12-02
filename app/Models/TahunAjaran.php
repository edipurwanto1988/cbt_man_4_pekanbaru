<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun_ajaran',
        'semester',
        'status',
        'keterangan',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];
    /**
     * Get the bank soals for the tahun ajaran.
     */
    public function bankSoals()
    {
        return $this->hasMany(BankSoal::class);
    }
}