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
                    ->label(__('Subject'))
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn ($record): string => $record->subject),
                TextColumn::make('name')
                    ->label(__('Questioner Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('topic.name')
                    ->label(__('Topic'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('answers_count')
                    ->label(__('Answer Count'))
                    ->counts('answers')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label(__('Received At'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(QuestionStatus::class),
                SelectFilter::make('topic')
                    ->label(__('Topic'))
                    ->relationship('topic', 'name'),
            ])
            ->recordActions([
                Action::make('resendNotification')
                    ->label(__('Send Email'))
                    ->icon('heroicon-m-envelope')
                    ->color('info')
                    ->visible(fn ($record) => $record->answers()->where('is_published', true)->exists() && ! empty($record->email))
                    ->requiresConfirmation()
                    ->modalHeading(__('Resend Answer Notification?'))
                    ->modalDescription(__('Official answer email will be resent to the requester.'))
                    ->action(function ($record) {
                        $latestAnswer = $record->answers()->where('is_published', true)->latest()->first();
                        if ($latestAnswer) {
                            \Illuminate\Support\Facades\Notification::route('mail', $record->email)
                                ->notify(new QuestionAnsweredNotification($record, $latestAnswer));

                            Notification::make()
                                ->title(__('Email Sent Successfully'))
                                ->body(__('Response notification has been sent to :email', ['email' => $record->email]))
                                ->success()
                                ->send();
                        }
                    }),
                EditAction::make()->label(__('Respond / Edit')),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
