# GEMINI.md - Sistema de Gestión Administrativo (SITCAV)

Este documento proporciona el contexto necesario para trabajar en el proyecto SITCAV, un sistema de gestión administrativo desarrollado con Python/Flask y un frontend dinámico en Vanilla JS.

## Project Overview

SITCAV es una herramienta diseñada para la gestión de inventario, ventas, compras, clientes y proveedores. El sistema incluye funcionalidades avanzadas como:
- **Gestión de Usuarios:** Autenticación basada en roles (Encargado, Empleado Superior, Vendedor).
- **Módulos Administrativos:** Dashboard estadístico, productos, categorías, clientes, proveedores.
- **Operaciones Comerciales:** Ventas (con facturación PDF), compras, apartados (sistema de layaway), reembolsos.
- **Inventario:** Control de stock y seguimiento de movimientos.
- **Utilidades:** Generación de reportes PDF, backups de base de datos y gestión de tasa de cambio (Dólar/Bolívar).

### Technology Stack
- **Backend:** Python 3.12, Flask, Flask-SQLAlchemy (SQLite).
- **Frontend:** HTML5, Jinja2, Vanilla JavaScript, Vanilla CSS.
- **Generación de Documentos:** ReportLab (para facturas y reportes PDF).
- **Gestión de Dependencias:** `uv`.
- **Despliegue:** Vercel (Configurado para servir Flask como Serverless Functions).

## Building and Running

### Requisitos previos
- [uv](https://github.com/astral-sh/uv) instalado.
- [Vercel CLI](https://vercel.com/docs/cli) (para desarrollo local simulando el entorno de producción).

### Comandos Clave

| Acción | Comando |
| :--- | :--- |
| Instalar dependencias | `uv sync` |
| Ejecutar servidor de desarrollo | `vercel dev` (Recomendado) o `python main.py` |
| Ejecutar pruebas | `uv run pytest` |
| Acceder a la app | `http://localhost:3000` (con `vercel dev`) |

### Configuración de Base de Datos
- La base de datos local se guarda en `instance/system_data.db`.
- Se inicializa automáticamente al arrancar la app usando `schema.sql` y `db.py`.
- En Vercel, la base de datos es de solo lectura (usando SQLite efímero o configuración específica); localmente es persistente en la carpeta `instance/`.

## Development Conventions

### Arquitectura de Archivos
- `main.py`: Punto de entrada y configuración de la aplicación (App Factory).
- `models.py`: Definición de modelos SQLAlchemy.
- `api.py`: Rutas principales de la lógica de negocio (Blueprint `api`).
- `auth.py`: Autenticación, registro y recuperación de contraseñas (Blueprint `auth`).
- `db.py`: Utilidades para inicialización y migración ligera de la base de datos.
- `static/app.js`: Lógica del frontend (SPA-like) que consume la API.
- `templates/index.html`: Plantilla base única para la interfaz.

### Estilo de Código y Patrones
- **Backend (Python):** Sigue PEP 8. Las respuestas de la API deben ser consistentes (JSON con campos `success` y `message` cuando sea necesario).
- **Frontend (JS):** Uso extensivo de `fetch` para peticiones asíncronas. La manipulación del DOM es directa (Vanilla JS).
- **Base de Datos:** Los modelos incluyen un método `to_dict()` para facilitar la serialización a JSON.
- **Seguridad:** Las contraseñas se almacenan hasheadas con `werkzeug.security`.

### Pruebas
- Las pruebas se encuentran en la carpeta `tests/` y utilizan `pytest`.
- Al añadir nuevas funcionalidades, crear el test correspondiente y verificar con `uv run pytest`.

## Credenciales de Prueba (Local)
- **Encargado:** `12345678` / `test1`
- **Empleado Superior:** `87654321` / `test1`
- **Vendedor:** `11223344` / `test1`

---
*Este archivo es una guía viva y debe actualizarse ante cambios significativos en la arquitectura o el flujo de trabajo del proyecto.*
