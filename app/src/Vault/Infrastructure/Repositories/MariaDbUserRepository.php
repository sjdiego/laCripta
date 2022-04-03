<?php

declare(strict_types=1);

namespace App\Vault\Infrastructure\Repositories;

use App\Vault\Domain\Contracts\UserRepositoryContract;
use App\Vault\Domain\User;
use App\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};
use DateTimeImmutable;

/**
 * Class that manages the persistence of the User entity into MariaDB.
 */
final class MariaDbUserRepository implements UserRepositoryContract
{
    public function find(string $uuid): User
    {
        return new User(
            new UserUUID($uuid),
            new UserName('JohnDoe'),
            new UserEmail('john@doe.com'),
            new UserPassword('$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm'),
            new UserCreatedAt(new DateTimeImmutable()),
            new UserLastUse(new DateTimeImmutable())
        );
    }

    public function list(): array
    {
        return [];
    }

    public function create(User $user): User
    {
        return $user;
    }

    public function update(User $user): bool
    {
        return true;
    }

    public function delete(User $user): bool
    {
        return true;
    }
}
