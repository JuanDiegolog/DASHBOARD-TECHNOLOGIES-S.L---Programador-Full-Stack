# PowerShell script para reemplazar los comandos Make en Windows

param (
    [Parameter(Position=0)]
    [string]$Command
)

function Show-Help {
    Write-Host "Comandos disponibles:"
    Write-Host "  up         - Inicia los contenedores Docker"
    Write-Host "  down       - Detiene los contenedores Docker"
    Write-Host "  restart    - Reinicia los contenedores Docker"
    Write-Host "  build      - Construye las imágenes Docker"
    Write-Host "  install    - Instala las dependencias PHP"
    Write-Host "  test       - Ejecuta las pruebas PHPUnit"
    Write-Host "  migrate    - Actualiza el esquema de la base de datos"
    Write-Host "  init       - Inicializa el proyecto (up + install + migrate)"
}

switch ($Command) {
    "up" {
        Write-Host "Iniciando contenedores Docker..."
        docker-compose up -d
    }
    "down" {
        Write-Host "Deteniendo contenedores Docker..."
        docker-compose down
    }
    "restart" {
        Write-Host "Reiniciando contenedores Docker..."
        docker-compose down
        docker-compose up -d
    }
    "build" {
        Write-Host "Construyendo imágenes Docker..."
        docker-compose build
    }
    "install" {
        Write-Host "Instalando dependencias PHP..."
        docker-compose exec php composer install
    }
    "test" {
        Write-Host "Ejecutando pruebas PHPUnit..."
        docker-compose exec php vendor/bin/phpunit
    }
    "migrate" {
        Write-Host "Actualizando esquema de base de datos..."
        docker-compose exec php vendor/bin/doctrine orm:schema-tool:update --force
    }
    "init" {
        Write-Host "Inicializando el proyecto..."
        docker-compose up -d
        docker-compose exec php composer install
        docker-compose exec php vendor/bin/doctrine orm:schema-tool:update --force
    }
    default {
        Show-Help
    }
} 