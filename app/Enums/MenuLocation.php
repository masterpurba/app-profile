<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum MenuLocation: string implements HasLabel
{
    case Header = 'header';
    case Footer = 'footer';
    case Sidebar = 'sidebar';

    public function getLabel(): string
    {
        return match ($this) {
            self::Header => 'Header (Menu Atas)',
            self::Footer => 'Footer (Menu Bawah)',
            self::Sidebar => 'Sidebar (Menu Samping)',
        };
    }
}
