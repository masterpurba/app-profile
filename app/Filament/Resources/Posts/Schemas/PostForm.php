<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class PostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make(__('Article Content'))
                        ->schema([
                            TextInput::make('title')
                                ->label(__('Article Title'))
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label(__('URL Slug'))
                                ->required()
                                ->unique(ignoreRecord: true),
                            Textarea::make('summary')
                                ->label(__('Brief Summary'))
                                ->rows(3)
                                ->columnSpanFull(),
                            RichEditor::make('content')
                                ->label(__('Full Article Content'))
                                ->required()
                                ->columnSpanFull(),
                        ])->columnSpan(2),

                    Section::make(__('Meta & Publication'))
                        ->schema([
                            Select::make('category_id')
                                ->label(__('Category'))
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('name')
                                        ->label(__('Category Name'))
                                        ->required(),
                                    TextInput::make('slug')
                                        ->label(__('URL Slug'))
                                        ->required(),
                                ]),
                            Select::make('user_id')
                                ->label(__('Author'))
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required()
                                ->searchable(),
                            Select::make('status')
                                ->label(__('Publication Status'))
                                ->options(PostStatus::class)
                                ->default(PostStatus::Draft)
                                ->required(),
                            DateTimePicker::make('published_at')
                                ->label(__('Publish Date'))
                                ->default(now()),
                            FileUpload::make('image')
                                ->label(__('Featured Image'))
                                ->image()
                                ->directory('posts')
                                ->visibility('public'),
                            Toggle::make('is_featured')
                                ->label(__('Set as Featured'))
                                ->default(false),
                            TextInput::make('view_count')
                                ->label(__('View Count'))
                                ->numeric()
                                ->default(0)
                                ->disabled(),
                        ])->columnSpan(1),
                ])->columnSpanFull(),
            ]);
    }
}
