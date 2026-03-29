# Sistema de Gestión Administrativo - Guía de Instalación e Inicio

## 📋 Requisitos Previos

- **Python 3.8 o superior** instalado en el sistema
- **PHP 8.2 o superior** instalado en el sistema
- **Composer 2 o superior** instalado en el sistema
- **Navegador web moderno** (Chrome, Firefox, Edge)
- **Conexión a internet** (solo para la primera instalación de dependencias)

---

## 🚀 Instalación Paso a Paso

### 1. Instalar Dependencias de Python

Abra PowerShell o bash en la carpeta del proyecto y ejecute:

```bash
pip install Flask Flask-CORS Flask-SQLAlchemy werkzeug reportlab
```

### Detalle de las Librerías:

| Librería | Versión Recomendada | Propósito |
|----------|---------------------|-----------|
| **Flask** | 3.0+ | Framework web principal del backend |
| **Flask-CORS** | 4.0+ | Permitir peticiones cross-origin desde el frontend |
| **Flask-SQLAlchemy** | 3.0+ | ORM para manejo de base de datos SQLite |
| **werkzeug** | 3.0+ | Utilidades de seguridad (hash de contraseñas) |
| **reportlab** | 4.0+ | Generación profesional de archivos PDF |

> **Nota:** Estas son las únicas librerías necesarias. SQLite viene incluido con Python.

### 2. Verificar Instalación

Ejecute este comando para verificar que todo esté instalado correctamente:

```bash
python -c "import flask, flask_cors, flask_sqlalchemy, werkzeug, reportlab; print('✅ Todas las dependencias instaladas correctamente')"
```

---

## 🔧 Ejecución del Sistema

### 🔴 PROBLEMA ACTUAL: CORS

Si abres `index.html` directamente (doble click), tendrás errores de CORS porque el navegador bloquea peticiones desde `file://`.

### 🎯 SOLUCIÓN: Usar Servidores HTTP

Necesitas **2 terminales abiertas**:

#### Terminal 1: Backend API

```bash
cd ruta/del/proyecto
composer api
```

✅ Debe mostrar: `🚀 Servidor Flask corriendo en http://127.0.0.1:5000`

**Salida esperada completa:**

```
✅ Base de datos y tablas creadas.
📊 Añadiendo datos de prueba...
✅ Datos de prueba añadidos exitosamente!

📋 USUARIOS CREADOS:
   Encargado    - Cédula: 12345678 / Contraseña: test1
   Emp. Superior- Cédula: 87654321 / Contraseña: test1
   Vendedor     - Cédula: 11223344 / Contraseña: test1

💰 Cotización inicial: 35.5 Bs/USD
📦 Productos creados: 6
👥 Clientes creados: 3
🏪 Proveedores creados: 2

============================================================
🚀 Servidor Flask corriendo en http://127.0.0.1:5000
============================================================
```

> ⚠️ **No cierre esta ventana.** El servidor debe permanecer en ejecución para que el sistema funcione.

#### Terminal 2: Frontend HTTP Server

```bash
cd ruta/del/proyecto
composer frontend
```

✅ Debe mostrar: `✅ Servidor HTTP corriendo en http://localhost:8000`

#### Abrir Navegador:

http://localhost:8000/index.html

---

## 🔐 Iniciar Sesión

Use una de las credenciales de prueba:

| Rol | Cédula | Contraseña |
|-----|--------|------------|
| Encargado (acceso total) | `12345678` | `test1` |
| Empleado Superior | `87654321` | `test1` |
| Vendedor (acceso limitado) | `11223344` | `test1` |

---

## 📁 Estructura del Proyecto

```bash
c:/Users/nadet/Desktop/Proyecto/
├── app.py                  # ⭐ Servidor Flask completo
├── app.js                  # ⭐ JavaScript del frontend (modular)
├── models.py               # ⭐ Modelos de base de datos (16 tablas)
├── index.html              # ⭐ Interfaz de usuario
├── index.html.backup       # Backup del HTML original
├── instance/
│   └── system_data.db     # Base de datos SQLite (se crea automáticamente)
│   └── backup_*.sql       # Backups generados
└── [archivos antiguos]     # Pueden ignorarse/eliminarse
```

---

## ✨ Funcionalidades Disponibles

### ✅ Módulos Operativos:

1. **Dashboard** - Estadísticas en tiempo real
2. **Empleados** - CRUD completo con roles
3. **Inventario/Productos** - CRUD completo con categorías y alertas de stock
4. **Clientes** - CRUD completo con búsqueda
5. **Proveedores** - CRUD completo
6. **Ventas** - Visualización (creación manual vía API)
7. **Backup** - Crear backups y ver historial

### 🎯 Permisos por Rol:

| Módulo | Vendedor | Empleado Superior | Encargado |
|--------|----------|-------------------|-----------|
| Dashboard | ✅ | ✅ | ✅ |
| Empleados | ❌ | ❌ | ✅ |
| Productos | ❌ | ✅ | ✅ |
| Clientes | ✅ | ✅ | ✅ |
| Proveedores | ❌ | ❌ | ✅ |
| Ventas | ✅ | ✅ | ✅ |
| Backup | ❌ | ❌ | ✅ |

---

## 🧪 Testear la API Directamente

### Ejemplo: Listar Productos

```bash
curl http://127.0.0.1:5000/api/productos
```

### Ejemplo: Crear Cliente

```bash
curl -X POST http://127.0.0.1:5000/api/clientes -H "Content-Type: application/json" -d "{\"nombre\":\"Pedro\",\"apellidos\":\"González\",\"cedula\":\"12341234\",\"telefono\":\"0424-1234567\"}"
```

---

## 🛠️ Troubleshooting

### Error CORS
- **Causa:** Abrir `index.html` directamente desde el sistema de archivos.
- **Solución:** Usar siempre `composer frontend` para servir el frontend.

### "Module not found: flask"

```bash
pip install Flask Flask-CORS Flask-SQLAlchemy werkzeug reportlab
```

### Puerto 5000 en uso

Edite `app.py` línea final y cambie el puerto:

```py
app.run(debug=True, port=5001)  # Cambiar 5000 por 5001
```

Luego actualice `app.js` línea 5:

```js
const API_BASE_URL = 'http://127.0.0.1:5001';
```

### Base de datos corrupta o quieres empezar de cero

Elimine el archivo `instance/system_data.db` y reinicie el servidor con `composer api`.

---

## 📦 Datos de Prueba Incluidos

El sistema viene pre-cargado con:
- ✅ 3 usuarios (Encargado, Empleado Superior, Vendedor)
- ✅ 6 productos en 4 categorías
- ✅ 3 clientes
- ✅ 2 proveedores
- ✅ 1 venta de ejemplo
- ✅ 5 tipos de pago
- ✅ Cotización del dólar (35.50 Bs/USD)

---

## 📌 IMPORTANTE

❌ **NO abras index.html directamente** (doble click)
✅ **SIEMPRE usa http://localhost:8000/index.html**

¡Esto soluciona TODOS los errores de CORS!

---

## ✅ Checklist de Instalación Exitosa

- [ ] Python 3.8+ instalado
- [ ] Dependencias instaladas (Flask, Flask-CORS, Flask-SQLAlchemy, werkzeug)
- [ ] Servidor ejecutándose en http://127.0.0.1:5000
- [ ] Frontend servido en http://localhost:8000
- [ ] Login exitoso con credenciales de prueba
- [ ] Módulos cargando datos correctamente

---

**¿Necesita ayuda?** Revise la sección de Troubleshooting, verifique la consola del navegador (F12) o revise los logs del servidor en la terminal donde ejecutó `composer api`.
