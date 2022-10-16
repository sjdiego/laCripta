<?php

declare(strict_types=1);

use App\Domains\Vault\Application\CreateUserUseCase;
use App\Domains\Vault\Application\UpdateUserUseCase;
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Infrastructure\{
    Hashing\PasswordDefaultHashing,
    Repositories\MariaDbUserRepository
};
use PHPUnit\Framework\TestCase;

final class UpdateUserTest extends TestCase
{
    const DEFAULT_UUID = '12345678-1234-1234-1234-123456789012';

    private $userRepository;
    private $passwordHashing;

    protected function setUp(): void
    {
        $this->userRepository = $this->getMockBuilder(MariaDbUserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find', 'create', 'update'])
            ->getMockForAbstractClass();

        $this->passwordHashing = new PasswordDefaultHashing();

        $this->createDefaultUser();
    }

    public function test_user_service_updates_user_in_repository(): void
    {
        $updateUserService = new UpdateUserUseCase($this->userRepository, $this->passwordHashing);

        $this->userRepository
            ->expects($this->any())
            ->method('update')
            ->willReturn(true);

        // Update data of previously created user
        $user = $updateUserService(
            uuid: self::DEFAULT_UUID,
            data: [
                'name' => 'LilJhonny',
                'email' => 'johndoe@other-domain.net',
                'password' => $this->passwordHashing->hash('1337p4$$wOrD')
            ],
        );

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertArrayHasKey(key: 'uuid', array: $user->toArray());
        // $this->assertEquals(expected: self::DEFAULT_UUID, actual: $user->getUUID()->value());
    }

    private function createDefaultUser()
    {
        $this->userRepository
            ->expects($this->any())
            ->method('create')
            ->willReturn(true);

        $userCreateService = new CreateUserUseCase($this->userRepository, $this->passwordHashing);

        $userCreateService(
            uuid: self::DEFAULT_UUID,
            name: 'JohnDoe',
            email: 'johndoe@example.com',
            password: 'S3cr3t%ABC#123'
        );
    }
}
