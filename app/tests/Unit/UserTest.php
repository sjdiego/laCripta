<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use App\Domains\Vault\Domain\User;
use App\Domains\Vault\Domain\Exceptions\{
    UUIDPatternException,
    UserNameException,
    EmailInvalidException,
    PasswordPatternException,
    PasswordTooShortException
};
use App\Domains\Vault\Domain\ValueObjects\{
    UserName,
    UserUUID,
    UserEmail,
    UserPassword,
    UserCreatedAt,
    UserLastUse
};

final class UserTest extends TestCase
{
    public function test_valid_user_can_be_created(): void
    {
        $user = new User(
            new UserUUID('00000000-0000-0000-0000-000000000000'),
            new UserName('JohnDoe'),
            new UserEmail('john@doe.ru'),
            new UserPassword('h%$p7P@BKAyyB&|'),
            new UserCreatedAt(new DateTimeImmutable()),
            new UserLastUse(new DateTimeImmutable()),
        );

        $this->assertInstanceOf(User::class, $user);
    }

    public function test_user_cant_be_created_with_invalid_uuid(): void
    {
        $this->expectException(UUIDPatternException::class);

        new User(
            new UserUUID('definitely-not-a-valid-uuid'),
            new UserName('John Doe'),
            new UserEmail('john@doe.ru'),
            new UserPassword('h%$p7P@BKAyyB&|'),
            new UserCreatedAt(new DateTimeImmutable()),
            new UserLastUse(new DateTimeImmutable()),
        );
    }

    public function test_user_cant_be_created_with_invalid_name(): void
    {
        $this->expectException(UserNameException::class);

        new User(
            new UserUUID('00000000-0000-0000-0000-000000000000'),
            new UserName('John Doe'),
            new UserEmail('john@doe.ru'),
            new UserPassword('h%$p7P@BKAyyB&|'),
            new UserCreatedAt(new DateTimeImmutable()),
            new UserLastUse(new DateTimeImmutable()),
        );
    }

    public function test_user_cant_be_created_with_invalid_email(): void
    {
        $this->expectException(EmailInvalidException::class);

        new User(
            new UserUUID('00000000-0000-0000-0000-000000000000'),
            new UserName('JohnDoe'),
            new UserEmail('john@doe'),
            new UserPassword('h%$p7P@BKAyyB&|'),
            new UserCreatedAt(new DateTimeImmutable()),
            new UserLastUse(new DateTimeImmutable()),
        );
    }

    public function test_user_cant_be_created_with_short_password(): void
    {
        $this->expectException(PasswordTooShortException::class);

        new User(
            new UserUUID('00000000-0000-0000-0000-000000000000'),
            new UserName('JohnDoe'),
            new UserEmail('john@doe.ru'),
            new UserPassword('1234'),
            new UserCreatedAt(new DateTimeImmutable()),
            new UserLastUse(new DateTimeImmutable()),
        );
    }

    public function test_user_cant_be_created_with_unsecure_password(): void
    {
        $this->expectException(PasswordPatternException::class);

        new User(
            new UserUUID('00000000-0000-0000-0000-000000000000'),
            new UserName('JohnDoe'),
            new UserEmail('john@doe.ru'),
            new UserPassword('123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'),
            new UserCreatedAt(new DateTimeImmutable()),
            new UserLastUse(new DateTimeImmutable()),
        );
    }

    public function test_user_cant_be_created_with_invalid_dates(): void
    {
        $this->expectErrorMessage('must be of type DateTimeImmutable');

        new User(
            new UserUUID('00000000-0000-0000-0000-000000000000'),
            new UserName('JohnDoe'),
            new UserEmail('john@doe.ru'),
            new UserPassword('h%$p7P@BKAyyB&|'),
            new UserCreatedAt('2020-10-01'),
            new UserLastUse(new DateTimeImmutable()),
        );
    }
}
