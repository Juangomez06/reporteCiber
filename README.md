# Reporte Ciber

**Una plataforma para que los colegios reporten y atiendan casos de ciberacoso.**

Los estudiantes pueden contar lo que está pasando (de forma anónima si lo prefieren) y los coordinadores pueden recibir esos reportes, asignarlos a alguien y hacerles seguimiento hasta resolverlos.

---

## ¿Qué puedes hacer con esta aplicación?

### Si eres estudiante
- Contar lo que te está pasando sin dar tu nombre, si así lo quieres.
- Adjuntar capturas de pantalla, fotos o cualquier prueba que tengas.
- Recibir un código para consultar cómo va tu caso, sin tener que registrarte.

### Si eres coordinador
- Ver todos los reportes del colegio en un solo lugar.
- Ver estadísticas rápidas: cuántos casos hay abiertos, cuántos son urgentes, cuántos ya se resolvieron.
- Asignarle cada caso a un estudiante responsable (un "orientador") para que lo acompañe.
- Cambiar el estado del caso (nuevo, en proceso, resuelto...) y dejar notas internas.
- Descargar reportes en Excel o PDF cuando se los pidan.
- Recibir un correo cuando llega un caso nuevo o cuando cambia algo importante.

---

## 🚀 Cómo poner el proyecto a andar

