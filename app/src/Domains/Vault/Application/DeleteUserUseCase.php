<?php

declare(strict_types=1);

namespace App\Domains\Vault\Application;

use App\Domains\Vault\Domain\Contracts\UserRepositoryContract;
use App\Domains\Vault\Domain\User;

final class DeleteUserUseCase
{
    public function __construct(private UserRepositoryContract $userRepository)
    {
    }

    /**
     * It deletes an user.
     *
     * @param string $uuid
     * @return User
     */
    public function __invoke(string $uuid): bool
    {
        $user = $this->userRepository->find($uuid);

        return $this->userRepository->delete($user);
    }
}
