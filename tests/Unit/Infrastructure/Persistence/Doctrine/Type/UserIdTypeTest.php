<?php

declare(strict_types=1);

namespace Tests\Unit\Infrastructure\Persistence\Doctrine\Type;

use App\Domain\Model\User\UserId;
use App\Infrastructure\Persistence\Doctrine\Type\UserIdType;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use PHPUnit\Framework\TestCase;

class UserIdTypeTest extends TestCase
{
    private UserIdType $type;
    private MySQLPlatform $platform;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Type::hasType(UserIdType::NAME)) {
            Type::addType(UserIdType::NAME, UserIdType::class);
        }

        $this->type = Type::getType(UserIdType::NAME);
        $this->platform = new MySQLPlatform();
    }

    public function testGetSQLDeclaration(): void
    {
        $fieldDeclaration = [
            'length' => 36,
            'fixed' => true,
        ];

        $sql = $this->type->getSQLDeclaration($fieldDeclaration, $this->platform);

        // Verifica que genera la declaración SQL correcta (CHAR)
        $this->assertStringContainsString('CHAR', $sql);
        $this->assertStringContainsString('36', $sql);
    }

    public function testConvertToDatabaseValue(): void
    {
        // Caso 1: Valor nulo
        $this->assertNull($this->type->convertToDatabaseValue(null, $this->platform));

        // Caso 2: Objeto UserId válido
        $uuid = 'e2fdb31c-f0f1-4c78-9b8e-d2578125bc9f';
        $userId = UserId::fromString($uuid);
        $this->assertEquals($uuid, $this->type->convertToDatabaseValue($userId, $this->platform));

        // Caso 3: Valor inválido
        $this->expectException(ConversionException::class);
        $this->type->convertToDatabaseValue('invalid-value', $this->platform);
    }

    public function testConvertToPHPValue(): void
    {
        // Caso 1: Valor nulo
        $this->assertNull($this->type->convertToPHPValue(null, $this->platform));

        // Caso 2: String UUID válido
        $uuid = 'e2fdb31c-f0f1-4c78-9b8e-d2578125bc9f';
        $userId = $this->type->convertToPHPValue($uuid, $this->platform);
        $this->assertInstanceOf(UserId::class, $userId);
        $this->assertEquals($uuid, $userId->value());

        // Caso 3: String UUID inválido
        $this->expectException(ConversionException::class);
        $this->type->convertToPHPValue('not-a-valid-uuid', $this->platform);
    }

    public function testRequiresSQLCommentHint(): void
    {
        $this->assertTrue($this->type->requiresSQLCommentHint($this->platform));
    }
}
