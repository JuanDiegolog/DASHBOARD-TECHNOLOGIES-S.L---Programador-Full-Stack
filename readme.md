# Sistema de Registro de Usuarios (DDD)

gracias por considerarme para el puesto de desarrollador full stack,aunque creo que las pruebas son un poco exageradas fue divertido. espero poder ver que otros problemas interesantes me propongan.

## Características

- Arquitectura Hexagonal / DDD completa
- Entidades y Value Objects inmutables
- Implementación de UUID como identificadores
- Validación de dominio (nombres, emails, contraseñas)
- Persistencia con Doctrine ORM
- API RESTful para registro de usuarios
- Eventos de dominio (UserRegisteredEvent)
- Tests unitarios y de integración
- Contenedores Docker para fácil instalación

## Requisitos previos

- Docker y Docker Compose
- Git
- Make (Linux/Mac) o PowerShell (Windows)
## Comandos disponibles

### Linux/Mac (Makefile)

| Comando | Descripción |
|---------|-------------|
| `make up` | Inicia los contenedores Docker |
| `make down` | Detiene los contenedores Docker |
| `make restart` | Reinicia los contenedores Docker |
| `make build` | Construye las imágenes Docker |
| `make install` | Instala las dependencias PHP |
| `make test` | Ejecuta los tests con PHPUnit |
| `make migrate` | Actualiza el esquema de la base de datos |
| `make init` | Inicializa el proyecto (up + install + migrate) |

### Windows (PowerShell)

| Comando | Descripción |
|---------|-------------|
| `.\windows-commands.ps1 up` | Inicia los contenedores Docker |
| `.\windows-commands.ps1 down` | Detiene los contenedores Docker |
| `.\windows-commands.ps1 restart` | Reinicia los contenedores Docker |
| `.\windows-commands.ps1 build` | Construye las imágenes Docker |
| `.\windows-commands.ps1 install` | Instala las dependencias PHP |
| `.\windows-commands.ps1 test` | Ejecuta los tests con PHPUnit |
| `.\windows-commands.ps1 migrate` | Actualiza el esquema de la base de datos |
| `.\windows-commands.ps1 init` | Inicializa el proyecto (up + install + migrate) |
| `.\windows-commands.ps1 fix-eol` | Corrige los finales de línea en scripts |


## Instalación

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/sistema-registro-usuarios.git
cd sistema-registro-usuarios
```
### 2. Crear archivo .env
crea un archivo .env en la raiz del proyecto y copia el contenido del archivo .env.example, puedes personalizar las variables de entorno segun tus preferencias.

```bash
cp .env.example .env
```


### 3. Iniciar el entorno

```bash
# Iniciar el entorno linux/mac
make init

# Iniciar el entorno windows
.\windows-commands.ps1 init
```
Este comando:
- Inicializara los contenedores Docker
- Instalara las dependencias PHP
- Creara y/o actualizara el esquema de la base de datos


### 4. Realizar pruebas

```bash
# Ejecutar pruebas unitarias linux/mac
make test

# Ejecutar pruebas unitarias windows
.\windows-commands.ps1 test
```
### 5. Registrar un usuario
**POST** /api/register
**Cuerpo de la petición**:

```json
{
"name": "Juan Perez",
"email": "juan.perez@example.com",
"password": "password123"
}
```




