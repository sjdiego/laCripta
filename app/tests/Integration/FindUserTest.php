<?php

declare(strict_types=1);

use App\Domains\Vault\Application\FindUserUseCase;
use App\Domains\Vault\Domain\Exceptions\UUIDPatternException;
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Domain\ValueObjects\{
    UserUUID,
    UserName,
    UserEmail,
    UserPassword,
    UserCreatedAt,
    UserLastUse
};
use App\Domains\Vault\Infrastructure\{
    Hashing\PasswordDefaultHashing,
    Repositories\MariaDbUserRepository
};
use PHPUnit\Framework\TestCase;

final class FindUserTest extends TestCase
{
    const DEFAULT_UUID = '12345678-1234-1234-1234-123456789012';

    private $userRepository;
    private $passwordHashing;

    protected function setUp(): void
    {
        $this->userRepository = $this->getMockBuilder(MariaDbUserRepository::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMockForAbstractClass();

        $this->userRepository;

        $this->passwordHashing = new PasswordDefaultHashing();
    }

    public function test_user_service_finds_user_in_repository(): void
    {
        $this->userRepository
            ->expects($this->any())
            ->method('find')
            ->willReturn(
                new User(
                    new UserUUID(self::DEFAULT_UUID),
                    new UserName('JohnDoe'),
                    new UserEmail('johndoe@example.com'),
                    new UserPassword('s3cr3t%PaSsW0rD'),
                    new UserCreatedAt(new DateTimeImmutable),
                    new UserLastUse(new DateTimeImmutable),
                )
            );

        $findUserService = new FindUserUseCase(
            $this->userRepository,
            $this->passwordHashing
        );

        $user = $findUserService(uuid: self::DEFAULT_UUID);

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertArrayHasKey(key: 'uuid', array: $user->toArray());
        $this->assertEquals(expected: self::DEFAULT_UUID, actual: $user->getUUID()->value());
    }

    public function test_user_service_does_not_find_invalid_uuid_in_repository(): void
    {
        $this->expectException(UUIDPatternException::class);

        $this->userRepository
            ->expects($this->any())
            ->method('find')
            ->will($this->throwException(new UUIDPatternException));

        $findUserService = new FindUserUseCase(
            $this->userRepository,
            $this->passwordHashing
        );

        $findUserService(uuid: 'definitely-not-a-valid-uuid');
    }


    public function test_user_service_does_not_find_missing_uuid_in_repository(): void
    {
        $this->expectException(UUIDPatternException::class);

        $this->userRepository
            ->expects($this->any())
            ->method('find')
            ->will($this->throwException(new UUIDPatternException));

        $findUserService = new FindUserUseCase(
            $this->userRepository,
            $this->passwordHashing
        );

        $findUserService(uuid: '00000000-0000-0000-0000-000000000000');
    }
}
