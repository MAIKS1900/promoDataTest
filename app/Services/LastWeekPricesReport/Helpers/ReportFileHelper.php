<?php
declare(strict_types=1);

namespace App\Services\LastWeekPricesReport\Helpers;

use DateTimeInterface;
use Spatie\TemporaryDirectory\TemporaryDirectory;

readonly class ReportFileHelper
{
    public function __construct(
        private int               $categoryId,
        private DateTimeInterface $startDate,
        public TemporaryDirectory $tmpDir,
    ) {
    }

    /**
     * Наименование файла архива с отчетами
     *
     * @return string
     */
    public function getReportArchiveFileName(): string
    {
        return "report_{$this->categoryId}_{$this->startDate->format('Y-m-d_H-i-s')}.zip";
    }

    /**
     * Наименование отчета для каждого производителя
     *
     * @param int $manufacturerId
     * @return string
     */
    public function getManufacturerReportFileName(int $manufacturerId): string
    {
        return "report_{$manufacturerId}_{$this->categoryId}_{$this->startDate->format('Y-m-d_H-i-s')}.csv";
    }
}
