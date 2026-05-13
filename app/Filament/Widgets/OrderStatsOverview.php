<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use App\Models\OrderItem;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class OrderStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalOrders = Order::count();
        $newOrders = Order::where('status', 'new')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        $totalRevenue = OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', '!=', 'cancelled')
            ->sum(DB::raw('order_items.qty * order_items.unit_price'));

        $last30Days = Order::query()
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->where('created_at', '>=', now()->subDays(29))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $chartData = collect(range(29, 0))->map(
            fn ($daysAgo) => (int) ($last30Days[now()->subDays($daysAgo)->toDateString()] ?? 0)
        )->values()->all();

        return [
            Stat::make('Total Orders', number_format($totalOrders))
                ->description('All time')
                ->descriptionIcon(Heroicon::OutlinedShoppingCart)
                ->color('primary')
                ->chart($chartData),

            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Excluding cancelled orders')
                ->descriptionIcon(Heroicon::OutlinedBanknotes)
                ->color('success'),

            Stat::make('New Orders', number_format($newOrders))
                ->description('Awaiting processing')
                ->descriptionIcon(Heroicon::OutlinedClock)
                ->color('warning'),

            Stat::make('Delivered', number_format($deliveredOrders))
                ->description('Successfully fulfilled')
                ->descriptionIcon(Heroicon::OutlinedCheckCircle)
                ->color('success'),
        ];
    }
}
