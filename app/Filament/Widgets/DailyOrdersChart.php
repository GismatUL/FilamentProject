<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;

class DailyOrdersChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected ?string $heading = 'Orders – Last 30 Days';

    protected int | string | array $columnSpan = 'half';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $counts = Order::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->pluck('count', 'date');

        $labels = [];
        $data = [];

        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $labels[] = now()->subDays($i)->format('M j');
            $data[] = (int) ($counts[$date] ?? 0);
        }

        return [
            'datasets' => [
                [
                    'label' => 'Orders',
                    'data' => $data,
                    'fill' => true,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'tension' => 0.3,
                ],
            ],
            'labels' => $labels,
        ];
    }
}
