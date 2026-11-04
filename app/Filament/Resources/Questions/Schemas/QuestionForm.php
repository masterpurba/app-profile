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
                    Section::make(__('Public Question Details'))
                        ->schema([
                            TextInput::make('subject')
                                ->label(__('Question Subject'))
                                ->required()
                                ->columnSpanFull(),
                            Textarea::make('body')
                                ->label(__('Full Question Body'))
                                ->rows(5)
                                ->required()
                                ->columnSpanFull(),
                            Select::make('topic_id')
                                ->label(__('Topic'))
                                ->relationship('topic', 'name')
                                ->searchable()
                                ->preload(),
                            Select::make('status')
                                ->label(__('Question Status'))
                                ->options(QuestionStatus::class)
                                ->default(QuestionStatus::Pending)
                                ->required(),
                        ])->columns(2)->columnSpan(2),

                    Section::make(__('Questioner Identity'))
                        ->schema([
                            TextInput::make('name')
                                ->label(__('Full Name'))
                                ->required(),
                            TextInput::make('email')
                                ->label(__('Questioner Email'))
                                ->email()
                                ->required(),
                            TextInput::make('phone')
                                ->label(__('Phone Number / WhatsApp'))
                                ->tel(),
                            TextInput::make('ip_address')
                                ->label(__('IP Address'))
                                ->disabled(),
                        ])->columnSpan(1),
                ])->columnSpanFull(),

                Section::make(__('Official Response & Answer'))
                    ->description(__('Write official agency answer for this question'))
                    ->schema([
                        Repeater::make('answers')
                            ->relationship('answers')
                            ->schema([
                                Select::make('user_id')
                                    ->label(__('Responding Officer / Admin'))
                                    ->relationship('user', 'name')
                                    ->default(fn () => auth()->id())
                                    ->required(),
                                Select::make('status')
                                    ->label(__('Response Status'))
                                    ->options(AnswerStatus::class)
                                    ->default(AnswerStatus::Published)
                                    ->required(),
                                Toggle::make('is_published')
                                    ->label(__('Show to Public'))
                                    ->default(true),
                                DateTimePicker::make('answered_at')
                                    ->label(__('Answered Date'))
                                    ->default(now()),
                                RichEditor::make('body')
                                    ->label(__('Official Answer Content'))
                                    ->required()
                                    ->columnSpanFull(),
                            ])
                            ->columns(4)
                            ->defaultItems(0)
                            ->addActionLabel(__('Add New Response')),
                    ]),
            ]);
    }
}
