<?php

declare(strict_types=1);

namespace App\Domain\Event;

interface EventDispatcherInterface
{
    public function dispatch(DomainEvent $event): void;
} 