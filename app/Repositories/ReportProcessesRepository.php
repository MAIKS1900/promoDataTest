<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Domain\ReportStatusesInterface;
use App\Models\ReportProcess;
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
     * @param $reportId
     * @param string $filePath
     * @return void
     */
    public function markReportDone($reportId, string $filePath): void
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
     * @param $reportId
     * @return void
     */
    public function markReportError($reportId): void
    {
        $report = ReportProcess::find($reportId);

        if ($report === null) {
            throw new LogicException('Report not found. Попытка обновить несуществующий отчет ' . $reportId);
        }

        $report->update([
            'rp_exec_time' => (int)now()->diffInUTCSeconds($report->rp_start_datetime, true),
            'ps_id' => ReportStatusesInterface::ERROR,
        ]);
    }

    /**
     * Получить все процессы со статусами
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithStatus()
    {
        return ReportProcess::with('processStatus')
            ->orderBy('rp_start_datetime', 'desc')
            ->get();
    }
}
