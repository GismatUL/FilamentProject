<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class OrdersByStatusChart extends ChartWidget
{
    protected static ?int $sort = 2;

    protected ?string $heading = 'Orders by Status';

    protected int | string | array $columnSpan = 'half';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $counts = Order::query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        $statuses = ['new', 'processing', 'shipped', 'delivered', 'cancelled'];
        $colors = [
            'new' => '#3b82f6',
            'processing' => '#f59e0b',
            'shipped' => '#8b5cf6',
            'delivered' => '#22c55e',
            'cancelled' => '#ef4444',
        ];

        return [
            'datasets' => [
                [
                    'data' => collect($statuses)->map(fn ($s) => $counts[$s] ?? 0)->values()->all(),
                    'backgroundColor' => collect($statuses)->map(fn ($s) => $colors[$s])->values()->all(),
                ],
            ],
            'labels' => collect($statuses)->map(fn ($s) => ucfirst($s))->all(),
        ];
    }
}
