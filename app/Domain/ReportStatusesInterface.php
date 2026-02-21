<?php
declare(strict_types=1);

namespace App\Domain;

enum ReportStatusesInterface: int
{
    /** Id статуса - Запуск */
    case START = 1;

    /** Id статуса - Завершен */
    case DONE = 2;

    /** Id статуса - Ошибка */
    case ERROR = 3;
}
