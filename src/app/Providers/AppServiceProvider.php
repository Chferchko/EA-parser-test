<?php

declare(strict_types=1);

namespace App\Providers;

use App\Api\WbApiClient;
use App\Api\WbQueryAuthStrategy;
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
            $this->WbApiBaseEndpoint(),
            new WbQueryAuthStrategy($this->WbApiAuthKey()),
        ));
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {}

    private function WbApiBaseEndpoint(): string
    {
        $baseEndpoint = config('stock_api.base_endpoint');

        if (!\is_string($baseEndpoint) || $baseEndpoint === '') {
            throw new \RuntimeException('stock_api.base_endpoint is not configured');
        }

        return $baseEndpoint;
    }

    private function WbApiAuthKey(): string
    {
        $key = config('stock_api.key');

        if (!\is_string($key) || $key === '') {
            throw new \RuntimeException('stock_api.key is not configured');
        }

        return $key;
    }
}
