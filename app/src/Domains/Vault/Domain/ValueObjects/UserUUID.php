<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\ValueObjects;

final class UserUUID
{
    public function __construct(private string $userUUID)
    {
    }

    public function value(): string
    {
        return $this->userUUID;
    }
}
