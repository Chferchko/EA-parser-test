<?php

use App\Infrastructure\Console\Commands\WbSyncIncomes;
use App\Infrastructure\Console\Commands\WbSyncOrders;
use App\Infrastructure\Console\Commands\WbSyncSales;
use App\Infrastructure\Console\Commands\WbSyncStocks;
use Illuminate\Console\Application;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Application::starting(function (Application $artisan): void {
    $artisan->resolveCommands([
        WbSyncStocks::class,
        WbSyncIncomes::class,
        WbSyncSales::class,
        WbSyncOrders::class,
    ]);
});

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
