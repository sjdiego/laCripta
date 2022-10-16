<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\Contracts;

interface ValueObjectContract
{
    public function value(): string;
}
