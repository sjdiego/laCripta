<?php

declare(strict_types=1);

namespace App\Domains\Vault\Application;

use App\Domains\Vault\Domain\Exceptions\{PasswordPatternException, PasswordTooShortException};

/**
 * Class which manages password validation rules.
 */
final class ValidateUserPasswordUseCase
{
    const MIN_LENGTH = 8;
    const PASSWORD_PATTERN = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[A-Za-z\d@$!%*?&]{8,}$/';

    public function __invoke(string $password): bool
    {
        if (strlen($password) < self::MIN_LENGTH) {
            throw new PasswordTooShortException;
        }

        if (preg_match(self::PASSWORD_PATTERN, $password) === 0) {
            throw new PasswordPatternException;
        }

        return true;
    }
}
