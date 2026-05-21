# Sistema de Gestión Administrativo

Guía de instalación e inicio para ejecutar el proyecto localmente.

## Requisitos previos

- Python 3.12.x (requerido)
- Navegador web moderno (Chrome, Firefox, Edge)
- Conexión a internet (solo para instalar dependencias)
- uv (para gestión de dependencias Python)
- vercel CLI (para entorno de desarrollo)

## Instalación

En PowerShell, dentro de `<ruta-del-proyecto>`:

```powershell
uv sync
vercel dev
```

Acceso: `http://localhost:3000`


## Credenciales de prueba

- Encargado: `12345678` / `test1`
- Empleado Superior: `87654321` / `test1`
- Vendedor: `11223344` / `test1`

## Estructura principal

```text
<ruta-del-proyecto>/
├── static/                # Archivos estáticos (app.js, CSS, íconos)
│   ├── app.js             # Lógica principal del frontend
│   └── index.css          # Estilos globales
├── templates/             # Plantillas HTML
│   └── index.html         # Plantilla base (SPA)
├── tests/                 # Pruebas automáticas (pytest)
├── instance/              # Datos locales (BD SQLite, uploads, facturas)
├── app.py                 # Punto de entrada y App Factory
├── api.py                 # Registro central de Blueprints
├── db.py                  # Configuración de SQLAlchemy y utilidades
├── auth.py                # Lógica de autenticación
├── pdf_generator.py       # Generación de reportes y facturas PDF
├── schema.sql             # Esquema SQL para inicialización
├── [modelo].py            # Modelos SQLAlchemy (ej. producto.py, venta.py)
├── [blueprint].py         # Blueprints de la API (ej. productos.py, ventas.py)
├── pyproject.toml         # Dependencias (uv)
└── vercel.json            # Configuración de despliegue
```
* Cada entidad principal tiene un archivo para su modelo (singular) y otro para sus rutas API (plural).

## Módulos disponibles (según rol)

- Dashboard
- Empleados
- Productos
- Clientes
- Proveedores
- Compras
- Ventas
- Consultas
- Apartados
- Inventario
- Cotización
- Credenciales
- Reembolsos
- Estadísticas
- Backup

La visibilidad por rol se controla en `setupRolePermissions()` de `static/app.js`.

## Pruebas rápidas de API

```powershell
curl http://localhost:3000/api/productos
curl http://localhost:3000/api/cotizacion/actual
```

## Ejecutar pruebas

Ejecuta todas las pruebas:

```powershell
uv run pytest
```

## Resolución de Problemas

### Error: `No module named ...`

Asegúrate de haber ejecutado:
```powershell
uv sync
```

### Puerto 3000 en uso

Cierra el proceso que ocupa el puerto o cambia el puerto de vercel dev.

### Reiniciar base de datos local

Detén el backend, elimina `instance\system_data.db` y ejecuta nuevamente:
```powershell
vercel dev
```

## Notas importantes

- El backend corre sobre Flask y la base de datos local es SQLite (`instance\system_data.db`).
- El arranque de `app.py` realiza migración ligera e inicialización de datos.
- Todos los archivos subidos, backups y PDFs se almacenan en la carpeta `instance/`.
- El frontend se sirve dinámicamente como plantilla Jinja2, no abrir index.html directo.
- Usa siempre `uv sync` y `vercel dev` para desarrollo local.

