<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\ValueObjects;

final class UserEmail
{
    public function __construct(private string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }
    }

    public function value(): string
    {
        return $this->email;
    }
}
