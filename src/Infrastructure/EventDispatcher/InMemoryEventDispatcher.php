<?php

declare(strict_types=1);

namespace App\Infrastructure\EventDispatcher;

use App\Domain\Event\DomainEvent;
use App\Domain\Event\EventDispatcherInterface;
use App\Domain\Event\UserRegisteredEvent;
use App\Application\EventHandler\UserRegisteredEventHandler;

final class InMemoryEventDispatcher implements EventDispatcherInterface
{
    private UserRegisteredEventHandler $userRegisteredEventHandler;

    public function __construct(UserRegisteredEventHandler $userRegisteredEventHandler)
    {
        $this->userRegisteredEventHandler = $userRegisteredEventHandler;
    }

    public function dispatch(DomainEvent $event): void
    {
        if ($event instanceof UserRegisteredEvent) {
            $this->userRegisteredEventHandler->handle($event);
        }
    }
} 