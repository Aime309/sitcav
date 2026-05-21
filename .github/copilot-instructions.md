# Instrucciones de Copilot para este repositorio

## Comandos de Construcción, Prueba y Lint

- **Instalar dependencias:**
  ```powershell
  uv sync
  ```
- **Iniciar servidor de desarrollo:**
  ```powershell
  vercel dev
  ```
  Acceso en: http://localhost:3000
- **Ejecutar todas las pruebas:**
  ```powershell
  uv run pytest
  ```
- **Ejecutar una sola prueba:**
  ```powershell
  uv run pytest tests/test_user_data.py
  ```
- **Chequeos rápidos de API:**
  ```powershell
  curl http://localhost:3000/api/productos
  curl http://localhost:3000/api/cotizacion/actual
  ```

## Arquitectura de Alto Nivel

- **Backend:** Python 3.12.x, Flask, SQLAlchemy (SQLite). Punto de entrada: `app.py`.
- **Frontend:** Servido a través de plantillas Jinja2 (`templates/index.html`), lógica principal en `static/app.js`.
- **Generación de PDF:** `pdf_generator.py` usa ReportLab para crear facturas en `instance/facturas/`.
- **Base de Datos:** Archivo SQLite en `instance/system_data.db`, inicializado mediante `db.py` y `schema.sql`.
- **API:** Centralizada en `api.py`. Lógica dividida en modelos (nombres de archivo en singular, ej. `producto.py`) y blueprints (nombres de archivo en plural, ej. `productos.py`).
- **Autenticación:** Gestionada en `auth.py`.
- **UI basada en roles:** La visibilidad de los módulos se controla mediante `setupRolePermissions()` en `static/app.js`.
- **Pruebas:** Basadas en Pytest, con fixtures en `tests/conftest.py` y pruebas de ejemplo en `tests/`.
- **Despliegue:** Configuración de Vercel en `vercel.json`.

## Convenciones Clave

- **Idioma:** Usar español para los nombres de modelos, rutas y variables.
- **Python:** Seguir PEP 8. Usar nombres claros y descriptivos.
- **JavaScript:** Usar ES6+, async/await y nombres descriptivos.
- **Datos:** Todos los datos locales (cargas, PDFs, BD) se almacenan en `instance/` (nunca confirmar estos archivos).
- **Frontend:** Nunca abrir `index.html` directamente; usar siempre el servidor de desarrollo.
- **Base de Datos:** En la primera ejecución, `app.py` realiza la migración automática y siembra la BD si es necesario.
- **Datos Sensibles:** Nunca confirmar credenciales o datos locales.

---

Este archivo resume los comandos de construcción/prueba, la arquitectura y las convenciones para Copilot y futuros colaboradores. ¿Te gustaría ajustar algo o añadir cobertura para otras áreas (ej. pruebas avanzadas, CI o despliegue)?
