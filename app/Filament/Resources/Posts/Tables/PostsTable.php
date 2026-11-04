<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\PostStatus;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Cover'))
                    ->circular()
                    ->defaultImageUrl('/placeholder.png'),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->tooltip(fn ($record): string => $record->title),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->badge()
                    ->sortable()
                    ->searchable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                IconColumn::make('is_featured')
                    ->label(__('Featured'))
                    ->boolean()
                    ->sortable(),
                TextColumn::make('view_count')
                    ->label(__('Views'))
                    ->numeric()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label(__('Published At'))
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('published_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status Filter'))
                    ->options(PostStatus::class),
                SelectFilter::make('category')
                    ->label(__('Category Filter'))
                    ->relationship('category', 'name'),
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
