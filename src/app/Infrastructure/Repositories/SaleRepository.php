<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\SaleRepositoryInterface;
use App\Infrastructure\Mappers\SaleModelMapper;
use App\Infrastructure\Models\Sale;

final class SaleRepository extends AbstractEloquentWbEntityRepository implements SaleRepositoryInterface
{
    public function __construct(SaleModelMapper $mapper)
    {
        parent::__construct($mapper, Sale::class, 'payload_hash');
    }
}
