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
                    Section::make('Konten Halaman')
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Halaman')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                            RichEditor::make('content')
                                ->label('Isi Halaman')
                                ->required()
                                ->columnSpanFull(),
                        ])->columnSpan(2),

                    Section::make('Pengaturan Halaman')
                        ->schema([
                            Select::make('user_id')
                                ->label('Penulis')
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required(),
                            Select::make('status')
                                ->label('Status')
                                ->options(PageStatus::class)
                                ->default(PageStatus::Published)
                                ->required(),
                            FileUpload::make('image')
                                ->label('Banner / Header Image')
                                ->image()
                                ->directory('pages')
                                ->visibility('public'),
                            TextInput::make('sort_order')
                                ->label('Urutan')
                                ->numeric()
                                ->default(0),
                            Toggle::make('show_in_menu')
                                ->label('Tampilkan di Navigasi Menu')
                                ->default(false),
                        ])->columnSpan(1),
                ])->columnSpanFull(),
            ]);
    }
}
