<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Infrastructure\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/**
 * @coversNothing
 */
final class WbSyncStocksCommandTest extends TestCase
{
    use RefreshDatabase;

    public function testItSynchronizesStocksAndSeparatesCreatedUpdatedInvalidAndOutdated(): void
    {
        Stock::query()->create([
            'date' => '2026-06-25',
            'last_change_date' => '2026-06-25',
            'supplier_article' => 'existing-update',
            'tech_size' => 'M',
            'barcode' => 1001,
            'quantity' => 1,
            'is_supply' => true,
            'is_realization' => false,
            'quantity_full' => 1,
            'warehouse_name' => 'WH-UPDATE',
            'in_way_to_client' => 0,
            'in_way_from_client' => 0,
            'nm_id' => 10,
            'subject' => 'subject',
            'category' => 'category',
            'brand' => 'brand',
            'sc_code' => 100,
            'price' => 1000,
            'discount' => 10,
        ]);

        Stock::query()->create([
            'date' => '2026-06-26',
            'last_change_date' => '2026-06-26',
            'supplier_article' => 'existing-outdated',
            'tech_size' => 'L',
            'barcode' => 1002,
            'quantity' => 5,
            'is_supply' => false,
            'is_realization' => true,
            'quantity_full' => 5,
            'warehouse_name' => 'WH-OUTDATED',
            'in_way_to_client' => 1,
            'in_way_from_client' => 1,
            'nm_id' => 20,
            'subject' => 'subject',
            'category' => 'category',
            'brand' => 'brand',
            'sc_code' => 200,
            'price' => 2000,
            'discount' => 20,
        ]);

        Http::fake([
            '*' => Http::response([
                'data' => [
                    [
                        'date' => '2026-06-26',
                        'last_change_date' => '2026-06-26',
                        'supplier_article' => 'new-stock',
                        'tech_size' => 'S',
                        'barcode' => 2001,
                        'quantity' => 7,
                        'is_supply' => 1,
                        'is_realization' => 0,
                        'quantity_full' => 7,
                        'warehouse_name' => 'WH-NEW',
                        'in_way_to_client' => 0,
                        'in_way_from_client' => 0,
                        'nm_id' => 30,
                        'subject' => 'new-subject',
                        'category' => 'new-category',
                        'brand' => 'new-brand',
                        'sc_code' => 300,
                        'price' => 3000,
                        'discount' => 30,
                    ],
                    [
                        'date' => '2026-06-27',
                        'last_change_date' => '2026-06-27',
                        'supplier_article' => 'updated-stock',
                        'tech_size' => 'XL',
                        'barcode' => 1001,
                        'quantity' => 9,
                        'is_supply' => 0,
                        'is_realization' => 1,
                        'quantity_full' => 9,
                        'warehouse_name' => 'WH-UPDATE',
                        'in_way_to_client' => 2,
                        'in_way_from_client' => 3,
                        'nm_id' => 11,
                        'subject' => 'updated-subject',
                        'category' => 'updated-category',
                        'brand' => 'updated-brand',
                        'sc_code' => 101,
                        'price' => 1100,
                        'discount' => 11,
                    ],
                    [
                        'date' => '2026-06-25',
                        'last_change_date' => '2026-06-25',
                        'supplier_article' => 'outdated-stock',
                        'tech_size' => 'XS',
                        'barcode' => 1002,
                        'quantity' => 99,
                        'is_supply' => 1,
                        'is_realization' => 0,
                        'quantity_full' => 99,
                        'warehouse_name' => 'WH-OUTDATED',
                        'in_way_to_client' => 9,
                        'in_way_from_client' => 9,
                        'nm_id' => 99,
                        'subject' => 'outdated-subject',
                        'category' => 'outdated-category',
                        'brand' => 'outdated-brand',
                        'sc_code' => 999,
                        'price' => 9999,
                        'discount' => 99,
                    ],
                    [
                        'date' => '2026-06-26',
                        'supplier_article' => 'invalid-stock',
                        'tech_size' => 'M',
                        'barcode' => 3001,
                        'quantity' => 1,
                        'is_supply' => 1,
                        'is_realization' => 0,
                        'quantity_full' => 1,
                        'warehouse_name' => 'WH-INVALID',
                        'in_way_to_client' => 0,
                        'in_way_from_client' => 0,
                        'nm_id' => 40,
                        'subject' => 'subject',
                        'category' => 'category',
                        'brand' => 'brand',
                        'sc_code' => 400,
                        'price' => 4000,
                        'discount' => 40,
                    ],
                ],
            ]),
        ]);

        $this->artisan('wb:sync-stocks')
            ->expectsOutputToContain('skipped invalid item #4')
            ->expectsOutput('Done. Processed: 2. Created: 1. Updated: 1. Skipped invalid: 1. Skipped outdated: 1.')
            ->assertSuccessful();

        $this->assertDatabaseHas('stocks', [
            'barcode' => 2001,
            'warehouse_name' => 'WH-NEW',
            'supplier_article' => 'new-stock',
        ]);

        $this->assertDatabaseHas('stocks', [
            'barcode' => 1001,
            'warehouse_name' => 'WH-UPDATE',
            'supplier_article' => 'updated-stock',
            'quantity' => 9,
        ]);

        $this->assertDatabaseHas('stocks', [
            'barcode' => 1002,
            'warehouse_name' => 'WH-OUTDATED',
            'supplier_article' => 'existing-outdated',
            'quantity' => 5,
        ]);

        self::assertSame(
            '2026-06-26',
            Stock::query()->where('barcode', 2001)->where('warehouse_name', 'WH-NEW')->firstOrFail()->last_change_date->format('Y-m-d'),
        );

        self::assertSame(
            '2026-06-27',
            Stock::query()->where('barcode', 1001)->where('warehouse_name', 'WH-UPDATE')->firstOrFail()->last_change_date->format('Y-m-d'),
        );

        self::assertSame(
            '2026-06-26',
            Stock::query()->where('barcode', 1002)->where('warehouse_name', 'WH-OUTDATED')->firstOrFail()->last_change_date->format('Y-m-d'),
        );

        self::assertSame(3, Stock::query()->count());
    }

    public function testItHandlesEmptyStocksResponse(): void
    {
        Http::fake([
            '*' => Http::response([
                'data' => [],
            ]),
        ]);

        $this->artisan('wb:sync-stocks')
            ->expectsOutput('WB sync for "stocks" returned empty data set.')
            ->expectsOutput('Done. Processed: 0. Created: 0. Updated: 0. Skipped invalid: 0. Skipped outdated: 0.')
            ->assertSuccessful();
    }

    public function testItFailsWhenStocksApiRequestFails(): void
    {
        Http::fake([
            '*' => Http::response([
                'message' => 'Server error',
            ], 500),
        ]);

        $this->artisan('wb:sync-stocks')
            ->expectsOutputToContain('WB sync for "stocks" failed:')
            ->assertFailed();
    }

    public function testItPassesLimitOptionToStocksApiRequest(): void
    {
        Http::fake([
            '*' => Http::response([
                'data' => [],
            ]),
        ]);

        $this->artisan('wb:sync-stocks --limit=123')
            ->assertSuccessful();

        Http::assertSent(function ($request): bool {
            return str_starts_with($request->url(), 'http://109.73.206.144:6969/api/stocks')
                && $request['limit'] === '123'
                && $request['page'] === '1'
                && $request['dateFrom'] === now()->format('Y-m-d');
        });
    }
}
