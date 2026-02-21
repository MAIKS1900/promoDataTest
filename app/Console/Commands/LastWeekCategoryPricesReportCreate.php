<?php

namespace App\Console\Commands;

use App\Services\LastWeekPricesReport\CategoryPricesReportService;
use App\Services\LastWeekPricesReport\DTO\BuildItemDTO;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Throwable;

class LastWeekCategoryPricesReportCreate extends Command
{
    /** @var string Значение старта периода по умолчанию */
    private const DEFAULT_DATE_FROM = '-6 days';
    /** @var string Значение окончания периода по умолчанию */
    private const DEFAULT_DATE_TO = 'now';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:last-week-category-prices-report-create
                            {--C|categoryId= : Id категории товара, для которой строится отчет}
                            {--f|dateFrom= : Дата начала построения отчета, если не указано, то 7 дней назад}
                            {--t|dateTo= : Дата окончания построения отчета, если не указано, то сегодня}
                            ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Создание отчета по ценам товара';

    /**
     * Execute the console command.
     * @throws Throwable
     */
    public function handle(CategoryPricesReportService $reportService): void
    {
        $categoryId = $this->option('categoryId');

        if ($categoryId === null) {
            $this->fail(new InvalidArgumentException('Необходимо указать категорию для генерации отчета'));
        }

        $pid = getmypid();
        $startDate = now();

        $buildParams = new BuildItemDTO(
            categoryId: (int)$categoryId,
            pid: $pid,
            startDate: $startDate,
            dateFrom: CarbonImmutable::parse($this->option('dateFrom') ?? self::DEFAULT_DATE_FROM),
            dateTo: CarbonImmutable::parse($this->option('dateTo') ?? self::DEFAULT_DATE_TO),
        );

        try {
            Log::info('Начинаем сбор отчета по категории {categoryId} с {dateFrom} по {dateTo}', [
                'categoryId' => $buildParams->categoryId,
                'dateFrom' => $buildParams->dateFrom->toDateString(),
                'dateTo' => $buildParams->dateTo->toDateString(),
            ]);
            $reportService->build($buildParams);
        } catch (Throwable $exception) {
            Log::error('Не удалось создать отчет по категории {categoryId} с {dateFrom} по {dateTo}: {exception}', [
                'categoryId' => $categoryId,
                'dateFrom' => $buildParams->dateFrom->toDateString(),
                'dateTo' => $buildParams->dateTo->toDateString(),
                'exception' => $exception->getMessage(),
            ]);
            $this->fail($exception);
        }
        Log::info('Закончили сбор отчета по категории {categoryId} с {dateFrom} по {dateTo}', [
            'categoryId' => $buildParams->categoryId,
            'dateFrom' => $buildParams->dateFrom->toDateString(),
            'dateTo' => $buildParams->dateTo->toDateString(),
        ]);
    }
}
