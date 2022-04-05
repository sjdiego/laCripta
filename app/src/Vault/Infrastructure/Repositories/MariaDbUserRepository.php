<?php

declare(strict_types=1);

namespace App\Vault\Infrastructure\Repositories;

use Doctrine\DBAL\Connection;
use App\Vault\Domain\Contracts\UserRepositoryContract;
use App\Vault\Domain\User;
use App\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};
use DateTimeImmutable;

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
            new UserUUID($data['uuid']),
            new UserName($data['name']),
            new UserEmail($data['email']),
            new UserPassword($data['password']),
            new UserCreatedAt(new DateTimeImmutable($data['created_at'])),
            new UserLastUse(new DateTimeImmutable($data['last_use']))
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
                'uuid' => $user['uuid'],
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => $user['password'],
                'createdAt' => $user['created_at'],
                'lastUse' => $user['last_use']
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
                'name'       => $user->getName()->value(),
                'email'      => $user->getEmail()->value(),
                'password'   => $user->getPassword()->value(),
                'created_at' => $user->getCreatedAt()->value()->format('Y-m-d H:i:s'),
                'last_use'   => $user->getLastUse()->value()->format('Y-m-d H:i:s'),
                'uuid'       => $user->getUUID()->value(),
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
