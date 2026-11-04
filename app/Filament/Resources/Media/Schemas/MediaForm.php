<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Unggah Berkas Media')
                    ->schema([
                        Select::make('user_id')
                            ->label('Pengunggah')
                            ->relationship('user', 'name')
                            ->default(fn () => auth()->id())
                            ->required(),
                        FileUpload::make('file_path')
                            ->label('Pilih Berkas / Foto')
                            ->directory('media')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('file_name')
                            ->label('Nama Berkas')
                            ->required(),
                        TextInput::make('mime_type')
                            ->label('Tipe Konten (MIME)')
                            ->placeholder('image/jpeg, application/pdf')
                            ->required(),
                        TextInput::make('file_size')
                            ->label('Ukuran Berkas (Bytes)')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('alt_text')
                            ->label('Teks Alternatif (Alt Text)')
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
