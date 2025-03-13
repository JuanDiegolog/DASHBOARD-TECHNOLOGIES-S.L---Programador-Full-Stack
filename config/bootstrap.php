<?php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use App\Infrastructure\Persistence\Doctrine\Type\UserIdType;
use Doctrine\DBAL\Types\Type;

require_once __DIR__ . '/../vendor/autoload.php';

// Registrar tipos personalizados de Doctrine
if (!Type::hasType(UserIdType::NAME)) {
    Type::addType(UserIdType::NAME, UserIdType::class);
}

// Configuración de Doctrine
$paths = [__DIR__ . '/doctrine'];
$isDevMode = true;

$config = ORMSetup::createXMLMetadataConfiguration($paths, $isDevMode);
$connection = DriverManager::getConnection([
    'driver' => 'pdo_mysql',
    'host' => $_ENV['DB_HOST'] ?? 'mysql',
    'dbname' => $_ENV['DB_NAME'] ?? 'user_db',
    'user' => $_ENV['DB_USER'] ?? 'user',
    'password' => $_ENV['DB_PASSWORD'] ?? 'password',
    'charset' => 'utf8mb4'
], $config);

$entityManager = new EntityManager($connection, $config);

// Registrar el tipo en la plataforma
$platform = $connection->getDatabasePlatform();
$platform->registerDoctrineTypeMapping('user_id', UserIdType::NAME);

return $entityManager;
