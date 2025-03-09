<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Controller\RegisterUserController;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use App\Application\EventHandler\UserRegisteredEventHandler;
use App\Infrastructure\EventDispatcher\InMemoryEventDispatcher;
use App\Infrastructure\Persistence\Doctrine\DoctrineUserRepository;
use App\Application\UseCase\User\RegisterUserUseCase;

// Configuración Doctrine
$paths = [__DIR__ . '/../config/doctrine'];
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

// Configuración de logger
$logger = new Logger('app');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../var/log/app.log', Logger::INFO));

// Preparar dependencias
$userRepository = new DoctrineUserRepository($entityManager);
$userRegisteredEventHandler = new UserRegisteredEventHandler($logger);
$eventDispatcher = new InMemoryEventDispatcher($userRegisteredEventHandler);
$registerUserUseCase = new RegisterUserUseCase($userRepository, $eventDispatcher);
$registerUserController = new RegisterUserController($registerUserUseCase);

// Enrutador simple
$uri = $_SERVER['REQUEST_URI'];
$method = $_SERVER['REQUEST_METHOD'];

header('Content-Type: application/json');

if ($uri === '/api/register' && $method === 'POST') {
    $requestData = json_decode(file_get_contents('php://input'), true);
    
    $response = $registerUserController($requestData);
    http_response_code($response['code']);
    echo json_encode($response);
    exit;
}

// Ruta no encontrada
http_response_code(404);
echo json_encode([
    'status' => 'error',
    'message' => 'Route not found',
    'code' => 404
]); 