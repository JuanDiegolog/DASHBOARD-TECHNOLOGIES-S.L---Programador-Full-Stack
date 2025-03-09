<?php

declare(strict_types=1);

namespace App\Domain\Event;

use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserEmail;

final class UserRegisteredEvent implements DomainEvent
{
    private UserId $userId;
    private UserEmail $email;
    private \DateTimeImmutable $occurredOn;

    public function __construct(UserId $userId, UserEmail $email)
    {
        $this->userId = $userId;
        $this->email = $email;
        $this->occurredOn = new \DateTimeImmutable();
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function occurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
} 