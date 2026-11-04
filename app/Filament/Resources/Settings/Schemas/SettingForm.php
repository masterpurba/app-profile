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
                Section::make(__('Setting Configuration'))
                    ->schema([
                        TextInput::make('group')
                            ->label(__('Setting Group'))
                            ->placeholder(__('e.g. general, contact, social, seo'))
                            ->default('general')
                            ->required(),
                        TextInput::make('key')
                            ->label(__('Key (Unique key)'))
                            ->placeholder(__('e.g. site_name, contact_email'))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Select::make('type')
                            ->label(__('Value Type'))
                            ->options(SettingType::class)
                            ->default(SettingType::Text)
                            ->required(),
                        Textarea::make('value')
                            ->label(__('Setting Value'))
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }
}
