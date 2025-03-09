<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use App\Domain\Exception\InvalidEmailException;

final class UserEmail
{
    private string $value;

    private function __construct(string $email)
    {
        $this->validate($email);
        $this->value = strtolower($email);
    }

    public static function fromString(string $email): self
    {
        return new self($email);
    }

    private function validate(string $email): void
    {
        $email = trim($email);
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException('Invalid email format');
        }
        
        // Verificar longitud máxima para evitar ataques
        if (strlen($email) > 254) {
            throw new InvalidEmailException('Email is too long');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserEmail $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 