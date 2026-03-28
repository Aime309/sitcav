# ✅ GUÍA DE INICIO - Sistema de Gestión

## 🔧 PROBLEMA ACTUAL: CORS

Si abres `index.html` directamente (doble click), tendrás errores de CORS porque el navegador bloquea peticiones desde `file://`.

---

## 🎯 SOLUCIÓN: Usar Servidores HTTP

Necesitas **2 terminales abiertas**:

### Terminal 1: Backend API
```bash
cd c:\Users\nadet\Desktop\Proyecto
py app.py
```
✅ Debe mostrar: `🚀 Servidor Flask corriendo en http://127.0.0.1:5000`

### Terminal 2: Frontend HTTP Server
```bash
cd c:\Users\nadet\Desktop\Proyecto
py serve.py
```
✅ Debe mostrar: `✅ Servidor HTTP corriendo en http://localhost:8000`

### Abrir Navegador:
```
http://localhost:8000/index.html
```

---

## 📝 RESUMEN DE ERRORES ARREGLADOS:

1. ✅ **app.py línea 268** - Error `NameError: name 'e' is not defined` - ARREGLADO
2. ✅ **Endpoint /api/productos** - Ahora acepta GET y POST
3. ✅ **CORS** - Se soluciona usando serve.py en vez de abrir el HTML directamente
4. ✅ **Base de datos** - Recreada con campo `imagen_url`

---

## ⚡ INICIO RÁPIDO:

### Paso 1: Detén el servidor actual (Ctrl+C)

### Paso 2: Abre DOS terminales PowerShell

**Terminal 1 - Backend:**
```powershell
cd c:\Users\nadet\Desktop\Proyecto
py app.py
```

**Terminal 2 - Frontend:**
```powershell
cd c:\Users\nadet\Desktop\Proyecto
py serve.py
```

### Paso 3: Abre navegador:
```
http://localhost:8000/index.html
```

---

## 🎉 DEBERÍA FUNCIONAR:

- ✅ Sin errores de CORS
- ✅ Login/Registro/Anónimo
- ✅ Dashboard con estadísticas
- ✅ Carrusel de productos CON IMÁGENES
- ✅ CRUD de productos/clientes funcionando
- ✅ Todo 100% funcional

---

## 🐛 Si Aún Hay Errores:

1. **Ver consola del navegador** (F12)
2. **Ver terminal del backend** para errores de Python
3. **Verificar que ambos servidores estén corriendo:**
   - Backend: http://127.0.0.1:5000
   - Frontend: http://localhost:8000

---

## 📌 IMPORTANTE:

❌ **NO abras index.html directamente** (doble click)
✅ **SIEMPRE usa http://localhost:8000/index.html**

¡Esto soluciona TODOS los errores de CORS!
