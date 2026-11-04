<?php

namespace App\Filament\Resources\Slides\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SlideForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Slide Information'))
                    ->schema([
                        TextInput::make('title')
                            ->label(__('Slide Title'))
                            ->required(),
                        TextInput::make('link_url')
                            ->label(__('Target Link (URL)')),
                        Textarea::make('description')
                            ->label(__('Short Description'))
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label(__('Slide / Banner Image'))
                            ->image()
                            ->directory('slides')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label(__('Order'))
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label(__('Active Status'))
                            ->default(true)
                            ->required(),
                        DatePicker::make('start_date')
                            ->label(__('Display Start Date')),
                        DatePicker::make('end_date')
                            ->label(__('Display End Date')),
                    ])->columns(2),
            ]);
    }
}
