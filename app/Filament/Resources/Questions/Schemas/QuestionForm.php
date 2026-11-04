<?php

namespace App\Filament\Resources\Questions\Schemas;

use App\Enums\AnswerStatus;
use App\Enums\QuestionStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class QuestionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)->schema([
                    Section::make('Rincian Pertanyaan Masyarakat')
                        ->schema([
                            TextInput::make('subject')
                                ->label('Subjek Pertanyaan')
                                ->required()
                                ->columnSpanFull(),
                            Textarea::make('body')
                                ->label('Isi Pertanyaan Lengkap')
                                ->rows(5)
                                ->required()
                                ->columnSpanFull(),
                            Select::make('topic_id')
                                ->label('Topik')
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('status')
                                ->label('Status Pertanyaan')
                                ->options(QuestionStatus::class)
                                ->default(QuestionStatus::Pending)
                                ->required(),
                        ])->columns(2)->columnSpan(2),

                    Section::make('Identitas Penanya')
                        ->schema([
                            TextInput::make('name')
                                ->label('Nama Lengkap')
                                ->required(),
                            TextInput::make('email')
                                ->label('Email Penanya')
                                ->email()
                                ->required(),
                            TextInput::make('phone')
                                ->label('No. Telepon / WhatsApp')
                                ->tel(),
                            TextInput::make('ip_address')
                                ->label('Alamat IP')
                                ->disabled(),
                        ])->columnSpan(1),
                ])->columnSpanFull(),

                Section::make('Tanggapan & Jawaban Resmi')
                    ->description('Tuliskan jawaban dari pihak instansi untuk pertanyaan ini')
                    ->schema([
                        Repeater::make('answers')
                            ->relationship('answers')
                            ->schema([
                                Select::make('user_id')
                                    ->label('Petugas / Admin Penjawab')
                                    ->relationship('user', 'name')
                                    ->default(fn () => auth()->id())
                                    ->required(),
                                Select::make('status')
                                    ->label('Status Jawaban')
                                    ->options(AnswerStatus::class)
                                    ->default(AnswerStatus::Published)
                                    ->required(),
                                Toggle::make('is_published')
                                    ->label('Tampilkan ke Publik')
                                    ->default(true),
                                DateTimePicker::make('answered_at')
                                    ->label('Tanggal Dijawab')
                                    ->default(now()),
                                RichEditor::make('body')
                                    ->label('Isi Jawaban Resmi')
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->addActionLabel('Tambah Tanggapan Baru'),
                    ]),
            ]);
    }
}
