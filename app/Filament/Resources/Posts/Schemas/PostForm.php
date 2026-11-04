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
                    Section::make('Konten Artikel')
                        ->schema([
                            TextInput::make('title')
                                ->label('Judul Artikel')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),
                            TextInput::make('slug')
                                ->label('Slug URL')
                                ->required()
                                ->unique(ignoreRecord: true),
                            Textarea::make('summary')
                                ->label('Ringkasan Singkat')
                                ->rows(3)
                                ->columnSpanFull(),
                            RichEditor::make('content')
                                ->label('Isi Lengkap Artikel')
                                ->required()
                                ->columnSpanFull(),
                        ])->columnSpan(2),

                    Section::make('Meta & Publikasi')
                        ->schema([
                            Select::make('category_id')
                                ->label('Kategori')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload()
                                ->createOptionForm([
                                    TextInput::make('name')->required(),
                                    TextInput::make('slug')->required(),
                                ]),
                            Select::make('user_id')
                                ->label('Penulis')
                                ->relationship('user', 'name')
                                ->default(fn () => auth()->id())
                                ->required()
                                ->searchable(),
                            Select::make('status')
                                ->label('Status Publikasi')
                                ->options(PostStatus::class)
                                ->default(PostStatus::Draft)
                                ->required(),
                            DateTimePicker::make('published_at')
                                ->label('Tanggal Tayang')
                                ->default(now()),
                            FileUpload::make('image')
                                ->label('Gambar Utama')
                                ->image()
                                ->directory('posts')
                                ->visibility('public'),
                            Toggle::make('is_featured')
                                ->label('Tampilkan sebagai Unggulan (Featured)')
                                ->default(false),
                            TextInput::make('view_count')
                                ->label('Jumlah Dilihat')
                                ->numeric()
                                ->default(0)
                                ->disabled(),
                        ])->columnSpan(1),
                ])->columnSpanFull(),
            ]);
    }
}
