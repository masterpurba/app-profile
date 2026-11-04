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
                Section::make(__('Menu Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Menu Name'))
                            ->required(),
                        Select::make('location')
                            ->label(__('Placement Location'))
                            ->options(MenuLocation::class)
                            ->default(MenuLocation::Header)
                            ->required(),
                        Toggle::make('is_active')
                            ->label(__('Active Status'))
                            ->default(true)
                            ->required(),
                    ])->columns(3),

                Section::make(__('Menu Item List'))
                    ->description(__('Manage links and ordering of items in this menu'))
                    ->schema([
                        Repeater::make('items')
                            ->relationship('items')
                            ->schema([
                                TextInput::make('label')
                                    ->label(__('Link Text'))
                                    ->required(),
                                Select::make('type')
                                    ->label(__('Type'))
                                    ->options(MenuItemType::class)
                                    ->default(MenuItemType::Custom)
                                    ->required(),
                                TextInput::make('url')
                                    ->label(__('URL Address / Path'))
                                    ->required(),
                                TextInput::make('sort_order')
                                    ->label(__('Order'))
                                    ->numeric()
                                    ->default(0)
                                    ->required(),
                                Toggle::make('is_active')
                                    ->label(__('Active'))
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
