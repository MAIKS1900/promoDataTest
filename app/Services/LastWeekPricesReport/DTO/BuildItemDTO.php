<?php
declare(strict_types=1);

namespace App\Services\LastWeekPricesReport\DTO;

use Carbon\CarbonImmutable;
use DateTimeInterface;

readonly class BuildItemDTO
{
    public function __construct(
        public int               $categoryId,
        public int               $pid,
        public DateTimeInterface $startDate,
        public CarbonImmutable   $dateFrom,
        public CarbonImmutable   $dateTo,
    ) {
    }
}
