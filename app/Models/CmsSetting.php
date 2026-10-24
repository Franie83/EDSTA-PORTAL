<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsSetting extends Model
{
    protected $fillable = ['key', 'value', 'label', 'type', 'group'];

    public static function get(string $key, $default = null)
    {
        return optional(static::where('key', $key)->first())->value ?? $default;
    }

    public static function set(string $key, $value, string $label = '', string $type = 'text', string $group = 'general'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            compact('value', 'label', 'type', 'group')
        );
    }
}
