<?php

namespace App\Filament\Resources\Media\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MediaTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('file_path')
                    ->label('Pratinjau')
                    ->square(),
                TextColumn::make('file_name')
                    ->label('Nama File')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mime_type')
                    ->label('Tipe')
                    ->badge(),
                TextColumn::make('file_size')
                    ->label('Ukuran')
                    ->formatStateUsing(fn ($state) => number_format($state / 1024, 1).' KB')
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Pengunggah')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Diupload')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
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
