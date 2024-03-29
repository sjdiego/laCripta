<?php

declare(strict_types=1);

namespace App\Domains\Vault\Application;

use App\Domains\Vault\Domain\Contracts\UserRepositoryContract;
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};
use DateTimeImmutable;

final class ListUserUseCase
{
    public function __construct(private UserRepositoryContract $userRepository)
    {
    }

    /**
     * It returns a list of users.
     *
     * @return array<User>
     */
    public function __invoke(): array
    {
        $users = [];
        $records = $this->userRepository->list();

        foreach ($records as $record) {
            $users[] = new User(
                uuid: new UserUUID($record['uuid']),
                name: new UserName($record['name']),
                email: new UserEmail($record['email']),
                password: new UserPassword($record['password']),
                createdAt: new UserCreatedAt(new DateTimeImmutable($record['createdAt'])),
                lastUse: new UserLastUse(new DateTimeImmutable($record['lastUse']))
            );
        }

        return $users;
    }
}
