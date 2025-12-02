<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankSoalRombel extends Model
{
    protected $table = 'bank_soal_rombel';
    
    protected $fillable = [
        'bank_soal_id',
        'rombel_id',
    ];

    /**
     * Get the bank soal that belongs to the rombel.
     */
    public function bankSoal()
    {
        return $this->belongsTo(BankSoal::class);
    }

    /**
     * Get the rombel that belongs to the bank soal.
     */
    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
