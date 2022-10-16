<?php

declare(strict_types=1);

namespace App\Domains\Vault\Application;

use App\Domains\Vault\Domain\Contracts\{UserRepositoryContract, PasswordHashingContract};
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Domain\ValueObjects\{UserEmail, UserName, UserPassword};

final class UpdateUserUseCase
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private PasswordHashingContract $passwordHashing,
    ) {
    }

    /**
     * It updates an user.
     *
     * @param string $uuid
     * @param array $data
     * @return User
     */
    public function __invoke(string $uuid, array $data): User
    {
        $user = $this->userRepository->find($uuid);

        $hashedPassword = !empty($data['password']) ? $this->passwordHashing->hash($data['password']) : null;

        $updatedUser = new User(
            uuid: $user->getUUID(),
            name: !empty($data['name']) ? new UserName($data['name']) : $user->getName(),
            email: !empty($data['email']) ? new UserEmail($data['email']) : $user->getEmail(),
            password: (null !== $hashedPassword) ? new UserPassword($hashedPassword) : $user->getPassword(),
            createdAt: $user->getCreatedAt(),
            lastUse: $user->getLastUse()
        );

        $this->userRepository->update($updatedUser);

        return $updatedUser;
    }
}
