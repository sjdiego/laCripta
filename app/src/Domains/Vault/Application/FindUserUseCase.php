<?php

declare(strict_types=1);

namespace App\Domains\Vault\Application;

use App\Domains\Vault\Domain\Contracts\UserRepositoryContract;
use App\Domains\Vault\Domain\User;

final class FindUserUseCase
{
    public function __construct(private UserRepositoryContract $userRepository)
    {
    }

    /**
     * It returns an user.
     *
     * @param string $uuid
     * @return User
     */
    public function __invoke(string $uuid): User
    {
        return $this->userRepository->find($uuid);
    }
}
