<?php

declare(strict_types=1);

namespace App\Domains\Vault\Infrastructure\Repositories;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Domain\Contracts\UserRepositoryContract;
use App\Domains\Vault\Infrastructure\Repositories\Enums\UserDbColumns;
use App\Domains\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};

/**
 * Class that manages the persistence of the User entity into MariaDB.
 */
final class MariaDbUserRepository implements UserRepositoryContract
{
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * It finds an User by its UUID.
     *
     * @param string $uuid
     * @return User
     */
    public function find(string $uuid): User
    {
        $data = $this->connection->fetchAssociative(
            'SELECT * FROM users WHERE uuid = :uuid',
            ['uuid' => $uuid]
        );

        if (false === $data) {
            throw new \Exception('User not found');
        }

        return new User(
            new UserUUID($data[UserDbColumns::UUID]),
            new UserName($data[UserDbColumns::NAME]),
            new UserEmail($data[UserDbColumns::EMAIL]),
            new UserPassword($data[UserDbColumns::PASSWORD]),
            new UserCreatedAt(new DateTimeImmutable($data[UserDbColumns::CREATED_AT])),
            new UserLastUse(new DateTimeImmutable($data[UserDbColumns::LAST_USE]))
        );
    }

    /**
     * It lists all the Users.
     *
     * @return array
     */
    public function list(): array
    {
        $results = [];

        $data = $this->connection->fetchAllAssociative('SELECT * FROM users');

        foreach ($data as $user) {
            $results[] = [
                'uuid' => $user[UserDbColumns::UUID],
                'name' => $user[UserDbColumns::NAME],
                'email' => $user[UserDbColumns::EMAIL],
                'password' => $user[UserDbColumns::PASSWORD],
                'createdAt' => $user[UserDbColumns::CREATED_AT],
                'lastUse' => $user[UserDbColumns::LAST_USE]
            ];
        }

        return $results;
    }

    /**
     * It saves an User.
     *
     * @param User $user
     * @return boolean
     */
    public function create(User $user): bool
    {
        return $this->connection->transactional(function () use ($user): bool {
            return 1 === $this->connection->insert('users', [
                UserDbColumns::UUID       => $user->getName()->value(),
                UserDbColumns::NAME       => $user->getEmail()->value(),
                UserDbColumns::EMAIL      => $user->getPassword()->value(),
                UserDbColumns::PASSWORD   => $user->getCreatedAt()->value()->format('Y-m-d H:i:s'),
                UserDbColumns::CREATED_AT => $user->getLastUse()->value()->format('Y-m-d H:i:s'),
                UserDbColumns::LAST_USE   => $user->getUUID()->value(),
            ]);
        });
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
