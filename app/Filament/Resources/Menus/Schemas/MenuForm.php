<?php

namespace App\Filament\Resources\Menus\Schemas;

use App\Enums\MenuItemType;
use App\Enums\MenuLocation;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MenuForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Menu')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama Menu')
                            ->required(),
                        Select::make('location')
                            ->label('Lokasi Penempatan')
                            ->options(MenuLocation::class)
                            ->default(MenuLocation::Header)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                    ])->columns(3),

                Section::make('Daftar Item Menu')
                    ->description('Kelola tautan dan urutan item di dalam menu ini')
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                TextInput::make('label')
                                    ->label('Teks Tautan')
                                    ->required(),
                                Select::make('type')
                                    ->label('Tipe')
                                    ->options(MenuItemType::class)
                                    ->default(MenuItemType::Custom)
                                    ->required(),
                                TextInput::make('url')
                                    ->label('Alamat URL / Path')
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label('Urutan')
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label('Aktif')
                                    ->default(true),
                            ])
                            ->orderColumn('sort_order')
                            ->columns(5)
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => $state['label'] ?? null),
                    ]),
            ]);
    }
}
