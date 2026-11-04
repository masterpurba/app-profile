<?php

namespace App\Filament\Resources\Settings\Tables;

use App\Enums\SettingType;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('group')
                    ->label(__('Group'))
                    ->badge()
                    ->sortable(),
                TextColumn::make('key')
                    ->label(__('Key'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),
                TextColumn::make('value')
                    ->label(__('Value'))
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label(__('Last Modified'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('group', 'asc')
            ->filters([
                SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options(SettingType::class),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
