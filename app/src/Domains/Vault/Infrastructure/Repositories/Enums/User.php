<?php

declare(strict_types=1);

namespace App\Domains\Vault\Infrastructure\Repositories\Enums;

enum User: string
{
    case UUID = 'uuid';
    case NAME = 'name';
    case EMAIL = 'email';
    case PASSWORD = 'password';
    case CREATED_AT = 'created_at';
    case LAST_USE = 'last_use';
}
