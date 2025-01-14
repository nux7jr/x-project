<?php

declare(strict_types=1);

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function createUser(string $userId);
    public function isUserExists(string $userId);
}
