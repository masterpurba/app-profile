<?php

namespace App\Filament\Resources\Menus\Tables;

use App\Enums\MenuLocation;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MenusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Menu Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('location')
                    ->label(__('Location'))
                    ->badge(),
                TextColumn::make('items_count')
                    ->label(__('Item Count'))
                    ->counts('items')
                    ->badge(),
                IconColumn::make('is_active')
                    ->label(__('Active Status'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('Last Modified'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('location')
                    ->label(__('Location'))
                    ->options(MenuLocation::class),
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
