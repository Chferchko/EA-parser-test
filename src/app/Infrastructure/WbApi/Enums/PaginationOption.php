<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Enums;

enum PaginationOption
{
    case Limit;
    case Page;

    public function toString(): string
    {
        return match ($this) {
            self::Limit => 'limit',
            self::Page => 'page',
        };
    }

    public function toDefault(): string
    {
        return match ($this) {
            self::Limit => '500',
            self::Page => '1',
        };
    }
}
