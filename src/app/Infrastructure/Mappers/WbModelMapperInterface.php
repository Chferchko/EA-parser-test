<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Contracts\WbEntityInterface;
use Illuminate\Database\Eloquent\Model;

interface WbModelMapperInterface
{
    /**
     * @return array<string, mixed>
     */
    public function toAttributes(WbEntityInterface $entity): array;

    public function toDomain(Model $model): WbEntityInterface;
}
