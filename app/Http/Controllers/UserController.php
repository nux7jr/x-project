<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Requests\CreateUserRequest;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    /**
     * Создание юзера.
     */
    public function create(CreateUserRequest $request): JsonResponse
    {
        $user = $this->userRepository->createUser($request->username);

        return response()->json([
            'id' => $user->id,
            'username' => $user->username
        ]);
    }
}
