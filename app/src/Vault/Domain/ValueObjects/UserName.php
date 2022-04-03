<?php

declare(strict_types=1);

namespace App\Vault\Domain\ValueObjects;

final class UserName
{
    const NAME_PATTERN = '/^[a-zA-Z][a-zA-Z0-9]{2,19}$/';

    public function __construct(private string $name)
    {
        if (!$this->validate($name)) {
            throw new \InvalidArgumentException('Invalid name');
        }
    }

    public function value(): string
    {
        return $this->name;
    }

    /**
     * Rules to validate an user name
     */
    private function validate(string $name): bool
    {
        return preg_match(self::NAME_PATTERN, $name) === 1;
    }
}
