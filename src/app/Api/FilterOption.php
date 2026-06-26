<?php

declare(strict_types=1);

namespace App\Api;

enum FilterOption
{
    case DateFrom;
    case DateTo;

    public function toString(): string
    {
        return match ($this) {
            self::DateFrom => 'dateFrom',
            self::DateTo => 'dateTo',
        };
    }
}
