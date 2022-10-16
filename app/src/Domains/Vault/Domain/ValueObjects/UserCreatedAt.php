<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\ValueObjects;

class UserCreatedAt
{
    public function __construct(private \DateTimeImmutable $createdAt)
    {
    }

    public function value(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
