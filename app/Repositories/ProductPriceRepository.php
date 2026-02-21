<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\DTO\ProductPriceInfoDTO;
use Carbon\CarbonImmutable;
use Generator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class ProductPriceRepository
{
    /** @var int Количество товаров при запросе на 1 страницу */
    private const MAX_PRODUCTS_PER_PAGE = 1000;

    /**
     * Получает минимальные и максимальные цены товаров по категории за указанный период
     *
     * @param int $categoryId
     * @param CarbonImmutable $dateFrom
     * @param CarbonImmutable $dateTo
     * @return Generator<ProductPriceInfoDTO>
     * @throws RuntimeException
     */
    public function getMinMaxPricesByCategoryLastWeek(int $categoryId, CarbonImmutable $dateFrom, CarbonImmutable $dateTo): Generator
    {
        $productsExists = DB::table('products')
            ->where('category_id', $categoryId)
            ->exists();

        if ($productsExists === false) {
            throw new RuntimeException("В категории с ID {$categoryId} нет товаров");
        }

        $sql = $this->getSqlQuery();
        $bindings = [
            'categoryId' => $categoryId,
            'dateFrom' => $dateFrom->toDateString(),
            'dateTo' => $dateTo->toDateString(),
            'limit' => self::MAX_PRODUCTS_PER_PAGE,
            'lastProductId' => -1
        ];
        $totalProcessed = 0;
        do {
            $startTime = microtime(true);
            $results = DB::select($sql, $bindings);
            $queryTime = microtime(true) - $startTime;
            $resultCount = count($results);

            foreach ($results as $row) {
                yield new ProductPriceInfoDTO(
                    productId: $row->product_id,
                    productName: $row->product_name,
                    manufacturerId: $row->manufacturer_id,
                    manufacturerName: $row->manufacturer_name,
                    minPrice: is_numeric($row->min_price) ? (float)$row->min_price : null,
                    minPriceDate: $row->min_price_date,
                    maxPrice: is_numeric($row->max_price) ? (float)$row->max_price : null,
                    maxPriceDate: $row->max_price_date,
                );

                $bindings['lastProductId'] = $row->product_id;
                $totalProcessed++;
            }
            Log::debug("Обработано пакет: {$resultCount} записей за " . round($queryTime, 3) . "с, всего: {$totalProcessed}");
        } while ($resultCount === self::MAX_PRODUCTS_PER_PAGE);
    }

    /**
     * Запрос на получение минимальных и максимальных цен товара с учетом пагинации по lastProductId
     *
     * @return string
     */
    private function getSqlQuery(): string
    {
        return "
            SELECT
                p.product_id,
                m.manufacturer_id,
                m.manufacturer_name,
                p.product_name,
                p.category_id,
                ROUND(minp.min_price, 2)  AS min_price,
                minp.min_price_date,
                ROUND(maxp.max_price, 2)  AS max_price,
                maxp.max_price_date
            
            FROM products p
            
            INNER JOIN manufacturers m ON p.manufacturer_id = m.manufacturer_id
            
            LEFT JOIN LATERAL (
                SELECT pr.price AS min_price,
                    pr.price_date AS min_price_date
                FROM prices pr
                WHERE pr.product_id = p.product_id
                    AND pr.price_date BETWEEN :dateFrom AND :dateTo
                ORDER BY pr.price ASC, pr.price_date DESC
                LIMIT 1
            ) minp ON TRUE
            
            LEFT JOIN LATERAL (
                SELECT pr.price AS max_price,
                    pr.price_date AS max_price_date
                FROM prices pr
                WHERE pr.product_id = p.product_id
                    AND pr.price_date BETWEEN :dateFrom AND :dateTo
                ORDER BY pr.price DESC, pr.price_date DESC
                LIMIT 1
            ) maxp ON TRUE
            
            WHERE p.category_id = :categoryId
                AND p.product_id > :lastProductId
            
            ORDER BY p.product_id
            LIMIT :limit
        ";
    }
}
