<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Enums;

enum WbResponseKey
{
    case Data;

    public function toString(): string
    {
        return match ($this) {
            self::Data => 'data',
        };
    }
}
