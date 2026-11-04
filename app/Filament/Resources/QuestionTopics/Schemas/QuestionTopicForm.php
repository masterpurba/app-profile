<?php

namespace App\Filament\Resources\QuestionTopics\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class QuestionTopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Topic Information'))
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Topic Name'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                        TextInput::make('slug')
                            ->label(__('URL Slug'))
                            ->required()
                            ->unique(ignoreRecord: true),
                        Textarea::make('description')
                            ->label(__('Description'))
                            ->columnSpanFull(),
                        TextInput::make('icon')
                            ->label(__('Icon'))
                            ->placeholder(__('e.g. folder, chat, user')),
                        TextInput::make('sort_order')
                            ->label(__('Order'))
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label(__('Active Status'))
                            ->default(true)
                            ->required(),
                    ])->columns(2),
            ]);
    }
}
