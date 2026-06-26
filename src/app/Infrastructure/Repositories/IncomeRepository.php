<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\IncomeRepositoryInterface;
use App\Infrastructure\Mappers\IncomeModelMapper;
use App\Infrastructure\Models\Income;

final class IncomeRepository extends AbstractEloquentWbEntityRepository implements IncomeRepositoryInterface
{
    public function __construct(IncomeModelMapper $mapper)
    {
        parent::__construct($mapper, Income::class, 'payload_hash');
    }
}
