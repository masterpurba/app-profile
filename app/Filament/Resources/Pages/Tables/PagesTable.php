<?php

namespace App\Filament\Resources\Pages\Tables;

use App\Enums\PageStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                IconColumn::make('show_in_menu')
                    ->label(__('In Menu'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label(__('Order'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('Last Updated'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(PageStatus::class),
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
