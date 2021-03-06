<?php

declare(strict_types=1);

namespace App\Domains\Vault\Domain\Contracts;

use App\Domains\Vault\Domain\User;

interface UserRepositoryContract
{
    public function find(string $uuid): User;

    public function list(): array;

    public function create(User $user): bool;

    public function update(User $user): bool;

    public function delete(User $user): bool;
}
