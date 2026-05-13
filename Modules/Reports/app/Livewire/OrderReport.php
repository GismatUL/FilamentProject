<?php

namespace Modules\Reports\app\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Component;
use Modules\Reports\app\Services\OrderReportService;

class OrderReport extends Component
{
    public string $period = '30';
    public string $status = 'all';

    public array $periods = [
        '7' => 'Last 7 days',
        '30' => 'Last 30 days',
        '90' => 'Last 90 days',
        '365' => 'Last year',
    ];

    public array $statuses = [
        'all' => 'All statuses',
        'new' => 'New',
        'processing' => 'Processing',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled',
    ];

    #[Computed]
    public function stats(): array
    {
        return app(OrderReportService::class)->filtered((int) $this->period, $this->status);
    }

    #[Computed]
    public function summary(): array
    {
        return app(OrderReportService::class)->summary();
    }

    public function render()
    {
        return view('reports::livewire.order-report');
    }
}
