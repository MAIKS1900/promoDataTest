<?php
declare(strict_types=1);

namespace App\Repositories\DTO;

class ProductPriceInfoDTO
{
    /**
     * @param int $productId Id товара
     * @param string $productName Наименование товара
     * @param int $manufacturerId Id производителя
     * @param string $manufacturerName Наименование производителя
     * @param float|null $minPrice Минимальная цена за период
     * @param string|null $minPriceDate Последняя дата минимальной цены внутри периода
     * @param float|null $maxPrice Максимальная цена за период
     * @param string|null $maxPriceDate Последняя дата Максимальной цены внутри периода
     */
    public function __construct(
        public int     $productId,
        public string  $productName,
        public int     $manufacturerId,
        public string  $manufacturerName,
        public ?float  $minPrice,
        public ?string $minPriceDate,
        public ?float  $maxPrice,
        public ?string $maxPriceDate,
    ) {
    }
}
