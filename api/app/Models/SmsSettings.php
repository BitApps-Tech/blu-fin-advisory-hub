<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_url',
        'api_key',
        'provider_name',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the active SMS settings
     */
    public static function getActive()
    {
        return static::where('is_active', true)->first();
    }

    /**
     * Get or create SMS settings
     */
    public static function getOrCreate()
    {
        $settings = static::first();
        
        if (!$settings) {
            $settings = static::create([
                'api_url' => 'https://smsethiopia.et/api/sms/send',
                'api_key' => '',
                'provider_name' => 'SMS Ethiopia',
                'is_active' => true,
            ]);
        }

        return $settings;
    }
}

