# Sistema de Gestión Administrativo

Guía unificada de instalación e inicio para ejecutar el proyecto localmente.

## Requisitos previos

- Python 3.8 o superior (recomendado 3.12)
- Navegador web moderno (Chrome, Firefox, Edge)
- Conexión a internet (solo para instalar dependencias)

## Instalación

En PowerShell, dentro de `<ruta-del-proyecto>`:

```powershell
py -m venv .venv
.\.venv\Scripts\Activate.ps1
pip install -e .
```

Si prefieres sin entorno virtual:

```powershell
pip install -e .
```

## Inicio rápido (sin CORS)

No abras `index.html` con doble clic (`file://`), eso genera errores de CORS.

Ejecuta **dos terminales**:
### Ejecución local

### Terminal 1: Servidor Flask (Backend y Frontend)

```powershell
cd <ruta-del-proyecto>
py src\app.py
```

Acceso: `http://localhost:5000`

### Abrir en el navegador

```text
http://localhost:5000
```

## Credenciales de prueba

- Encargado: `12345678` / `test1`
- Empleado Superior: `87654321` / `test1`
- Vendedor: `11223344` / `test1`

## Estructura principal

```text
<ruta-del-proyecto>/
├── src/
│   ├── app.py
│   ├── models.py
│   ├── pdf_generator.py
│   ├── fix_broken_images.py
│   ├── reset_password.py
│   ├── templates/
│   │   └── index.html
│   └── static/
│       └── app.js
├── pyproject.toml
└── instance/
```
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

La visibilidad por rol se controla en `setupRolePermissions()` de `src\static\app.js`.

## Pruebas rápidas de API

```powershell
curl http://localhost:5000/api/productos
curl http://localhost:5000/api/cotizacion/actual
```

## Ejecutar pruebas (pytest)

Instala dependencias de desarrollo:

```powershell
pip install -e ".[dev]"
```

Ejecuta todas las pruebas:

```powershell
pytest
```

## Troubleshooting

### Error: `No module named ...`

```powershell
pip install -e .
```

### Error de CORS

1. Verifica que `py src\app.py` esté corriendo.
2. Usa `http://localhost:5000` para acceder a la aplicación.

### Puerto 5000 en uso

Cierra el proceso que ocupa el puerto o cambia el puerto del backend en `src\app.py` y ajusta la URL API en `src\static\app.js`.

### Reiniciar base de datos local

Detén backend, elimina `instance\system_data.db` y ejecuta `py src\app.py` nuevamente.

## Notas importantes

- Backend en Flask + SQLite local (`instance\system_data.db`).
- El arranque de `src\app.py` ejecuta migración ligera e inicialización de datos.
- Uploads, backups y PDFs se guardan en `instance\`.
