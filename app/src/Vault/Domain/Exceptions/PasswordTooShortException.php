<?php

declare(strict_types=1);

namespace App\Vault\Domain\Exceptions;

class PasswordTooShortException extends \Exception
{
    public $message = 'Password is too short';
}
