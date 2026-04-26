<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    /**
     * Get a setting value by key.
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return static::castValue($setting->value, $setting->type);
    }

    /**
     * Set a setting value by key.
     */
    public static function set($key, $value, $type = 'string', $description = null)
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            [
                'value' => static::serializeValue($value, $type),
                'type' => $type,
                'description' => $description
            ]
        );

        return $setting;
    }

    protected static function castValue($value, $type)
    {
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'boolean':
                return (bool) $value;
            case 'array':
                return json_decode($value, true);
            default:
                return $value;
        }
    }

    protected static function serializeValue($value, $type)
    {
        if ($type === 'array' || is_array($value)) {
            return json_encode($value);
        }
        return (string) $value;
    }
}
