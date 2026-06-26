<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Contracts\WbEntityInterface;
use App\Domain\Contracts\WbEntityRepositoryInterface;
use App\Domain\Enums\PersistenceOutcome;
use App\Infrastructure\Mappers\WbModelMapperInterface;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractEloquentWbEntityRepository implements WbEntityRepositoryInterface
{
    /**
     * @param class-string<Model> $modelClass
     */
    public function __construct(
        protected readonly WbModelMapperInterface $mapper,
        private readonly string $modelClass,
        private readonly string $uniqueColumn,
    ) {}

    public function save(WbEntityInterface $entity): PersistenceOutcome
    {
        $model = $this->newModel()->newQuery()->firstWhere($this->uniqueColumn, $entity->uniqueKey());
        $outcome = $model === null ? PersistenceOutcome::Created : PersistenceOutcome::Updated;
        $model ??= $this->newModel();

        $model->fill($this->mapper->toAttributes($entity));
        $model->save();

        return $outcome;
    }

    private function newModel(): Model
    {
        $modelClass = $this->modelClass;

        return new $modelClass();
    }
}
