<?php

declare(strict_types=1);

namespace App\Application\EventHandler;

use App\Domain\Event\UserRegisteredEvent;
use Psr\Log\LoggerInterface;

final class UserRegisteredEventHandler
{
    private LoggerInterface $logger;
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(UserRegisteredEvent $event): void
    {
        // Simular envío de email de bienvenida
        $this->logger->info(
            'Email de bienvenida enviado al usuario',
            [
                'user_id' => $event->userId()->value(),
                'email' => $event->email()->value(),
                'registered_at' => $event->occurredOn()->format('Y-m-d H:i:s')
            ]
        );
    }
} 