<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'group',
        'type',
    ];

    /**
     * Mutator to automatically convert null values to empty strings
     */
    public function setValueAttribute($value)
    {
        $this->attributes['value'] = $value === null ? '' : $value;
    }

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = '')
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value, $type = 'text')
    {
        // Convert null values to empty strings to prevent database constraint violation
        $processedValue = $value === null ? '' : $value;
        
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $processedValue,
                'type' => $type
            ]
        );
    }

    /**
     * Get all settings grouped by group
     */
    public static function getGrouped()
    {
        return static::all()->groupBy('group');
    }
}