<?php

declare(strict_types=1);

namespace App\Vault\Application;

use App\Vault\Domain\Contracts\UserRepositoryContract;
use App\Vault\Domain\User;

final class FindUserUseCase
{
    public function __construct(private UserRepositoryContract $userRepository)
    {
    }

    public function __invoke(string $uuid): User
    {
        return $this->userRepository->find($uuid);
    }
}
