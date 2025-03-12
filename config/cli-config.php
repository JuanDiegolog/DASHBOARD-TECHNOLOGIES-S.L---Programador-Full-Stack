<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use App\Infrastructure\Persistence\Doctrine\Type\UserIdType;
use Doctrine\DBAL\Types\Type;

// Obtener el EntityManager
$entityManager = require_once __DIR__ . '/bootstrap.php';

// Registrar el tipo personalizado (importante para que funcione en CLI)
if (!Type::hasType(UserIdType::NAME)) {
    Type::addType(UserIdType::NAME, UserIdType::class);
}

// Devolver el ConsoleRunner configurado con nuestro EntityManager
return ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);
