<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\ReportStatusesInterface;
use App\Models\ReportProcess;
use Illuminate\Database\Eloquent\Collection;
use LogicException;

class ReportProcessesRepository
{
    /**
     * Создание нового отчета
     *
     * @param int $pid
     * @return int
     */
    public function startReport(int $pid): int
    {
        $report = ReportProcess::create([
            'rp_pid' => $pid,
            'ps_id' => ReportStatusesInterface::START,
        ]);

        return $report->rp_id;
    }

    /**
     * Пометить отчет как выполненный
     *
     * @param int $reportId
     * @param string $filePath
     * @return void
     */
    public function markReportDone(int $reportId, string $filePath): void
    {
        $report = ReportProcess::find($reportId);

        if ($report === null) {
            throw new LogicException('Report not found. Попытка обновить несуществующий отчет ' . $reportId);
        }

        $report->update([
            'rp_exec_time' => (int)now()->diffInSeconds($report->rp_start_datetime, true),
            'ps_id' => ReportStatusesInterface::DONE,
            'rp_file_save_path' => $filePath
        ]);
    }

    /**
     * Пометить отчет как выполненный
     *
     * @param int $reportId
     * @return void
     */
    public function markReportError(int $reportId): void
    {
        $report = ReportProcess::find($reportId);

        if ($report === null) {
            throw new LogicException('Report not found. Попытка обновить несуществующий отчет ' . $reportId);
        }

        $report->update([
            'rp_exec_time' => (int)now()->diffInSeconds($report->rp_start_datetime, true),
            'ps_id' => ReportStatusesInterface::ERROR,
        ]);
    }

    /**
     * Получить все процессы со статусами
     *
     * @return Collection<int, ReportProcess>
     */
    public function getAllWithStatus(): Collection
    {
        return ReportProcess::with('processStatus')
            ->orderBy('rp_start_datetime', 'desc')
            ->get();
    }
}
