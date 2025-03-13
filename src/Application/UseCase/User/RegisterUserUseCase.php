<?php

declare(strict_types=1);

namespace App\Application\UseCase\User;

use App\Domain\Event\EventDispatcherInterface;
use App\Domain\Event\UserRegisteredEvent;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Model\User\User;
use App\Domain\Model\User\UserEmail;
use App\Domain\Model\User\UserName;
use App\Domain\Model\User\UserPassword;
use App\Domain\Repository\UserRepositoryInterface;

final class RegisterUserUseCase
{
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->userRepository = $userRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function execute(RegisterUserRequest $request): UserResponseDTO
    {
        $email = UserEmail::fromString($request->email());
        
        // Verificar si el email ya está en uso
        if ($this->userRepository->findByEmail($email)) {
            throw new UserAlreadyExistsException('El email ya se encuentra registrado');
        }
        
        $user = User::create(
            UserName::fromString($request->name()),
            $email,
            UserPassword::fromPlainPassword($request->password())
        );
        
        $this->userRepository->save($user);
        
        // Disparar evento de registro
        $this->eventDispatcher->dispatch(
            new UserRegisteredEvent($user->id(), $user->email())
        );
        
        return UserResponseDTO::fromUser($user);
    }
} 