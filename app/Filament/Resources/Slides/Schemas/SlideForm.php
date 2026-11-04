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
                Section::make('Informasi Slide')
                    ->schema([
                        TextInput::make('title')
                            ->label('Judul Slide')
                            ->required(),
                        TextInput::make('link_url')
                            ->label('Tautan Tujuan (URL)'),
                        Textarea::make('description')
                            ->label('Deskripsi Singkat')
                            ->columnSpanFull(),
                        FileUpload::make('image')
                            ->label('Gambar Slide / Banner')
                            ->image()
                            ->directory('slides')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai Tayang'),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai Tayang'),
                    ])->columns(2),
            ]);
    }
}
