<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\PageStatus;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make(__('Page Content'))
                        ->schema([
                            TextInput::make('title')
                                ->label(__('Page Title'))
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label(__('URL Slug'))
                                ->required()
                                ->unique(ignoreRecord: true),
                            RichEditor::make('content')
                                ->label(__('Page Content'))
                                ->required()
                                ->columnSpanFull(),
                        ])->columnSpan(2),

                    Section::make(__('Page Settings'))
                        ->schema([
                            Select::make('user_id')
                                ->label(__('Author'))
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required(),
                            Select::make('status')
                                ->label(__('Status'))
                                ->options(PageStatus::class)
                                ->default(PageStatus::Published)
                                ->required(),
                            FileUpload::make('image')
                                ->label(__('Banner / Header Image'))
                                ->image()
                                ->directory('pages')
                                ->visibility('public'),
                            TextInput::make('sort_order')
                                ->label(__('Order'))
                                ->numeric()
                                ->default(0),
                            Toggle::make('show_in_menu')
                                ->label(__('Show in Navigation Menu'))
                                ->default(false),
                        ])->columnSpan(1),
                ])->columnSpanFull(),
            ]);
    }
}
