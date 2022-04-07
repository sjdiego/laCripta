<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\ValueObjects;

use App\Domains\Vault\Application\ValidateUserPasswordUseCase;

final class UserPassword
{
    public function __construct(private string $password)
    {
        $passwordValidator = new ValidateUserPasswordUseCase();
        $passwordValidator($password);
    }

    public function value(): string
    {
        return $this->password;
    }
}
