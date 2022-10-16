<?php

declare(strict_types=1);

use App\Domains\Vault\Application\UpdateUserUseCase;
use App\Domains\Vault\Domain\Contracts\{PasswordHashingContract, UserRepositoryContract};
use App\Domains\Vault\Domain\User;
use PHPUnit\Framework\TestCase;

final class UpdateUserTest extends TestCase
{
    const DEFAULT_UUID = '87654321-1234-1234-1234-123456789012';
    const DEFAULT_PASS = 'S3cr3t%ABC#543';

    private $userRepository;
    private $passwordHashing;

    protected function setUp(): void
    {
        $this->userRepository = $this->getMockBuilder(UserRepositoryContract::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find', 'update'])
            ->getMockForAbstractClass();

        $this->passwordHashing = $this->getMockBuilder(PasswordHashingContract::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['hash'])
            ->getMockForAbstractClass();
    }

    public function test_user_service_updates_user_in_repository(): void
    {
        $this->passwordHashing
            ->expects($this->any())
            ->method('hash')
            ->willReturn('$2y$10$BBH95N4G0ZiKKjtyANd2hetwfKWURZa0u5qqDWNCONnfXcgU54Ik6');

        $hashedPassword = $this->passwordHashing->hash(self::DEFAULT_PASS);

        $this->userRepository
            ->expects($this->any())
            ->method('update')
            ->willReturn(true);

        $updateUserService = new UpdateUserUseCase($this->userRepository, $this->passwordHashing);

        // Update data of previously created user
        $user = $updateUserService(
            uuid: self::DEFAULT_UUID,
            data: [
                'name' => 'LilJhonny',
                'email' => 'johndoe@other-domain.net',
                'password' => $hashedPassword,
            ],
        );

        $this->assertInstanceOf(expected: User::class, actual: $user);
        $this->assertArrayHasKey(key: 'uuid', array: $user->toArray());
        $this->assertEquals(expected: 'LilJhonny', actual: $user->getName()->value());
        $this->assertEquals(expected: 'johndoe@other-domain.net', actual: $user->getEmail()->value());
        $this->assertEquals(expected: $hashedPassword, actual: $user->getPassword()->value());
    }
}
