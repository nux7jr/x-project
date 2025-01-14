<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Repositories\Interfaces\HistoryPointsRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\HistoryPoints;
use App\Enums\PeriodType;

class HistoryPointsRepository implements HistoryPointsRepositoryInterface
{
    /**
     * Добавить очки пользователю.
     *
     * @param string $userId
     * @param int    $points
     * @return int Общая сумма очков пользователя
     */
    public function updatePoints(string $userId, int $points): int
    {
        HistoryPoints::create([
            'user_id' => $userId,
            'score' => $points,
        ]);

        $totalPoints = HistoryPoints::where('user_id', $userId)->sum('score');

        return (int) $totalPoints;
    }

    /**
     * Получить историю очков пользователя за указанный период.
     *
     * @param string     $userId
     * @param PeriodType $period
     * @return int Сумма очков за период
     */
    public function userScore(string $userId, PeriodType $period): int
    {
        return (int) HistoryPoints::where('user_id', $userId)
            ->where('created_at', '>=', $period->getStartDate())
            ->sum('score');
    }
    /**
     * Получить ранг пользователя за указанный период.
     *
     * @param string     $userId
     * @param PeriodType $period
     * @return int Ранг за период
     */
    public function userRank(string $userId, PeriodType $period): int
    {
        $rank = HistoryPoints::selectRaw('user_id, SUM(score) AS total_points')
            ->where('created_at', '>=', $period->getStartDate())
            ->groupBy('user_id')
            ->orderByDesc('total_points')
            ->get()
            ->pluck('user_id')
            ->search($userId);

        return $rank !== false ? $rank + 1 : 0;
    }


    /**
     * Получить топ пользователей за указанный период.
     *
     * @param PeriodType $period
     * @param int        $limit
     * @return Collection Топ пользователей с их очками
     */
    public function topUsers(PeriodType $period, int $limit = 10): Collection
    {
        return HistoryPoints::selectRaw('user_id, SUM(score) as total_score')
        ->where('created_at', '>=', $period->getStartDate())
        ->with('user:id,username')
        ->groupBy('user_id')
        ->orderByDesc('total_score')
        ->take($limit)
        ->get()
        ->map(function ($item) {
            return [
                'user_id' => $item->user_id,
                'username' => $item->user->username,
                'score' => $item->total_score,
            ];
        });
    }
}
