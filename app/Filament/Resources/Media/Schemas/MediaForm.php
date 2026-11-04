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
                Section::make(__('Upload Media File'))
                    ->schema([
                        Select::make('user_id')
                            ->label(__('Uploader'))
                            ->relationship('user', 'name')
                            ->default(fn () => auth()->id())
                            ->required(),
                        FileUpload::make('file_path')
                            ->label(__('Choose File / Photo'))
                            ->directory('media')
                            ->visibility('public')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('file_name')
                            ->label(__('File Name'))
                            ->required(),
                        TextInput::make('mime_type')
                            ->label(__('Content Type (MIME)'))
                            ->placeholder(__('e.g. image/jpeg, application/pdf'))
                            ->required(),
                        TextInput::make('file_size')
                            ->label(__('File Size (Bytes)'))
                            ->numeric()
                            ->default(0)
                            ->required(),
                        TextInput::make('alt_text')
                            ->label(__('Alternative Text (Alt Text)'))
                            ->columnSpanFull(),
                    ])->columns(2),
            ]);
    }
}
