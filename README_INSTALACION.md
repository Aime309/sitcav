# Sistema de Gestión Administrativo - Guía de Instalación

## 📋 Requisitos Previos

- **Python 3.8 o superior** instalado en el sistema
- **PHP 8.2 o superior** instalado en el sistema
- **Composer 2 o superior** instalado en el sistema
- **Navegador web moderno** (Chrome, Firefox, Edge)
- **Conexión a internet** (solo para la primera instalación de dependencias)

## 🚀 Instalación Paso a Paso

### 1. Instalar Dependencias de Python

Abra PowerShell o CMD en la carpeta del proyecto y ejecute:

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

O si usa `py`:

```bash
py -c "import flask, flask_cors, flask_sqlalchemy, werkzeug, reportlab; print('✅ Todas las dependencias instaladas correctamente')"
```

## 🔧 Ejecución del Sistema

### Paso 1: Iniciar el Servidor Backend

En la carpeta del proyecto, ejecute:

```bash
composer api
```

**Salida esperada:**
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

### Paso 2: Abrir el Frontend

Abra el archivo `index.html` en su navegador web:

1. **Opción 1:** Doble clic en `index.html`
2. **Opción 2:** Click derecho → "Abrir con" → Navegador
3. **Opción 3:** Arrastrar `index.html` a una ventana del navegador

### Paso 3: Iniciar Sesión

Use una de las credenciales de prueba:

**Encargado (acceso total):**
- Usuario: `12345678`
- Contraseña: `test1`

**Empleado Superior:**
- Usuario: `87654321`
- Contraseña: `test1`

**Vendedor (acceso limitado):**
- Usuario: `11223344`
- Contraseña: `test1`

## 📁 Estructura del Proyecto

```
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

## 🧪 Testear la API Directamente

### Ejemplo: Listar Productos

```bash
curl http://127.0.0.1:5000/api/productos
```

### Ejemplo: Obtener Cotización

```bash
curl http://127.0.0.1:5000/api/cotizacion/actual
```

### Ejemplo: Crear Cliente

```bash
curl -X POST http://127.0.0.1:5000/api/clientes -H "Content-Type: application/json" -d "{\"nombre\":\"Pedro\",\"apellidos\":\"González\",\"cedula\":\"12341234\",\"telefono\":\"0424-1234567\"}"
```

## 🛠️ Troubleshooting

### Problema: "Module not found: flask"

**Solución:**
```bash
pip install Flask Flask-CORS Flask-SQLAlchemy werkzeug reportlab
```

### Problema: "Port 5000 already in use"

**Solución:** Edite `app.py` línea final y cambie el puerto:

```python
app.run(debug=True, port=5001)  # Cambiar 5000 por 5001
```

Luego actualice `app.js` línea 5:

```javascript
const API_BASE_URL = 'http://127.0.0.1:5001';
```

### Problema: "CORS error" en el navegador

**Solución:** Asegúrese de que el servidor esté corriendo y que Flask-CORS esté instalado:

```bash
pip install Flask-CORS
```

### Problema: La base de datos ya existe

Si quiere empezar de cero, elimine el archivo `instance/system_data.db` y reinicia el servidor.

## 📊 Datos de Prueba Incluidos

El sistema viene pre-cargado con:

- ✅ 3 usuarios (Encargado, Empleado Superior, Vendedor)
- ✅ 6 productos en 4 categorías
- ✅ 3 clientes
- ✅ 2 proveedores
- ✅ 1 venta de ejemplo
- ✅ 5 tipos de pago
- ✅ Cotización del dólar (35.50 Bs/USD)

## 🎓 Próximos Pasos

1. **Explorar** todos los módulos usando diferentes roles
2. **Crear** nuevos productos, clientes y proveedores
3. **Generar** un backup desde el módulo de respaldo
4. **Revisar** las estadísticas del dashboard
5. **Probar** las alertas de stock bajo (productos con < 10 unidades)

## 📚 Documentación API

Para ver todos los endpoints disponibles, consulte el archivo `walkthrough.md` en la carpeta de artefactos.

## ⚠️ Importante

- El sistema usa **autenticación simple** (no JWT). Para producción se recomienda implementar tokens.
- Los datos se guardan en **SQLite** (archivo local). Para entornos multi-usuario considere PostgreSQL o MySQL.
- Los backups se guardan en formato SQL en la carpeta `instance/`.

## ✅ Checklist de Instalación Exitosa

- [ ] Python 3.8+ instalado
- [ ] Dependencias instaladas (Flask, Flask-CORS, Flask-SQLAlchemy, werkzeug)
- [ ] Servidor ejecutándose en http://127.0.0.1:5000
- [ ] index.html abierto en el navegador
- [ ] Login exitoso con credenciales de prueba
- [ ] Módulos cargando datos correctamente

---

**¿Necesita ayuda?** Revise la sección de Troubleshooting o consulte los logs del servidor en la consola donde ejecutó `py app.py`.
