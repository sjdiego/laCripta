<?php

declare(strict_types=1);

namespace App\Domains\Vault\Application;

use App\Domains\Vault\Domain\Contracts\{PasswordHashingContract, UserRepositoryContract};

final class CheckUserPasswordUseCase
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private PasswordHashingContract $passwordHashing,
    ) {
    }

    /**
     * It checks if provided password matches an user.
     *
     * @param string $uuid
     * @param string $password
     * @return bool
     */
    public function __invoke(string $uuid, string $password): bool
    {
        $user = $this->userRepository->find($uuid);

        return $this->passwordHashing->verify($password, $user->getPassword()->value());
    }
}
