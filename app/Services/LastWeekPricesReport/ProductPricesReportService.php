<?php
declare(strict_types=1);

namespace App\Services\LastWeekPricesReport;

use App\Repositories\ProductPriceRepository;
use App\Services\LastWeekPricesReport\DTO\BuildItemDTO;
use App\Services\LastWeekPricesReport\Helpers\ArrayHelper;
use App\Services\LastWeekPricesReport\Helpers\ReportFileHelper;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use ZipArchive;

class ProductPricesReportService
{
    public function __construct(
        private readonly ProductPriceRepository $productPriceRepository,
    ) {
    }

    /**
     * Создание оптимизированного отчета
     *
     * @param BuildItemDTO $buildItem
     * @param ReportFileHelper $reportFileHelper
     * @return string - Путь к архиву с файлами
     */
    public function build(BuildItemDTO $buildItem, ReportFileHelper $reportFileHelper): string
    {
        $dataGenerator = $this->productPriceRepository->getMinMaxPricesByCategoryLastWeek(
            $buildItem->categoryId,
            $buildItem->dateFrom,
            $buildItem->dateTo,
        );
        $groupedByManufacturer = [];
        foreach ($dataGenerator as $productPriceInfo) {
            $manufacturerId = $productPriceInfo->manufacturerId;
            if (!array_key_exists($manufacturerId, $groupedByManufacturer)) {
                $groupedByManufacturer[$manufacturerId] = [[
                    'manufacturer_name',
                    'product_name',
                    'price',
                    'price_date',
                ]];
            }

            $groupedByManufacturer[$manufacturerId][] = [
                $productPriceInfo->manufacturerName,
                $productPriceInfo->productName,
                $productPriceInfo->minPrice,
                $productPriceInfo->minPriceDate
            ];

            $groupedByManufacturer[$manufacturerId][] = [
                $productPriceInfo->manufacturerName,
                $productPriceInfo->productName,
                $productPriceInfo->maxPrice,
                $productPriceInfo->maxPriceDate
            ];
        }

        return $this->createZipArchive($reportFileHelper, $groupedByManufacturer);

    }

    /**
     * Создание ZIP архива с файлами отчета
     *
     * @param ReportFileHelper $reportFileHelper
     * @param array<int, array> $groupedByManufacturer
     * @return string
     */
    private function createZipArchive(ReportFileHelper $reportFileHelper, array $groupedByManufacturer): string
    {
        $zipPath = $reportFileHelper->tmpDir->path('report.zip');
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new RuntimeException("Не удалось создать ZIP архив: {$zipPath}");
        }

        foreach ($groupedByManufacturer as $manufacturerId => $infoByManufacturer) {
            $fileName = $reportFileHelper->getManufacturerReportFileName($manufacturerId);
            if (!$zip->addFromString($fileName, ArrayHelper::arrayToCsv($infoByManufacturer))) {
                Log::warning("Не удалось добавить файл в архив: {filePath}", ['fileName' => $fileName]);
            }
        }

        if (!$zip->close()) {
            throw new RuntimeException("Не удалось закрыть ZIP архив");
        }

        return $zipPath;
    }
}
