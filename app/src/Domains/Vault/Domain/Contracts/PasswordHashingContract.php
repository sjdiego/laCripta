<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\Contracts;

interface PasswordHashingContract
{
    public function hash(string $password): string;

    public function verify(string $password, string $hash): bool;
}
