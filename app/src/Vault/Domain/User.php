<?php

declare(strict_types=1);

namespace App\Vault\Domain;

use App\Vault\Domain\ValueObjects\{UserUUID, UserName, UserEmail, UserPassword, UserCreatedAt, UserLastUse};

final class User
{
    public function __construct(
        private UserUUID $uuid,
        private UserName $name,
        private UserEmail $email,
        private UserPassword $password,
        private UserCreatedAt $createdAt,
        private UserLastUse $lastUse
    ) {
    }

    public function getUUID(): UserUUID
    {
        return $this->uuid;
    }

    public function getName(): UserName
    {
        return $this->name;
    }

    public function getEmail(): UserEmail
    {
        return $this->email;
    }

    public function getPassword(): UserPassword
    {
        return $this->password;
    }

    public function getCreatedAt(): UserCreatedAt
    {
        return $this->createdAt;
    }

    public function getLastUse(): UserLastUse
    {
        return $this->lastUse;
    }

    public function toArray(): array
    {
        return [
            'uuid' => $this->getUUID()->value(),
            'name' => $this->getName()->value(),
            'email' => $this->getEmail()->value(),
            'password' => $this->getPassword()->value(),
            'createdAt' => $this->getCreatedAt()->value(),
            'lastUse' => $this->getLastUse()->value(),
        ];
    }
}
