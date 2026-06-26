<?php

declare(strict_types=1);

namespace App\Api;

enum WbApiEndpoint
{
    case Stocks;
    case Incomes;
    case Sales;
    case Orders;

    public function toString(): string
    {
        return match ($this) {
            self::Stocks => 'stocks',
            self::Incomes => 'incomes',
            self::Sales => 'sales',
            self::Orders => 'orders',
        };
    }
}
