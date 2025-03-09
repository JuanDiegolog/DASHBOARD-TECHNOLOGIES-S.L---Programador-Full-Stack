<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use DateTimeImmutable;

class User
{
    private UserId $id;
    private UserName $name;
    private UserEmail $email;
    private UserPassword $password;
    private DateTimeImmutable $createdAt;

    private function __construct(
        UserId $id,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    public static function create(
        UserName $name,
        UserEmail $email,
        UserPassword $password
    ): self {
        return new self(
            UserId::generate(),
            $name,
            $email,
            $password,
            new DateTimeImmutable()
        );
    }

    public static function reconstitute(
        UserId $id,
        UserName $name,
        UserEmail $email,
        UserPassword $password,
        DateTimeImmutable $createdAt
    ): self {
        return new self($id, $name, $email, $password, $createdAt);
    }

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function password(): UserPassword
    {
        return $this->password;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function changeName(UserName $name): void
    {
        $this->name = $name;
    }

    public function changePassword(UserPassword $password): void
    {
        $this->password = $password;
    }
} 