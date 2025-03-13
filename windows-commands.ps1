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
    Write-Host "  fix-eol    - Corrige finales de línea en archivos de script"
}

function Fix-LineEndings {
    Write-Host "Corrigiendo finales de línea en los scripts..."
    
    # Instalar dos2unix si no está disponible
    docker-compose exec php bash -c "if ! command -v dos2unix &> /dev/null; then apt-get update && apt-get install -y dos2unix; fi"
    
    # Corregir finales de línea en bin/doctrine
    docker-compose exec php bash -c "dos2unix bin/doctrine"
    
    # Dar permisos de ejecución
    docker-compose exec php bash -c "chmod +x bin/doctrine"
    
    Write-Host "Finales de línea corregidos exitosamente."
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
        Write-Host "Iniciando contenedores Docker..."
        docker-compose up -d
    }
    "build" {
        Write-Host "Construyendo imágenes Docker..."
        docker-compose build
    }
    "install" {
        Fix-LineEndings
        Write-Host "Instalando dependencias PHP..."
        docker-compose exec php composer install
    }
    "test" {
        Fix-LineEndings
        Write-Host "Ejecutando pruebas PHPUnit..."
        docker-compose exec php vendor/bin/phpunit --testdox
    }
    "fix-eol" {
        Fix-LineEndings
    }
    "migrate" {
        Write-Host "Actualizando esquema de base de datos..."
        # Primero corregimos los finales de línea
        Fix-LineEndings
        # Luego ejecutamos el comando
        docker-compose exec php bin/doctrine orm:schema-tool:update --complete --force
    }
    "init" {
        Write-Host "Inicializando el proyecto..."
        docker-compose up -d
        Write-Host "Instalando dependencias PHP..."
        docker-compose exec php composer install
        Write-Host "Actualizando esquema de base de datos..."
        # Primero corregimos los finales de línea
        Fix-LineEndings
        # Luego ejecutamos el comando
        docker-compose exec php bin/doctrine orm:schema-tool:update --complete --force
    }
    default {
        Show-Help
    }
} 