<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\ValueObjects;

use App\Domains\Vault\Domain\Exceptions\UUIDPatternException;

final class UserUUID
{
    const UUID_PATTERN = '/^[0-9a-fA-F]{8}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{4}\b-[0-9a-fA-F]{12}$/';

    public function __construct(private string $uuid)
    {
        if (!$this->validate($uuid)) {
            throw new UUIDPatternException;
        }
    }

    public function value(): string
    {
        return $this->uuid;
    }

    /**
     * Rules to validate an UUID
     */
    private function validate(string $uuid): bool
    {
        return preg_match(self::UUID_PATTERN, $uuid) === 1;
    }
}
