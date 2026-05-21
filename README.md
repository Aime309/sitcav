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
├── static/            # Archivos estáticos (CSS, íconos)
├── app.js             # Lógica principal del frontend (antes en static/)
├── templates/         # Plantillas HTML (principalmente index.html)
├── tests/             # Pruebas automáticas y utilidades de test
├── instance/          # Datos locales: base de datos y uploads
├── .venv/             # Entorno virtual local (ignorado en git)
├── app.py             # Punto de entrada principal de la app Flask
├── models.py          # Definición de modelos y base de datos
├── pdf_generator.py   # Lógica para generación de PDFs
├── uploads.py         # Rutas para servir archivos subidos
├── api.py             # Rutas y lógica de la API principal
├── auth.py            # Autenticación y rutas de login
├── db.py              # Inicialización y utilidades de base de datos
├── pyproject.toml     # Configuración de dependencias y Python
├── schema.sql         # Esquema SQL inicial de la base de datos
├── uv.lock            # Lockfile de dependencias Python (uv)
└── vercel.json        # Configuración de despliegue Vercel
```
* El contenido de las carpetas static/, templates/, tests/ e instance/ está oculto para simplificar la vista.

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

## Troubleshooting

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

