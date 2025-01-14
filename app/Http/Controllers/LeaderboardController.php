<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\AddPointsRequest;
use App\Http\Requests\PeriodRequest;
use App\Services\LeaderboardCacheService;
use App\Repositories\Interfaces\UserRepositoryInterface;

class LeaderboardController extends Controller
{
    public function __construct(
        private readonly LeaderboardCacheService $leaderboardCacheService,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Топ 10 ладера
     *
     * @param  mixed $request
     * @return JsonResponse
     */
    public function index(PeriodRequest $request): JsonResponse
    {
        $period = $request->getPeriodType();

        return response()->json([
            'period' => $period->value,
            'scores' => $this->leaderboardCacheService->getTopUsers($period),
        ]);
    }

    /**
     * Очки юзера
     *
     * @param  mixed $request
     * @param  mixed $userId
     * @return JsonResponse
     */
    public function show(PeriodRequest $request, string $userId): JsonResponse
    {
        $period = $request->getPeriodType();

        return response()->json([
            'user_id' => (int)$userId,
            'period' => $period->value,
            'total_score' => $this->leaderboardCacheService->getUserScore($userId, $period),
            'rank' => $this->leaderboardCacheService->getUserRank($userId, $period),
        ]);
    }
    /**
     * Добавление поинтов юзера.
     * @param  mixed $request
     * @return JsonResponse
     */
    public function store(AddPointsRequest $request): JsonResponse
    {
        $userId = $request->userId;
        $points = $request->points;

        if (!$this->userRepository->isUserExists($userId)) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        return response()->json([
            'user_id' => $userId,
            'total_score' => $this->leaderboardCacheService->updateUserPoints($userId, $points),
        ]);
    }
}
