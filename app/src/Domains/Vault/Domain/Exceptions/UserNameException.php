<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\Exceptions;

use App\Domains\Vault\Domain\ValueObjects\UserName;

class UserNameException extends \Exception
{
    public $message = 'Name is not valid. It must met the following pattern: ' . UserName::NAME_PATTERN;
}
