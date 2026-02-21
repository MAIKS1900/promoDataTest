<?php
declare(strict_types=1);

namespace App\Services\LastWeekPricesReport\Helpers;

use DateTimeInterface;
use Spatie\TemporaryDirectory\TemporaryDirectory;

class ReportFileHelperFactory
{
    /**
     * Создание экземпляра помощника наименований
     *
     * @param int $categoryId
     * @param DateTimeInterface $startDate
     * @param TemporaryDirectory $tmpDir
     * @return ReportFileHelper
     */
    public function make(int $categoryId, DateTimeInterface $startDate, TemporaryDirectory $tmpDir): ReportFileHelper
    {
        return new ReportFileHelper($categoryId, $startDate, $tmpDir);
    }
}
