<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# API Timesheet

API para gestión y registro de horas trabajadas desarrollada con Laravel.

## Descripción

Este proyecto implementa una API RESTful para el manejo de registros de tiempo y control de horas. La API permite a los usuarios registrar sus horas de trabajo y otros tipos de actividades, consultar resúmenes de tiempo, y administrar sus registros de horas.

## Características principales

- Registro de horas de trabajo y otros tipos de actividades
- Consulta de registros por fechas y tipos de actividad
- Cálculo automático de totales de tiempo
- Formateado de tiempo en formato legible (ej: "8h 30min")
- Documentación API con Swagger/OpenAPI
- Autenticación mediante tokens JWT/Sanctum

## Tecnologías utilizadas

- PHP 8.x
- Laravel Framework
- MySQL/PostgreSQL
- Eloquent ORM
- Laravel Sanctum/JWT para autenticación
- OpenAPI/Swagger para documentación de API

## Requisitos

- PHP >= 8.0
- Composer
- MySQL, PostgreSQL u otro sistema de base de datos compatible
- Extensiones PHP requeridas por Laravel

## Instalación

1. Clona el repositorio:

   ```
   git clone https://github.com/Alejo-Santis/api-timesheet.git
   cd api-timesheet
   ```

2. Instala las dependencias:

   ```
   composer install
   ```

3. Configura las variables de entorno:

   ```
   cp .env.example .env
   ```

   Edita el archivo `.env` con tus configuraciones de base de datos y otros parámetros necesarios

4. Genera la clave de la aplicación:

   ```
   php artisan key:generate
   ```

5. Ejecuta las migraciones:

   ```
   php artisan migrate
   ```

6. Opcional: Ejecuta los seeders para datos iniciales:

   ```
   php artisan db:seed
   ```

7. Inicia el servidor de desarrollo:

   ```
   php artisan serve
   ```

## Estructura del proyecto

El proyecto sigue la estructura estándar de Laravel con algunos componentes específicos:

```
api-timesheet/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── TimesheetController.php  # Controlador principal para gestión de horas
│   │   ├── Requests/
│   │   │   └── StoreTimesheetRequest.php  # Validación para la creación de registros
│   │   └── Resources/
│   │       └── TimesheetResource.php  # Transformación de datos para la API
│   ├── Models/
│   │   └── Timesheet.php  # Modelo Eloquent para registros de horas
│   └── Service/
│       └── TimesheetService.php  # Lógica de negocio para cálculos de horas
├── routes/
│   └── api.php  # Definición de rutas de la API
└── ...
```

## API Endpoints

La API proporciona los siguientes endpoints para la gestión de registros de horas:

### Timesheets

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/timesheets` | Listar todos los registros de horas del usuario autenticado (con filtros opcionales) |
| POST | `/api/timesheets` | Crear un nuevo registro de horas |
| GET | `/api/timesheets/total` | Obtener total de minutos acumulados por tipo de actividad |
| GET | `/api/timesheets/{id}` | Mostrar un registro específico |
| PUT | `/api/timesheets/{id}` | Actualizar un registro existente |
| DELETE | `/api/timesheets/{id}` | Eliminar un registro |

### Parámetros de filtrado

Para el endpoint `GET /api/timesheets` y `GET /api/timesheets/total` se pueden utilizar los siguientes parámetros de filtrado:

- `type`: Filtrar por tipo de actividad (por defecto: "work")
- `from`: Fecha de inicio para filtrar registros (formato YYYY-MM-DD)
- `to`: Fecha de fin para filtrar registros (formato YYYY-MM-DD)

## Estructura de datos

### Modelo Timesheet

El modelo principal de la aplicación es `Timesheet` que representa un registro de horas:

```php
/**
 * @OA\Schema(
 *   schema="Timesheet",
 *   required={"type", "start_time"},
 *   @OA\Property(property="id", type="integer", format="int64"),
 *   @OA\Property(property="user_id", type="integer"),
 *   @OA\Property(property="type", type="string", example="work"),
 *   @OA\Property(property="start_time", type="string", format="date-time"),
 *   @OA\Property(property="end_time", type="string", format="date-time"),
 *   @OA\Property(property="description", type="string"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
```

## Documentación de la API

La API está documentada utilizando OpenAPI/Swagger. Puedes acceder a la documentación completa en la ruta `/api/documentation` después de iniciar el servidor.

Para generar la documentación, utiliza:

```
php artisan l5-swagger:generate
```

## Autenticación

La API utiliza autenticación mediante tokens (JWT o Laravel Sanctum). Para acceder a los endpoints protegidos, es necesario incluir un token de autenticación en las cabeceras de la petición:

```
Authorization: Bearer {tu-token}
```

## Ejemplos de uso

### Crear un nuevo registro de horas

```bash
curl -X POST "http://localhost:8000/api/timesheets" \
  -H "Authorization: Bearer {tu-token}" \
  -H "Content-Type: application/json" \
  -d '{
    "type": "work",
    "start_time": "2023-05-10T09:00:00",
    "end_time": "2023-05-10T17:30:00",
    "description": "Desarrollo de nuevas funcionalidades"
  }'
```

### Obtener total de horas trabajadas en un período

```bash
curl -X GET "http://localhost:8000/api/timesheets/total?type=work&from=2023-05-01&to=2023-05-31" \
  -H "Authorization: Bearer {tu-token}"
```

## Desarrollo

### Scripts disponibles

- `php artisan serve` - Inicia el servidor de desarrollo
- `php artisan test` - Ejecuta pruebas
- `php artisan migrate:fresh --seed` - Recrea la base de datos con datos iniciales

## Contribuir

1. Crea un fork del repositorio
2. Crea una rama para tu característica (`git checkout -b feature/amazing-feature`)
3. Realiza tus cambios y haz commit (`git commit -m 'Add some amazing feature'`)
4. Sube los cambios a tu fork (`git push origin feature/amazing-feature`)
5. Abre un Pull Request

## Licencia

Este proyecto está licenciado bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para más detalles.

## Autor

Alejo Santis - [GitHub](https://github.com/Alejo-Santis)
