<?php

namespace App\Filament\Resources\Slides\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class SlidesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Banner'))
                    ->width(120)
                    ->height(60),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('sort_order')
                    ->label(__('Order'))
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_active')
                    ->label(__('Active Status'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('start_date')
                    ->label(__('Starts'))
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('Ends'))
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->defaultSort('sort_order', 'asc')
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
