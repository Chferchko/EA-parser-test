<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Contracts\IncomeRepositoryInterface;
use App\Domain\Contracts\OrderRepositoryInterface;
use App\Domain\Contracts\SaleRepositoryInterface;
use App\Domain\Contracts\StockRepositoryInterface;
use App\Infrastructure\Repositories\IncomeRepository;
use App\Infrastructure\Repositories\OrderRepository;
use App\Infrastructure\Repositories\SaleRepository;
use App\Infrastructure\Repositories\StockRepository;
use App\Infrastructure\WbApi\Auth\WbQueryAuthStrategy;
use App\Infrastructure\WbApi\Client\WbApiClient;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Support\ServiceProvider;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WbApiClient::class, fn ($app): WbApiClient => new WbApiClient(
            $app->make(HttpFactory::class),
            $this->wbApiBaseEndpoint(),
            new WbQueryAuthStrategy($this->wbApiAuthKey()),
        ));

        $this->app->bind(StockRepositoryInterface::class, StockRepository::class);
        $this->app->bind(IncomeRepositoryInterface::class, IncomeRepository::class);
        $this->app->bind(SaleRepositoryInterface::class, SaleRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}

    private function wbApiBaseEndpoint(): string
    {
        $baseEndpoint = config('stock_api.base_endpoint');

        if (!\is_string($baseEndpoint) || $baseEndpoint === '') {
            throw new \RuntimeException('stock_api.base_endpoint is not configured');
        }

        return $baseEndpoint;
    }

    private function wbApiAuthKey(): string
    {
        $key = config('stock_api.key');

        if (!\is_string($key) || $key === '') {
            throw new \RuntimeException('stock_api.key is not configured');
        }

        return $key;
    }
}
