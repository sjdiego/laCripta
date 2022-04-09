<?php

declare(strict_types=1);

use App\Domains\Vault\Application\CreateUserUseCase;
use PHPUnit\Framework\TestCase;
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Infrastructure\Hashing\PasswordDefaultHashing;
use App\Domains\Vault\Infrastructure\Repositories\MariaDbUserRepository;

final class CreateUserTest extends TestCase
{
    const DEFAULT_UUID = '12345678-1234-1234-1234-123456789012';

    protected $userRepository;
    protected $passwordHashing;

    protected function setUp(): void
    {
        $this->userRepository = $this->getMockBuilder(MariaDbUserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['create'])
            ->getMockForAbstractClass();

        $this->passwordHashing = new PasswordDefaultHashing();
    }

    public function test_user_service_registers_a_new_user(): void
    {
        $userCreateService = new CreateUserUseCase(
            $this->userRepository,
            $this->passwordHashing
        );

        $user = $userCreateService(
            uuid: self::DEFAULT_UUID,
            name: 'JohnDoe',
            email: 'johndoe@example.com',
            password: 'S3cr3t%ABC#123'
        );

        $this->assertInstanceOf(User::class, $user);
        $this->assertArrayHasKey(key: 'uuid', array: $user->toArray());
        $this->assertEquals($user->getUUID()->value(), self::DEFAULT_UUID);
    }
}
