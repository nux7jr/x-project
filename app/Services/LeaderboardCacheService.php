<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\Interfaces\HistoryPointsRepositoryInterface;
use App\Enums\PeriodType;
use Illuminate\Support\Collection;

final class LeaderboardCacheService extends BaseCacheService
{
    protected const CACHE_PREFIX = 'leaderboard';
    protected const KEY_TRACKER = 'leaderboard_cache_keys';

    private HistoryPointsRepositoryInterface $historyPointsRepository;

    public function __construct(HistoryPointsRepositoryInterface $historyPointsRepository)
    {
        $this->historyPointsRepository = $historyPointsRepository;
    }

    /**
     * Добавить очки пользователю и сбросить соответствующие кеши.
     */
    public function updateUserPoints(string $userId, int $points): int
    {
        $newScore = $this->historyPointsRepository->updatePoints($userId, $points);

        $this->clearCacheByKey('top');
        $this->clearCacheByKey('user');

        return $newScore;
    }

    /**
     * Получить очки пользователя за период.
     */
    public function getUserScore(string $userId, PeriodType $period): int
    {
        $cacheKey = $this->buildCacheKey('user', $userId, 'score', $period->value);

        return $this->cacheRemember($cacheKey, 10, function () use ($userId, $period) {
            return $this->historyPointsRepository->userScore($userId, $period);
        });
    }

    /**
     * Получить ранг пользователя за период.
     */
    public function getUserRank(string $userId, PeriodType $period): int
    {
        $cacheKey = $this->buildCacheKey('user', $userId, 'rank', $period->value);

        return $this->cacheRemember($cacheKey, 10, function () use ($userId, $period) {
            return $this->historyPointsRepository->userRank($userId, $period);
        });
    }

    /**
     * Получить топ пользователей за период.
     */
    public function getTopUsers(PeriodType $period, int $limit = 10): Collection
    {
        $cacheKey = $this->buildCacheKey('top', $period->value, "limit-{$limit}");

        return $this->cacheRemember($cacheKey, 10, function () use ($period, $limit) {
            return $this->historyPointsRepository->topUsers($period, $limit);
        });
    }
    /**
     * Удалить кеш для всех топов.
     */
    public function clearCacheByKey(string $key): void
    {
        $prefix = $this->buildCacheKey($key);
        $this->clearCacheByPrefix($prefix);
    }
}
