<?php

declare(strict_types=1);

namespace App\Domains\Vault\Application;

use App\Domains\Vault\Domain\Contracts\{UserRepositoryContract, PasswordHashingContract};
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};

final class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private PasswordHashingContract $passwordHashing,
    ) {
    }

    /**
     * It creates a new user.
     *
     * @param string $uuid
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function __invoke(string $uuid, string $name, string $email, string $password): User
    {
        $hashedPassword = $this->passwordHashing->hash($password);

        $user = new User(
            new UserUUID($uuid),
            new UserName($name),
            new UserEmail($email),
            new UserPassword($hashedPassword),
            new UserCreatedAt(new \DateTimeImmutable()),
            new UserLastUse(new \DateTimeImmutable())
        );

        $this->userRepository->create($user);

        return $user;
    }
}
