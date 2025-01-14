<?php

declare(strict_types=1);

namespace App\Enums;

use Carbon\Carbon;

enum PeriodType: string
{
    case DAY = 'day';
    case WEEK = 'week';
    case MONTH = 'month';

    /**
     * Возвращает период как Carbon.
     *
     * @return Carbon
     */
    public function getStartDate(): Carbon
    {
        return match ($this) {
            self::DAY => Carbon::now()->startOfDay(),
            self::WEEK => Carbon::now()->startOfWeek(),
            self::MONTH => Carbon::now()->startOfMonth(),
        };
    }

    public static function default(): self
    {
        return self::DAY;
    }
}
