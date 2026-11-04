<?php

namespace App\Models;

use App\Enums\SettingType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['group', 'key', 'value', 'type'])]
class Setting extends Model
{
    protected function casts(): array
    {
        return [
            'type' => SettingType::class,
        ];
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();

        if (! $setting) {
            return $default;
        }

        return match ($setting->type) {
            SettingType::Boolean => filter_var($setting->value, FILTER_VALIDATE_BOOLEAN),
            SettingType::Json => json_decode($setting->value, true) ?? $default,
            default => $setting->value ?? $default,
        };
    }
}
