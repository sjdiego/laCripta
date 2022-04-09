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
            'SELECT * FROM users WHERE uuid = :uuid',
            ['uuid' => $uuid]
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

        $data = $this->connection->fetchAllAssociative('SELECT * FROM users');

        foreach ($data as $user) {
            $results[] = [
                'uuid' => $user[UserEnum::UUID->value],
                'name' => $user[UserEnum::NAME->value],
                'email' => $user[UserEnum::EMAIL->value],
                'password' => $user[UserEnum::PASSWORD->value],
                'createdAt' => $user[UserEnum::CREATED_AT->value],
                'lastUse' => $user[UserEnum::LAST_USE->value],
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
                UserEnum::UUID       => $user->getName()->value(),
                UserEnum::NAME       => $user->getEmail()->value(),
                UserEnum::EMAIL      => $user->getPassword()->value(),
                UserEnum::PASSWORD   => $user->getCreatedAt()->value()->format('Y-m-d H:i:s'),
                UserEnum::CREATED_AT => $user->getLastUse()->value()->format('Y-m-d H:i:s'),
                UserEnum::LAST_USE   => $user->getUUID()->value(),
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
