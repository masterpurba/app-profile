<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\Posts\PostResource;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class LatestPostsWidget extends TableWidget
{
    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    protected function getTableHeading(): string|Htmlable|null
    {
        return __('Latest Articles & News');
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Latest Articles & News'))
            ->query(fn (): Builder => Post::query()->latest('published_at')->limit(5))
            ->columns([
                ImageColumn::make('image')
                    ->label(__('Cover'))
                    ->circular(),
                TextColumn::make('title')
                    ->label(__('Title'))
                    ->limit(50),
                TextColumn::make('category.name')
                    ->label(__('Category'))
                    ->badge(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge(),
                TextColumn::make('view_count')
                    ->label(__('Views'))
                    ->numeric(),
                TextColumn::make('published_at')
                    ->label(__('Publish Date'))
                    ->dateTime('d M Y, H:i'),
            ])
            ->recordActions([
                Action::make('edit')
                    ->label(__('Manage'))
                    ->icon('heroicon-m-pencil-square')
                    ->url(fn (Post $record): string => PostResource::getUrl('edit', ['record' => $record])),
            ])
            ->paginated(false);
    }
}
