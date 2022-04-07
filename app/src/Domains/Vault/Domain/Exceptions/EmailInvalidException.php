<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\Exceptions;

class EmailInvalidException extends \Exception
{
    public $message = 'Email is not valid';
}
