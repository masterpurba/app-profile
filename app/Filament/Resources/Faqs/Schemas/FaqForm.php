<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Manage Frequently Asked Questions (FAQ)'))
                    ->schema([
                        Select::make('topic_id')
                            ->label(__('Category Topic'))
                            ->relationship('topic', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('sort_order')
                            ->label(__('Order'))
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label(__('Active Status'))
                            ->default(true)
                            ->required(),
                        TextInput::make('question')
                            ->label(__('Frequently Asked Question'))
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('answer')
                            ->label(__('Complete Answer'))
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }
}
