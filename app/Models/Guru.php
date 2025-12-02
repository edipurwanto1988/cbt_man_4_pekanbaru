<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guru extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'guru';

    protected $primaryKey = 'id_guru';

    public $timestamps = true;
    
    protected $attributes = [
        'role' => 'guru',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama_guru',
        'nik',
        'email',
        'password',
        'remember_token',
        'role',
    ];
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    /**
     * Get the role attribute.
     *
     * @return string
     */
    public function getRoleAttribute()
    {
        return 'guru';
    }
    
    /**
     * Get the name attribute for authentication.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->nama_guru;
    }
}