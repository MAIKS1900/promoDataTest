<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $ps_id
 * @property string $ps_name Наименование статуса
 * @method static Builder<static>|ProcessStatus newModelQuery()
 * @method static Builder<static>|ProcessStatus newQuery()
 * @method static Builder<static>|ProcessStatus query()
 * @method static Builder<static>|ProcessStatus wherePsId($value)
 * @method static Builder<static>|ProcessStatus wherePsName($value)
 * @mixin Eloquent
 */
class ProcessStatus extends Model
{
    /** @inheritdoc */
    protected $primaryKey = 'ps_id';

    /** @inheritdoc */
    public $timestamps = false;
}
