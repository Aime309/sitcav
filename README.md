# Sistema de Gestión Administrativo - SITCAV (PHP 8.3)

Este proyecto es una aplicación web para la gestión administrativa, migrada de una API en Python a una arquitectura moderna basada en **PHP 8.3** utilizando el framework **FlightPHP** para la API y un frontend dinámico.

## 📋 Requisitos Previos

- **PHP 8.3** o superior instalado.
- **Composer 2** o superior.
- **SQLite 3** habilitado en PHP (extensión `php_sqlite3`).
- **Navegador web moderno**.

## 🚀 Instalación y Ejecución

### 1. Instalar Dependencias de PHP

Ejecute el siguiente comando en la raíz del proyecto para instalar FlightPHP, Leaf Auth y otras dependencias:

```bash
composer install
```

### 2. Configuración

Asegúrese de tener un archivo `.env.php` (basado en `.env.dist.php`) con la configuración correcta de la base de datos. Por defecto, el sistema utiliza:

- Base de datos: `database/database.sqlite`

### 3. Ejecutar el Sistema

Para iniciar el servidor de desarrollo (API y Frontend), ejecute:

```bash
composer serve
```

El sistema estará disponible en: **http://localhost:8000**

---

## 🔐 Credenciales de Prueba

| Rol | Cédula | Contraseña |
|-----|--------|------------|
| Encargado (acceso total) | `12345678` | `test1` |
| Empleado Superior | `87654321` | `test1` |
| Vendedor (acceso limitado) | `11223344` | `test1` |

---

## 📁 Estructura del Proyecto (Migrado)

```bash
sitcav/
├── app/                    # Lógica de negocio (Modelos PSR-4)
│   ├── Models/             # Modelos de base de datos (Product, Sale, etc.)
│   └── Http/               # Controladores (opcional)
├── database/
│   └── database.sqlite     # Base de datos SQLite unificada
├── resources/
│   ├── js/app.js           # Frontend logic (AJAX a la API PHP)
│   ├── css/                # Estilos CSS
│   └── views/              # Vistas PHP (Layouts/Pages)
├── routes/
│   ├── api.php             # Definición de todos los endpoints REST
│   └── web.php             # Rutas para servir el frontend
├── tests/                  # Suite de tests (PHPUnit)
├── index.php               # Punto de entrada de la aplicación
├── composer.json           # Dependencias y scripts
└── .env.dist.php           # Plantilla de variables de entorno
```

---

## ✨ Funcionalidades Migradas

- ✅ **Dashboard**: Estadísticas en tiempo real (KPIs).
- ✅ **Inventario**: Gestión de productos con stock apartado y disponible.
- ✅ **Ventas**: Registro de ventas con transacciones seguras y stock dinámico.
- ✅ **Apartados**: Sistema completo de reserva de productos con abonos.
- ✅ **Estadísticas**: Gráficas de rendimiento (Ventas vs Compras).
- ✅ **Seguridad**: Recuperación de cuenta mediante preguntas de seguridad.
- ✅ **Localización**: Selección dinámica de Estados, Municipios y Sectores.

---

## 🧪 Pruebas (Tests)

Para ejecutar la suite de pruebas automatizadas y validar la integridad de la API:

```bash
composer test
```

---

## 🛠️ Desarrollo

La API se sirve bajo el prefijo `/api`. Por ejemplo:
- GET `/api/productos`
- GET `/api/estadisticas/resumen`

Todas las peticiones en `app.js` están centralizadas mediante la constante `API_BASE_URL`.

---

**SITCAV** - Sistema de Gestión Administrativo optimizado para despliegues ligeros en PHP.
