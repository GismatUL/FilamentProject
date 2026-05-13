<?php

use Illuminate\Support\Facades\Route;
use Modules\Reports\app\Http\Controllers\ReportsController;

Route::middleware('web')->prefix('reports')->name('reports.')->group(function () {
    Route::get('/', ReportsController::class . '@index')->name('index');
});
