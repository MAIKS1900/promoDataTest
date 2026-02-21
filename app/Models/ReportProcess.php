<?php

namespace App\Models;

use Carbon\CarbonInterface;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $rp_id
 * @property int $rp_pid Идентификатор процесса
 * @property CarbonInterface $rp_start_datetime Дата/время начала процесса
 * @property int $rp_exec_time Время выполнения
 * @property int $ps_id Статус процесса
 * @property string|null $rp_file_save_path Путь к сохраненному файлу
 * @property-read string|null $status_name
 * @method static Builder<static>|ReportProcess newModelQuery()
 * @method static Builder<static>|ReportProcess newQuery()
 * @method static Builder<static>|ReportProcess query()
 * @method static Builder<static>|ReportProcess wherePsId($value)
 * @method static Builder<static>|ReportProcess whereRpExecTime($value)
 * @method static Builder<static>|ReportProcess whereRpFileSavePath($value)
 * @method static Builder<static>|ReportProcess whereRpId($value)
 * @method static Builder<static>|ReportProcess whereRpPid($value)
 * @method static Builder<static>|ReportProcess whereRpStartDatetime($value)
 * @property-read ProcessStatus|null $processStatus
 * @mixin Eloquent
 */
class ReportProcess extends Model
{
    /** @var string */
    public const CREATED_AT = 'rp_start_datetime';

    /** @var string */
    public const UPDATED_AT = null;

    /** @inheritdoc */
    protected $primaryKey = 'rp_id';

    /** @inheritdoc */
    protected $fillable = [
        'rp_pid',
        'rp_exec_time',
        'ps_id',
        'rp_file_save_path',
    ];

    /**
     * Атрибут для получения наименования статуса
     *
     * @return string|null
     */
    public function getStatusNameAttribute(): ?string
    {
        return $this->status->ps_name ?? null;
    }

    /**
     * Статус процесса
     *
     * @return BelongsTo
     */
    public function processStatus(): BelongsTo
    {
        return $this->belongsTo(ProcessStatus::class, 'ps_id', 'ps_id');
    }
}
