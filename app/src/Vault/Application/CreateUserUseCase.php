<?php

declare(strict_types=1);

namespace App\Vault\Application;

use App\Vault\Domain\Contracts\{UserRepositoryContract, PasswordHashingContract};
use App\Vault\Domain\User;
use App\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};

final class CreateUserUseCase
{
    public function __construct(
        private UserRepositoryContract $userRepository,
        private PasswordHashingContract $passwordHashing,
    ) {
    }

    public function __invoke(string $uuid, string $name, string $email, string $password): User
    {
        $passwordValidator = new ValidateUserPasswordUseCase();
        $passwordValidator($password);

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
