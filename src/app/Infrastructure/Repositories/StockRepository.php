<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\StockRepositoryInterface;
use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Entities\Stock as StockEntity;
use App\Domain\Enums\PersistenceOutcome;
use App\Infrastructure\Mappers\StockModelMapper;
use App\Infrastructure\Models\Stock;

final class StockRepository extends AbstractEloquentWbEntityRepository implements StockRepositoryInterface
{
    public function __construct(StockModelMapper $mapper)
    {
        parent::__construct($mapper, Stock::class, 'barcode');
    }

    public function save(WbEntityInterface $entity): PersistenceOutcome
    {
        if (!$entity instanceof StockEntity) {
            throw new \InvalidArgumentException('Stock entity expected.');
        }

        $model = Stock::query()
            ->where('barcode', $entity->barcode)
            ->where('warehouse_name', $entity->warehouseName)
            ->first()
        ;

        if ($model === null) {
            $model = new Stock();
            $model->fill($this->mapper->toAttributes($entity));
            $model->save();

            return PersistenceOutcome::Created;
        }

        $storedLastChangeDate = $model->getAttribute('last_change_date');

        if (!$storedLastChangeDate instanceof \DateTimeInterface) {
            throw new \UnexpectedValueException('Stored stock last_change_date is invalid.');
        }

        $storedLastChangeDate = \DateTimeImmutable::createFromInterface($storedLastChangeDate);

        if ($entity->lastChangeDate <= $storedLastChangeDate) {
            return PersistenceOutcome::SkippedOutdated;
        }

        $model->fill($this->mapper->toAttributes($entity));
        $model->save();

        return PersistenceOutcome::Updated;
    }
}
