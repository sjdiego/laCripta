<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\ValueObjects;

final class UserLastUse
{
    public function __construct(private \DateTimeImmutable $lastUse)
    {
    }

    public function value(): \DateTimeImmutable
    {
        return $this->lastUse;
    }
}
