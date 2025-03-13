<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use App\Domain\Exception\WeakPasswordException;

final class UserPassword
{
    private string $hashedValue;
    private const MIN_LENGTH = 8;

    private function __construct(string $hashedPassword)
    {
        $this->hashedValue = $hashedPassword;
    }

    public static function fromPlainPassword(string $plainPassword): self
    {
        self::validatePassword($plainPassword);
        
        // Usar algoritmo de hashing seguro
        $hashedPassword = password_hash($plainPassword, PASSWORD_ARGON2ID);
        
        if (!$hashedPassword) {
            throw new \RuntimeException('Password hashing failed');
        }
        
        return new self($hashedPassword);
    }

    public static function fromHash(string $hashedPassword): self
    {
        return new self($hashedPassword);
    }

    private static function validatePassword(string $password): void
    {
        if (strlen($password) < self::MIN_LENGTH) {
            throw new WeakPasswordException(
                sprintf('La contraseña debe tener al menos %d caracteres', self::MIN_LENGTH)
            );
        }
        
        // Verificar al menos una letra mayúscula
        if (!preg_match('/[A-Z]/', $password)) {
            throw new WeakPasswordException('La contraseña debe contener al menos una letra mayúscula');
        }
        
        // Verificar al menos un número
        if (!preg_match('/[0-9]/', $password)) {
            throw new WeakPasswordException('La contraseña debe contener al menos un número');
        }
        
        // Verificar al menos un carácter especial
        if (!preg_match('/[^a-zA-Z0-9]/', $password)) {
            throw new WeakPasswordException('La contraseña debe contener al menos un caracter especial');
        }
    }

    public function verify(string $plainPassword): bool
    {
        return password_verify($plainPassword, $this->hashedValue);
    }

    public function value(): string
    {
        return $this->hashedValue;
    }

    public function __toString(): string
    {
        return $this->hashedValue;
    }
} 