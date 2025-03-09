<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Model\User;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserEmail;
use App\Domain\Model\User\UserName;
use App\Domain\Model\User\UserPassword;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserCreation(): void
    {
        $name = UserName::fromString('John Doe');
        $email = UserEmail::fromString('john@example.com');
        $password = UserPassword::fromPlainPassword('Password123!');
        
        $user = User::create($name, $email, $password);
        
        $this->assertSame($name, $user->name());
        $this->assertSame($email, $user->email());
        $this->assertSame($password, $user->password());
        $this->assertInstanceOf(\DateTimeImmutable::class, $user->createdAt());
    }
    
    public function testUserCanChangeName(): void
    {
        $name = UserName::fromString('John Doe');
        $email = UserEmail::fromString('john@example.com');
        $password = UserPassword::fromPlainPassword('Password123!');
        
        $user = User::create($name, $email, $password);
        
        $newName = UserName::fromString('Jane Doe');
        $user->changeName($newName);
        
        $this->assertSame($newName, $user->name());
    }
    
    public function testUserCanChangePassword(): void
    {
        $name = UserName::fromString('John Doe');
        $email = UserEmail::fromString('john@example.com');
        $password = UserPassword::fromPlainPassword('Password123!');
        
        $user = User::create($name, $email, $password);
        
        $newPassword = UserPassword::fromPlainPassword('NewPassword456!');
        $user->changePassword($newPassword);
        
        $this->assertSame($newPassword, $user->password());
    }
} 