<?php

declare(strict_types=1);

namespace App\Vault\Application;

use App\Vault\Domain\Contracts\UserRepositoryContract;
use App\Vault\Domain\User;
use App\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};
use DateTimeImmutable;

final class ListUserUseCase
{
    public function __construct(private UserRepositoryContract $userRepository)
    {
    }

    public function __invoke(): array
    {
        $users = [];
        $records = $this->userRepository->list();

        foreach ($records as $record) {
            $users[] = new User(
                new UserUUID($record['uuid']),
                new UserName($record['name']),
                new UserEmail($record['email']),
                new UserPassword($record['password']),
                new UserCreatedAt(new DateTimeImmutable($record['createdAt'])),
                new UserLastUse(new DateTimeImmutable($record['lastUse']))
            );
        }

        return $users;
    }
}
