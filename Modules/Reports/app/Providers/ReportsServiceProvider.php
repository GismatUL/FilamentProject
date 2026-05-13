<?php

namespace Modules\Reports\app\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Modules\Reports\app\Livewire\OrderReport;

class ReportsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'reports');

        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');

        Livewire::component('reports.order-report', OrderReport::class);
    }
}
