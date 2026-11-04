<?php

namespace App\Filament\Resources\Settings\Schemas;

use App\Enums\SettingType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Konfigurasi Pengaturan')
                    ->schema([
                        TextInput::make('group')
                            ->label('Grup Pengaturan')
                            ->placeholder('contoh: general, contact, social, seo')
                            ->default('general')
                            ->required(),
                        TextInput::make('key')
                            ->label('Kunci (Key unik)')
                            ->placeholder('contoh: site_name, contact_email')
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('type')
                            ->label('Tipe Nilai')
                            ->options(SettingType::class)
                            ->default(SettingType::Text)
                            ->required(),
                        Textarea::make('value')
                            ->label('Nilai Pengaturan (Value)')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }
}
