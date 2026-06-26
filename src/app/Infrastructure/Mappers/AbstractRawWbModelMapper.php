<?php

declare(strict_types=1);

namespace App\Infrastructure\Mappers;

use App\Domain\Contracts\WbEntityInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractRawWbModelMapper implements WbModelMapperInterface
{
    public function toAttributes(WbEntityInterface $entity): array
    {
        return [
            'payload_hash' => $entity->uniqueKey(),
            'payload' => $this->payload($entity),
        ];
    }

    public function toDomain(Model $model): WbEntityInterface
    {
        $payload = $model->getAttribute('payload');

        return $this->makeDomain(
            (string) $model->getAttribute('payload_hash'),
            \is_array($payload) ? $payload : [],
        );
    }

    abstract protected function payload(WbEntityInterface $entity): array;

    /**
     * @param array<string, mixed> $payload
     */
    abstract protected function makeDomain(string $payloadHash, array $payload): WbEntityInterface;
}
