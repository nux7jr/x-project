<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Создание юзера
     *
     * @param string     $userId
     * @return User User
     */
    public function createUser(string $username): User
    {
        $user = User::create([
            'username' => $username,
        ]);
        return $user;
    }
    /**
     * Проверка есть ли юзер
     *
     * @param string     $userId
     * @return bool Есть ли такой юзер
     */
    public function isUserExists(string $userId): bool
    {
        return User::where('id', $userId)->exists();
    }

}
