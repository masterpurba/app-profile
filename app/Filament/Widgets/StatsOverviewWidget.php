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
            Stat::make(__('Total Articles'), $totalPosts)
                ->description(__(':count published', ['count' => $publishedPosts]))
                ->descriptionIcon('heroicon-m-document-text')
                ->color('primary'),

            Stat::make(__('Pending Questions'), $pendingQuestions)
                ->description(__('Needs immediate response'))
                ->descriptionIcon('heroicon-m-clock')
                ->color($pendingQuestions > 0 ? 'warning' : 'success'),

            Stat::make(__('Answered Questions'), $answeredQuestions)
                ->description(__('Response completed'))
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make(__('Total Readers'), number_format($totalViews))
                ->description(__('Total article views'))
                ->descriptionIcon('heroicon-m-eye')
                ->color('info'),
        ];
    }
}
