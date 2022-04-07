<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\Exceptions;

class UUIDPatternException extends \Exception
{
    public $message = 'UUID format is not valid';
}
