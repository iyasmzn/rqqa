<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\LatestQuestionsWidget;
use App\Filament\Widgets\LatestSpmbRegistrationsWidget;
use App\Filament\Widgets\PostsChartWidget;
use App\Filament\Widgets\SpmbRegistrationsChartWidget;
use App\Filament\Widgets\StatsOverviewWidget;
use BackedEnum;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Support\Icons\Heroicon;

class Dashboard extends BaseDashboard
{
    protected static ?string $title = 'Dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    public function getColumns(): int|array
    {
        return [
            'default' => 1,
            'lg' => 2,
            'xl' => 3,
        ];
    }

    public function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
            PostsChartWidget::class,
            SpmbRegistrationsChartWidget::class,
            LatestSpmbRegistrationsWidget::class,
            LatestQuestionsWidget::class,
        ];
    }
}