Sigue estos pasos **en orden**. Si algo falla, mira la sección [Problemas comunes](#-problemas-comunes) al final.

### 1. Descarga el proyecto y entra a la carpeta

```bash
git clone https://github.com/reporteciber/Reproteciber.git
cd reporteCiber
```

### 2. Instala lo que el proyecto necesita para funcionar

```bash
composer install
npm install
```

> Esto descarga las herramientas de PHP y de JavaScript. Puede tardar unos minutos la primera vez.

### 3. Prepara el archivo de configuración

```bash
cp .env.example .env
php artisan key:generate
```

> El archivo `.env` guarda cosas como la contraseña de la base de datos. El `.env.example` es una plantilla con valores de ejemplo.

### 4. Crea la base de datos

El proyecto usa SQLite, así que es solo crear un archivo vacío:

```bash
# En Windows (PowerShell)
type nul > database\database.sqlite

# En Mac o Linux
touch database/database.sqlite
```

### 5. Llena la base de datos con las tablas y datos de ejemplo

```bash
php artisan migrate --seed
```

> Esto crea todas las tablas y agrega usuarios de prueba para que puedas entrar a ver la app sin tener que registrarte.

### 6. Prepara la carpeta donde se guardan las imágenes

```bash
php artisan storage:link
```

> Esto crea un "atajo" para que las imágenes que suban los estudiantes se puedan ver desde el navegador.

### 7. Compila los estilos y gráficos

```bash
npm run build
```

### 8. ¡Enciende la aplicación!

```bash
php artisan serve
```

Abre tu navegador en: **http://127.0.0.1:8000**

---

## 👤 Usuarios de prueba

Los crea automáticamente el `UserSeeder` cuando corres el paso 5. Sirven para probar sin tener que registrarte:

| Rol | Correo | Contraseña |
|---|---|---|
| Coordinador | `coordinador@example.com` | `password` |
| Estudiante | `estudiante@example.com` | `password` |

> ⚠️ Cámbialos antes de publicar la app en un servidor real.

---

## 🧭 Cómo se usa

### Un estudiante que quiere reportar algo
1. Entra a la página y llena el formulario.
2. Elige el tipo de acoso, dónde pasó (WhatsApp, Instagram, etc.) y describe lo sucedido.
3. Sube las pruebas que tenga (capturas, fotos, videos).
4. Si no quiere dar su nombre, marca la casilla de **anónimo**.
5. Guarda el código que le da el sistema (algo como `CASO-8MZZB35V`). Con eso puede consultar el estado después.

### Un coordinador que recibe el reporte
1. Entra al panel y ve todos los casos en un vistazo.
2. Abre el caso que quiera atender.
3. Le asigna un estudiante responsable.
4. Cambia el estado a medida que avanza (nuevo → en revisión → asignado → resuelto).
5. Puede dejar notas internas solo para el equipo, o mensajes visibles para el que reportó.

---

## 📊 Lo que ves en el panel del coordinador

- **Total de casos** — todo lo que ha llegado.
- **Abiertos** — los que aún no se resuelven.
- **Resueltos** — los que ya se cerraron.
- **Críticos** — los que marcaste como urgentes.
- **Gráficos**: casos por estado, por tipo de acoso y por mes.

---

## 🛠️ Problemas comunes

### "No se ven las imágenes que subieron"

Probablemente falte el paso 6. Ejecuta:

```bash
php artisan storage:link
```

Debería decir *"The link has been connected"*.

### "Los estilos se ven rotos o sin color"

Falta compilar con Vite. Ejecuta:

```bash
npm run build
```

O si estás trabajando en el proyecto (haciendo cambios), déjalo corriendo en segundo plano:

```bash
npm run dev
```

### "No me deja entrar, me pide autorización"

Probablemente no corriste el paso 5 (los seeders) o no estás usando un usuario de prueba. Ejecuta:

```bash
php artisan migrate:fresh --seed
```

⚠️ **Ojo:** esto borra todos los datos y empieza de cero. Úsalo solo en desarrollo.

### "Error al conectar con la base de datos"

Verifica que exista el archivo `database/database.sqlite`. Si no está, vuelve al paso 4.

### "Después de un cambio no veo nada nuevo"

Limpia la caché de Laravel:

```bash
php artisan optimize:clear
```

---

## 🧪 Pruebas automáticas

Si quieres verificar que todo funciona bien:

```bash
php artisan test
```

Esto corre las pruebas que verifican el reporte anónimo, los permisos por rol, la asignación de casos y la API.

---

## 🔌 API (para desarrolladores)

La aplicación también expone una API para que otros sistemas puedan consultarla.

**Autenticación** — pide un token con tu correo y contraseña:

```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"coordinador@example.com","password":"password","device_name":"cli"}'
```

**Usar el token** — en las siguientes peticiones:

```bash
curl http://127.0.0.1:8000/api/casos \
  -H "Authorization: Bearer {token}"
```

Endpoints disponibles:
- `POST /api/login`
- `GET /api/casos`
- `GET /api/instituciones`

---

## 📁 Organización del código

Si vas a modificar el proyecto, esto es lo que hay dónde:

```
app/
├── Http/Controllers/      → qué hace cada pantalla
├── Http/Requests/         → reglas de validación de formularios
├── Models/                → las "cosas" del sistema (Caso, Usuario, Institución...)
├── Notifications/         → correos y avisos automáticos
├── Policies/              → quién puede ver/hacer qué
└── Services/
    └── CasoService.php    → toda la lógica de casos, separada de los controladores

resources/
├── js/
│   └── dashboard-charts.js → los gráficos del panel
└── views/                  → el HTML de cada pantalla

database/
├── migrations/             → definición de las tablas
└── seeders/                → datos de prueba
```

---

## ✅ Lo que está listo

- ✅ Registro e inicio de sesión con dos roles (coordinador y estudiante).
- ✅ Formulario para reportar casos, con opción anónima.
- ✅ Subida y visualización de evidencias (fotos, videos, documentos).
- ✅ Asignación de responsables y seguimiento del caso.
- ✅ Notas internas y notas visibles al que reportó.
- ✅ Historial de todo lo que ha pasado con cada caso.
- ✅ Panel con gráficos y estadísticas.
- ✅ Exportar a CSV y PDF.
- ✅ Correos automáticos cuando hay novedades.
- ✅ API con tokens para conectarse desde otros sistemas.
- ✅ Pruebas automáticas.

## 🔜 Lo que falta

- Migrar las tareas en segundo plano (correos, procesamiento) a Redis, para que la app sea más rápida cuando haya mucho tráfico.
- Preparar el despliegue en un servidor real (con la carpeta de evidencias en la nube).
- Cambiar los usuarios de prueba por cuentas reales.

---

## 🤝 Si vas a colaborar

1. Crea una rama nueva desde `main`:
   ```bash
   git checkout -b mejora/nombre-corto
   ```
2. Haz los cambios y súbelos con mensajes claros.
3. Abre un Pull Request explicando qué hiciste y por qué.

---

## 📄 Licencia

<!-- Elige una: MIT, propietario, o simplemente borra esta sección -->

---

**Hecho para apoyar la convivencia digital en colegios.** 💙
