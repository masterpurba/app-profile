<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SettingType: string implements HasLabel
{
    case Text = 'text';
    case Textarea = 'textarea';
    case Image = 'image';
    case Boolean = 'boolean';
    case Json = 'json';

    public function getLabel(): string
    {
        return match ($this) {
            self::Text => __('Short Text'),
            self::Textarea => __('Long Text'),
            self::Image => __('Image / Logo'),
            self::Boolean => __('Yes / No (Switch)'),
            self::Json => __('JSON Format'),
        };
    }
}
