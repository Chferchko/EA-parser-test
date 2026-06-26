<?php

declare(strict_types=1);

namespace App\Infrastructure\WbApi\Client;

use App\Infrastructure\WbApi\Auth\AuthStrategyInterface;
use App\Infrastructure\WbApi\Enums\WbApiEndpoint;
use App\Infrastructure\WbApi\Enums\WbResponseKey;
use App\Infrastructure\WbApi\Filters\QueryFilterInterface;
use App\Infrastructure\WbApi\Pagination\Pagination;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory as HttpFactory;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

final readonly class WbApiClient
{
    public function __construct(
        private HttpFactory $http,
        private string $baseUrl,
        private AuthStrategyInterface $authStrategy,
    ) {}

    /**
     * @return list<array<string, mixed>>
     *
     * @throws RequestException
     * @throws ConnectionException
     */
    public function get(WbApiEndpoint $endpoint, QueryFilterInterface $filter, Pagination $pagination): array
    {
        $response = $this->sendRequest(
            $endpoint,
            $this->buildQuery($filter, $pagination),
        );

        return $this->extractValidatedData($response);
    }

    /**
     * @return array<string, string>
     */
    private function buildQuery(QueryFilterInterface $filter, Pagination $pagination): array
    {
        return $this->authStrategy->apply(
            array_merge($filter->toQuery(), $pagination->toQuery()),
        );
    }

    /**
     * @param array<string, string> $query
     *
     * @throws ConnectionException
     */
    private function sendRequest(WbApiEndpoint $endpoint, array $query): Response
    {
        return $this->http->get(
            \sprintf('%s%s', $this->baseUrl, $endpoint->toString()),
            $query,
        );
    }

    /**
     * @return list<array<string, mixed>>
     *
     * @throws RequestException
     */
    private function extractValidatedData(Response $response): array
    {
        $response->throw();

        $data = $response->json(WbResponseKey::Data->toString());

        if (!\is_array($data)) {
            throw new \UnexpectedValueException('WB API response invalid "data"');
        }

        foreach ($data as $item) {
            if (!\is_array($item)) {
                throw new \UnexpectedValueException('WB API response "data" must contain only arrays');
            }
        }

        return $data;
    }
}
