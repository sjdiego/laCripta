<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\ValueObjects;

final class UserPassword
{
    public function __construct(private string $password)
    {
    }

    public function value(): string
    {
        return $this->password;
    }
}
