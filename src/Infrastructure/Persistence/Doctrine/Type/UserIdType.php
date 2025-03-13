<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Model\User\UserId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

class UserIdType extends Type
{
    public const NAME = 'user_id';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $platform->getVarcharTypeDeclarationSQL([
            'length' => 36,
            'fixed' => true
        ]);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?UserId
    {
        if ($value === null) {
            return null;
        }

        // Asegurar que tenemos un string completo
        $value = (string) $value;

        try {
            return UserId::fromString($value);
        } catch (\InvalidArgumentException $e) {
            // error_log("Error convirtiendo valor '$value' a UserId: " . $e->getMessage());
            throw ConversionException::conversionFailed($value, self::NAME);
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof UserId) {
            return $value->value();
        }

        throw ConversionException::conversionFailed($value, self::NAME);
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
