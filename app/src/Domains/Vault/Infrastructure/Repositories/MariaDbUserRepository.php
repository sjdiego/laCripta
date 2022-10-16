<?php

declare(strict_types=1);

namespace App\Domains\Vault\Infrastructure\Repositories;

use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Domain\Contracts\UserRepositoryContract;
use App\Domains\Vault\Infrastructure\Repositories\Enums\User as UserEnum;
use App\Domains\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};

/**
 * Class that manages the persistence of the User entity into MariaDB.
 */
class MariaDbUserRepository implements UserRepositoryContract
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
            'SELECT * FROM user WHERE uuid = :uuid',
            [UserEnum::UUID->value => $uuid]
        );

        if (false === $data) {
            throw new \Exception('User not found');
        }

        return new User(
            new UserUUID($data[UserEnum::UUID->value]),
            new UserName($data[UserEnum::NAME->value]),
            new UserEmail($data[UserEnum::EMAIL->value]),
            new UserPassword($data[UserEnum::PASSWORD->value]),
            new UserCreatedAt(new DateTimeImmutable($data[UserEnum::CREATED_AT->value])),
            new UserLastUse(new DateTimeImmutable($data[UserEnum::LAST_USE->value]))
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

        $data = $this->connection->fetchAllAssociative('SELECT * FROM user');

        foreach ($data as $user) {
            $results[] = [
                UserEnum::UUID->value       => $user[UserEnum::UUID->value],
                UserEnum::NAME->value       => $user[UserEnum::NAME->value],
                UserEnum::EMAIL->value      => $user[UserEnum::EMAIL->value],
                UserEnum::PASSWORD->value   => $user[UserEnum::PASSWORD->value],
                UserEnum::CREATED_AT->value => $user[UserEnum::CREATED_AT->value],
                UserEnum::LAST_USE->value   => $user[UserEnum::LAST_USE->value],
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
            return 1 === $this->connection->insert('user', [
                UserEnum::NAME->value => $user->getName()->value(),
                UserEnum::EMAIL->value => $user->getEmail()->value(),
                UserEnum::PASSWORD->value => $user->getPassword()->value(),
                UserEnum::CREATED_AT->value => $user->getCreatedAt()->value()->format('Y-m-d H:i:s'),
                UserEnum::LAST_USE->value => $user->getLastUse()->value()->format('Y-m-d H:i:s'),
                UserEnum::UUID->value => $user->getUUID()->value(),
            ]);
        });
    }

    public function update(User $user): bool
    {
        return $this->connection->transactional(function () use ($user): bool {
            return 1 === $this->connection->update(
                // Table
                'user',
                // Data
                [
                    UserEnum::NAME->value => $user->getName()->value(),
                    UserEnum::EMAIL->value => $user->getEmail()->value(),
                    UserEnum::PASSWORD->value => $user->getPassword()->value(),
                    UserEnum::LAST_USE->value => $user->getLastUse()->value()->format('Y-m-d H:i:s'),
                ],
                // Criteria
                [
                    UserEnum::UUID->value => $user->getUUID()->value(),
                ],
            );
        });
    }

    public function delete(User $user): bool
    {
        return $this->connection->transactional(function () use ($user): bool {
            return 1 === $this->connection->delete('user', [
                UserEnum::UUID->value => $user->getUUID()->value(),
            ]);
        });
    }
}
