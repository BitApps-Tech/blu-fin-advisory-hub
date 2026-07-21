<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'group',
        'key',
        'value',
        'type',
    ];

    /**
     * Get setting value by group and key.
     */
    public static function getValue($group, $key, $default = null)
    {
        $setting = static::where('group', $group)->where('key', $key)->first();
        
        if (!$setting) {
            return $default;
        }

        switch ($setting->type) {
            case 'boolean':
                return (bool) $setting->value;
            case 'json':
                return json_decode($setting->value, true);
            default:
                return $setting->value;
        }
    }

    /**
     * Set setting value by group and key.
     */
    public static function setValue($group, $key, $value, $type = 'text')
    {
        $setting = static::firstOrNew([
            'group' => $group,
            'key' => $key,
        ]);

        switch ($type) {
            case 'boolean':
                $setting->value = $value ? '1' : '0';
                break;
            case 'json':
                $setting->value = json_encode($value);
                break;
            default:
                $setting->value = (string) $value;
                break;
        }
        
        $setting->type = $type;
        $setting->save();

        return $setting;
    }
}

