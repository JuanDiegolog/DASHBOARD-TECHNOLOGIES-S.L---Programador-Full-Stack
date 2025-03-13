<?php

declare(strict_types=1);

namespace Tests\Integration\Infrastructure\Persistence;

use App\Domain\Model\User\User;
use App\Domain\Model\User\UserEmail;
use App\Domain\Model\User\UserId;
use App\Domain\Model\User\UserName;
use App\Domain\Model\User\UserPassword;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use App\Infrastructure\Persistence\Doctrine\Type\UserIdType;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\TestCase;

class DoctrineUserRepositoryTest extends TestCase
{
    private EntityManager $entityManager;
    private DoctrineUserRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Registrar el tipo personalizado
        if (!Type::hasType(UserIdType::NAME)) {
            Type::addType(UserIdType::NAME, UserIdType::class);
        }

        // Configuración de Doctrine para tests (usando SQLite en memoria)
        $config = ORMSetup::createXMLMetadataConfiguration(
            [__DIR__ . '/../../../../config/doctrine'],
            true
        );

        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'memory' => true,
        ], $config);

        $this->entityManager = new EntityManager($connection, $config);

        // Registrar el tipo en la plataforma
        $platform = $connection->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('user_id', 'user_id');

        // Crear esquema
        $schemaTool = new SchemaTool($this->entityManager);
        $classes = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($classes);

        $this->repository = new DoctrineUserRepository($this->entityManager);
    }

    public function testSaveAndFindById(): void
    {
        // Crear un usuario de prueba
        $userId = UserId::generate();
        $userName = UserName::fromString('Test User');
        $userEmail = UserEmail::fromString('test@example.com');
        $userPassword = UserPassword::fromPlainPassword('Password123!');

        $user = User::reconstitute(
            $userId,
            $userName,
            $userEmail,
            $userPassword,
            new \DateTimeImmutable()
        );

        // Guardar el usuario
        $this->repository->save($user);

        // Verificar que podemos encontrarlo por ID
        $foundUser = $this->repository->findById($userId);

        $this->assertNotNull($foundUser);
        $this->assertTrue($foundUser->id()->equals($userId));
        $this->assertTrue($foundUser->email()->equals($userEmail));
    }

    public function testFindByEmail(): void
    {
        // Crear un usuario de prueba
        $userId = UserId::generate();
        $userName = UserName::fromString('Email Test User');
        $userEmail = UserEmail::fromString('email_test@example.com');
        $userPassword = UserPassword::fromPlainPassword('Password123!');

        $user = User::reconstitute(
            $userId,
            $userName,
            $userEmail,
            $userPassword,
            new \DateTimeImmutable()
        );

        // Guardar el usuario
        $this->repository->save($user);

        // Verificar que podemos encontrarlo por email
        $foundUser = $this->repository->findByEmail($userEmail);

        $this->assertNotNull($foundUser);
        $this->assertTrue($foundUser->id()->equals($userId));
        $this->assertTrue($foundUser->email()->equals($userEmail));
    }

    public function testDelete(): void
    {
        // Crear un usuario de prueba
        $userId = UserId::generate();
        $userName = UserName::fromString('Delete Test User');
        $userEmail = UserEmail::fromString('delete_test@example.com');
        $userPassword = UserPassword::fromPlainPassword('Password123!');

        $user = User::reconstitute(
            $userId,
            $userName,
            $userEmail,
            $userPassword,
            new \DateTimeImmutable()
        );

        // Guardar el usuario
        $this->repository->save($user);

        // Asegurarse de que la sesión de Doctrine se limpie
        $this->entityManager->clear();

        // Verificar que existe
        $foundUser = $this->repository->findById($userId);
        $this->assertNotNull($foundUser);

        // Eliminarlo
        $this->repository->delete($userId);

        // Verificar que ya no existe
        $foundUserAfterDelete = $this->repository->findById($userId);
        $this->assertNull($foundUserAfterDelete);
    }
}
