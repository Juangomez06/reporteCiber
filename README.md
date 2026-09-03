# Reporte Ciber — Plataforma de convivencia digital

Sistema para reportar y gestionar casos de ciberacoso en instituciones educativas (Laravel 12).

## Estado del proyecto

**Fase 1 — Base (completa)**
- Autenticación (Laravel Breeze), roles `coordinador` / `estudiante`, middleware `role`.

**Fase 2 — Funcionalidad principal (completa)**
- CRUD de instituciones (solo coordinador).
- Formulario público de reporte de casos, con soporte de **reporte anónimo** (no se guarda `reporter_id`), validación de tipo de acoso/plataforma y subida de evidencias.
- Gestión de casos: asignación de orientador, cambio de estado con auditoría, notas privadas/visibles.
- Panel de control con métricas (casos por estado, tipo y mes) usando Chart.js.
- Exportación de informes a CSV y PDF (`barryvdh/laravel-dompdf`).

**Fase 3 — Extras (completa)**
- Notificaciones por correo y en la app (Laravel Notifications) al crear, asignar y cambiar estado de un caso.
- API REST con Laravel Sanctum (`/api/login`, `/api/casos`, `/api/instituciones`).
- Tests de Feature (reporte anónimo, permisos por rol, asignación, API).

**Pendiente**
- Colas con Redis en producción (actualmente `QUEUE_CONNECTION=sync`).
- Despliegue (servidor, storage en la nube).

## Instalación

```bash
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed
npm install && npm run build
php artisan serve
```

Usuarios de prueba (creados por `UserSeeder`):
- Coordinador: `coordinador@example.com` / `password`
- Estudiante: `estudiante@example.com` / `password`

## Tests

```bash
php artisan test
```

## API

Autenticación por token (Sanctum):

```bash
curl -X POST /api/login -d '{"email":"...","password":"...","device_name":"cli"}' -H "Content-Type: application/json"
```

Luego usar `Authorization: Bearer {token}` en `/api/casos`, `/api/instituciones`.

## Estructura relevante

- `app/Models` — Institucion, Caso, CasoEvidencia, CasoNota, CasoHistorial.
- `app/Services/CasoService.php` — lógica de negocio (creación, asignación, cambio de estado, notas), separada de los controladores.
- `app/Policies` — control de acceso por rol/institución.
- `app/Http/Requests` — validación vía Form Requests.
- `app/Notifications` — notificaciones de caso creado/asignado/actualizado.

## Buenas prácticas aplicadas

- Soft deletes en `casos` e `instituciones` (no se eliminan reportes físicamente).
- Route Model Binding en controladores.
- Lógica de negocio en `Services`, no en controladores.
- Form Requests para toda validación de entrada.
