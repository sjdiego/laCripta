<?php

declare(strict_types=1);

namespace App\Vault\Exceptions;

class PasswordPatternException extends \Exception
{
    public $message = 'Password must contain at least one uppercase letter, one lowercase letter and one number.';
}
