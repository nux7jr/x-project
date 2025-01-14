<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

use App\Enums\PeriodType;
interface HistoryPointsRepositoryInterface
{
    public function updatePoints(string $userId, int $points);

    public function userScore(string $userId, PeriodType $period);

    public function userRank(string $userId, PeriodType $period);

    public function topUsers(PeriodType $period, int $limit = 10);
}
