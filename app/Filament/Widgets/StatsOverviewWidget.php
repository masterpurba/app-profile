<?php

namespace App\Filament\Widgets;

use App\Enums\PostStatus;
use App\Enums\QuestionStatus;
use App\Models\Post;
use App\Models\Question;
use Filament\Widgets\StatsOverviewWidget as BaseStatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseStatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', PostStatus::Published)->count();
        $pendingQuestions = Question::where('status', QuestionStatus::Pending)->count();
        $answeredQuestions = Question::where('status', QuestionStatus::Answered)->count();
        $totalViews = Post::sum('view_count');

        return [
            Stat::make('Total Artikel', $totalPosts)
                ->description("{$publishedPosts} dipublikasikan")
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make('Pertanyaan Menunggu', $pendingQuestions)
                ->description('Perlu ditanggapi segera')
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingQuestions > 0 ? 'warning' : 'success'),

            Stat::make('Pertanyaan Terjawab', $answeredQuestions)
                ->description('Telah selesai ditanggapi')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Pembaca', number_format($totalViews))
                ->description('Total tayangan artikel')
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
        ];
    }
}
