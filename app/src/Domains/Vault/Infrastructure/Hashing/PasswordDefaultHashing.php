<?php

declare(strict_types=1);

namespace App\Domains\Vault\Infrastructure\Hashing;

use App\Domains\Vault\Domain\Contracts\PasswordHashingContract;

class PasswordDefaultHashing implements PasswordHashingContract
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
