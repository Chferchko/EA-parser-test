<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\OrderRepositoryInterface;
use App\Infrastructure\Mappers\OrderModelMapper;
use App\Infrastructure\Models\Order;

final class OrderRepository extends AbstractEloquentWbEntityRepository implements OrderRepositoryInterface
{
    public function __construct(OrderModelMapper $mapper)
    {
        parent::__construct($mapper, Order::class, 'payload_hash');
    }
}
