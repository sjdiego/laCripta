<?php

declare(strict_types=1);

use App\Domains\Vault\Application\CheckUserPasswordUseCase;
use App\Domains\Vault\Domain\Contracts\{PasswordHashingContract, UserRepositoryContract};
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Domain\ValueObjects\{
    UserUUID,
    UserName,
    UserEmail,
    UserPassword,
    UserCreatedAt,
    UserLastUse
};
use PHPUnit\Framework\TestCase;

final class CheckUserPasswordTest extends TestCase
{
    const DEFAULT_UUID = '12345678-1234-1234-1234-123456789012';
    const DEFAULT_PASS = 'S3cr3t%ABC#123';

    private $userRepository;
    private $passwordHashing;

    protected function setUp(): void
    {
        $this->userRepository = $this->getMockBuilder(UserRepositoryContract::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find', 'create'])
            ->getMockForAbstractClass();

        $this->passwordHashing = $this->getMockBuilder(PasswordHashingContract::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['hash', 'verify'])
            ->getMockForAbstractClass();
    }

    public function test_password_matches_stored_user_password(): void
    {
        $this->passwordHashing
            ->expects($this->any())
            ->method('hash')
            ->willReturn(password_hash(self::DEFAULT_PASS, PASSWORD_DEFAULT));

        $hashedPassword = $this->passwordHashing->hash(self::DEFAULT_PASS);

        $this->userRepository
            ->expects($this->any())
            ->method('find')
            ->willReturn(
                new User(
                    new UserUUID(self::DEFAULT_UUID),
                    new UserName('JohnDoe'),
                    new UserEmail('johndoe@example.com'),
                    new UserPassword($hashedPassword),
                    new UserCreatedAt(new DateTimeImmutable),
                    new UserLastUse(new DateTimeImmutable),
                )
            );

        $checkUserPasswordService = new CheckUserPasswordUseCase(
            $this->userRepository,
            $this->passwordHashing
        );

        $this->passwordHashing
            ->expects($this->any())
            ->method('verify')
            ->willReturn(password_verify(self::DEFAULT_PASS, $hashedPassword));

        $isValid = $checkUserPasswordService(uuid: self::DEFAULT_UUID, password: self::DEFAULT_PASS);

        $this->assertTrue($isValid);
    }

    public function test_password_does_not_match_stored_user_password(): void
    {
        $this->passwordHashing
            ->expects($this->any())
            ->method('hash')
            ->willReturn(password_hash(self::DEFAULT_PASS, PASSWORD_DEFAULT));

        $hashedPassword = $this->passwordHashing->hash(self::DEFAULT_PASS);

        $this->userRepository
            ->expects($this->any())
            ->method('find')
            ->willReturn(
                new User(
                    new UserUUID(self::DEFAULT_UUID),
                    new UserName('JohnDoe'),
                    new UserEmail('johndoe@example.com'),
                    new UserPassword($hashedPassword),
                    new UserCreatedAt(new DateTimeImmutable),
                    new UserLastUse(new DateTimeImmutable),
                )
            );

        $checkUserPasswordService = new CheckUserPasswordUseCase(
            $this->userRepository,
            $this->passwordHashing
        );

        $this->passwordHashing
            ->expects($this->any())
            ->method('verify')
            ->willReturn(password_verify('wRoNgP4Ss!WoRd_', $hashedPassword));

        $isValid = $checkUserPasswordService(uuid: self::DEFAULT_UUID, password: 'wRoNgP4Ss!WoRd_');

        $this->assertFalse($isValid);
    }
}
