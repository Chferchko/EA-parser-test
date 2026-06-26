<?php

declare(strict_types=1);

namespace App\Infrastructure\Console\Commands;

use App\Application\WbSync\DTO\SyncWbEntitiesInput;
use App\Application\WbSync\Handlers\SyncOrdersHandler;
use App\Infrastructure\WbApi\Enums\WbApiEndpoint;
use App\Infrastructure\WbApi\Filters\CommonQueryFilter;

final class WbSyncOrders extends AbstractSyncWbCommand
{
    protected $signature = 'wb:sync-orders {--limit= : WB API limit}';

    protected $description = 'Synchronize orders from WB API.';

    public function handle(SyncOrdersHandler $handler): int
    {
        return $this->finish($handler->handle(new SyncWbEntitiesInput(
            WbApiEndpoint::Orders,
            new CommonQueryFilter(new \DateTimeImmutable('today')),
            $this->pagination(),
        )));
    }
}
