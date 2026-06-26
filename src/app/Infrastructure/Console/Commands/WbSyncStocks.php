<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use App\Application\WbSync\DTO\SyncWbEntitiesInput;
use App\Application\WbSync\Handlers\SyncStocksHandler;
use App\Infrastructure\WbApi\Enums\WbApiEndpoint;
use App\Infrastructure\WbApi\Filters\CommonQueryFilter;

final class WbSyncStocks extends AbstractSyncWbCommand
{
    protected $signature = 'wb:sync-stocks {--limit= : WB API limit}';

    protected $description = 'Synchronize stocks from WB API.';

    public function handle(SyncStocksHandler $handler): int
    {
        return $this->finish($handler->handle(new SyncWbEntitiesInput(
            WbApiEndpoint::Stocks,
            new CommonQueryFilter(new \DateTimeImmutable('today')),
            $this->pagination(),
        )));
    }
}
