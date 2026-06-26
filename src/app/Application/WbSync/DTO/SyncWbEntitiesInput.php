<?php

declare(strict_types=1);

namespace App\Application\WbSync\DTO;

use App\Infrastructure\WbApi\Enums\WbApiEndpoint;
use App\Infrastructure\WbApi\Filters\QueryFilterInterface;
use App\Infrastructure\WbApi\Pagination\Pagination;

final readonly class SyncWbEntitiesInput
{
    public function __construct(
        public WbApiEndpoint $endpoint,
        public QueryFilterInterface $filter,
        public Pagination $pagination,
    ) {}
}
