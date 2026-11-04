<?php

namespace App\Filament\Resources\Questions\Tables;

use App\Enums\QuestionStatus;
use App\Notifications\QuestionAnsweredNotification;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class QuestionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject')
                    ->label('Subjek')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn ($record): string => $record->subject),
                TextColumn::make('name')
                    ->label('Nama Penanya')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('topic.name')
                    ->label('Topik')
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('answers_count')
                    ->label('Jumlah Jawaban')
                    ->counts('answers')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Waktu Masuk')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options(QuestionStatus::class),
                SelectFilter::make('topic')
                    ->label('Topik')
                    ->relationship('topic', 'name'),
            ])
            ->recordActions([
                Action::make('resendNotification')
                    ->label('Kirim Email')
                    ->icon('heroicon-m-envelope')
                    ->color('info')
                    ->visible(fn ($record) => $record->answers()->where('is_published', true)->exists() && ! empty($record->email))
                    ->requiresConfirmation()
                    ->modalHeading('Kirim Ulang Notifikasi Jawaban?')
                    ->modalDescription('Email jawaban resmi akan dikirim ulang ke alamat penanya.')
                    ->action(function ($record) {
                        $latestAnswer = $record->answers()->where('is_published', true)->latest()->first();
                        if ($latestAnswer) {
                            \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                                ->notify(new QuestionAnsweredNotification($record, $latestAnswer));

                            Notification::make()
                                ->title('Email Berhasil Dikirim')
                                ->body("Notifikasi tanggapan telah dikirim ke {$record->email}")
                                ->success()
                                ->send();
                        }
                    }),
                EditAction::make()->label('Tanggapi / Edit'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
