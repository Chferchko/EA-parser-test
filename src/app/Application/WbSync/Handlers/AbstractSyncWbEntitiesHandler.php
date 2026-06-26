<?php

declare(strict_types=1);

namespace App\Application\WbSync\Handlers;

use App\Application\WbSync\DTO\SyncWbEntitiesInput;
use App\Application\WbSync\DTO\SyncWbEntitiesResult;
use App\Domain\Contracts\WbEntityNormalizerInterface;
use App\Domain\Contracts\WbEntityRepositoryInterface;
use App\Infrastructure\WbApi\Client\WbApiClient;

abstract class AbstractSyncWbEntitiesHandler
{
    public function __construct(
        private readonly WbApiClient $client,
        private readonly WbEntityNormalizerInterface $normalizer,
        private readonly WbEntityRepositoryInterface $repository,
    ) {}

    final public function handle(SyncWbEntitiesInput $input): SyncWbEntitiesResult
    {
        $result = new SyncWbEntitiesResult();

        try {
            $items = $this->client->get($input->endpoint, $input->filter, $input->pagination);
        } catch (\Throwable $exception) {
            $result->addError(\sprintf(
                'WB sync for "%s" failed: %s',
                $input->endpoint->toString(),
                $exception->getMessage(),
            ));

            return $result;
        }

        if ($items === []) {
            $result->addWarning(\sprintf(
                'WB sync for "%s" returned empty data set.',
                $input->endpoint->toString(),
            ));

            return $result;
        }

        return $this->process($items, $result, $input);
    }

    /**
     * @param list<array<string, mixed>> $items
     */
    private function process(array $items, SyncWbEntitiesResult $result, SyncWbEntitiesInput $input): SyncWbEntitiesResult
    {
        foreach ($items as $index => $item) {
            try {
                $entity = $this->normalizer->normalize($item);
                $result->registerPersistence($this->repository->save($entity));
            } catch (\Throwable $exception) {
                $result->registerInvalidSkipped(\sprintf(
                    'WB sync for "%s" skipped invalid item #%d: %s',
                    $input->endpoint->toString(),
                    $index + 1,
                    $exception->getMessage(),
                ));
            }
        }

        return $result;
    }
}
