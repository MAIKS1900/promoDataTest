<?php
declare(strict_types=1);

namespace App\Services\LastWeekPricesReport;

use App\Repositories\ReportProcessesRepository;
use App\Services\LastWeekPricesReport\DTO\BuildItemDTO;
use App\Services\LastWeekPricesReport\Helpers\ReportFileHelper;
use App\Services\LastWeekPricesReport\Helpers\ReportFileHelperFactory;
use Illuminate\Support\Facades\Log;
use Spatie\TemporaryDirectory\Exceptions\PathAlreadyExists;
use Spatie\TemporaryDirectory\TemporaryDirectory;
use Storage;
use Throwable;

class CategoryPricesReportService
{
    /** @var string Диск хранилища, куда складываются отчеты */
    public const STORAGE_DISK = 'reports';

    private const REPORT_FOLDER = 'last_week_category_prices_report';

    public function __construct(
        private readonly ReportProcessesRepository  $reportProcessesRepository,
        private readonly ProductPricesReportService $pricesReportService,
        private readonly ReportFileHelperFactory    $reportFileHelperFactory,
    ) {
    }

    /**
     * Создание отчета
     *
     * @param BuildItemDTO $buildItem
     * @return void
     * @throws PathAlreadyExists
     */
    public function build(BuildItemDTO $buildItem): void
    {
        $outDir = (new TemporaryDirectory())->name(implode('_', [
            mt_rand(),
            $buildItem->categoryId,
            $buildItem->dateFrom->toDateString(),
            $buildItem->dateTo->toDateString()
        ]))->create();
        $reportId = $this->reportProcessesRepository->startReport($buildItem->pid);

        try {
            $reportFileHelper = $this->reportFileHelperFactory->make($buildItem->categoryId, $buildItem->startDate, $outDir);

            $zipFilePath = $this->pricesReportService->build($buildItem, $reportFileHelper);
            $url = $this->storeReport($reportFileHelper, $zipFilePath);

            $this->reportProcessesRepository->markReportDone($reportId, $url);
        } catch (Throwable $exception) {
            Log::error('Ошибка формирования отчета по категории {categoryId} за период с {dateFrom} по {dateTo}: exception', [
                'categoryId' => $buildItem->categoryId,
                'dateFrom' => $buildItem->dateFrom->toDateString(),
                'dateTo' => $buildItem->dateTo->toDateString(),
                'exception' => $exception,
            ]);
            $this->reportProcessesRepository->markReportError($reportId);
        } finally {
            $outDir->delete();
        }
    }

    /**
     * Сохраняем файл с результатом на публичный url
     *
     * @param ReportFileHelper $reportFileHelper
     * @param string $zipFilePath
     * @return string
     */
    private function storeReport(ReportFileHelper $reportFileHelper, string $zipFilePath): string
    {
        $archivePath = self::REPORT_FOLDER . DIRECTORY_SEPARATOR . $reportFileHelper->getReportArchiveFileName();

        Storage::disk(self::STORAGE_DISK)->put($archivePath, file_get_contents($zipFilePath));
        return $archivePath;
    }
}
