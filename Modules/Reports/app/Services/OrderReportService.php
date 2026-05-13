<?php

namespace Modules\Reports\app\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;

class OrderReportService
{
    public function summary(): array
    {
        $byStatus = Order::query()
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $revenue = $this->revenueQuery()->sum(DB::raw('order_items.qty * order_items.unit_price'));

        return [
            'total_orders' => Order::count(),
            'total_revenue' => (float) $revenue,
            'by_status' => $byStatus,
        ];
    }

    public function filtered(int $days, string $status): array
    {
        $query = Order::query()->where('orders.created_at', '>=', now()->subDays($days));

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orderIds = (clone $query)->pluck('id');

        $revenue = OrderItem::whereIn('order_id', $orderIds)
            ->sum(DB::raw('qty * unit_price'));

        $daily = (clone $query)
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $dates = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->toDateString();
            $dates[$date] = (int) ($daily[$date] ?? 0);
        }

        return [
            'count' => (clone $query)->count(),
            'revenue' => (float) $revenue,
            'daily' => $dates,
        ];
    }

    private function revenueQuery()
    {
        return OrderItem::join('orders', 'orders.id', '=', 'order_items.order_id')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', '!=', 'cancelled');
    }
}
