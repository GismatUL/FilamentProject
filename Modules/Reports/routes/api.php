<?php

use Illuminate\Support\Facades\Route;
use Modules\Reports\app\Http\Controllers\Api\OrderSummaryController;

Route::middleware('api')->prefix('api/v1')->name('api.v1.')->group(function () {
    Route::get('/reports/orders/summary', OrderSummaryController::class)->name('reports.orders.summary');
});
