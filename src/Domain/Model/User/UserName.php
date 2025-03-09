<?php

declare(strict_types=1);

namespace App\Domain\Model\User;

use App\Domain\Exception\InvalidNameException;

final class UserName
{
    private string $value;
    private const MIN_LENGTH = 2;
    private const MAX_LENGTH = 100;
    private const PATTERN = '/^[a-zA-Z\s]+$/';

    private function __construct(string $name)
    {
        $this->validate($name);
        $this->value = $name;
    }

    public static function fromString(string $name): self
    {
        return new self($name);
    }

    private function validate(string $name): void
    {
        $name = trim($name);
        
        if (strlen($name) < self::MIN_LENGTH) {
            throw new InvalidNameException(
                sprintf('Name must be at least %d characters long', self::MIN_LENGTH)
            );
        }
        
        if (strlen($name) > self::MAX_LENGTH) {
            throw new InvalidNameException(
                sprintf('Name cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
        
        if (!preg_match(self::PATTERN, $name)) {
            throw new InvalidNameException('Name can only contain letters and spaces');
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(UserName $other): bool
    {
        return $this->value === $other->value;
    }

    public function __toString(): string
    {
        return $this->value;
    }
} 