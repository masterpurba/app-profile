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
                Section::make('Kelola Tanya Jawab Umum (FAQ)')
                    ->schema([
                        Select::make('topic_id')
                            ->label('Topik Kategori')
                            ->relationship('topic', 'name')
                            ->searchable()
                            ->preload(),
                        TextInput::make('sort_order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0)
                            ->required(),
                        Toggle::make('is_active')
                            ->label('Status Aktif')
                            ->default(true)
                            ->required(),
                        TextInput::make('question')
                            ->label('Pertanyaan yang Sering Diajukan')
                            ->required()
                            ->columnSpanFull(),
                        RichEditor::make('answer')
                            ->label('Jawaban Lengkap')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(3),
            ]);
    }
}
