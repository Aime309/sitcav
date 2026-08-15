# Subir el frontend a tu hosting gratuito (GitHub Pages o aimee.42web.io)

Este folder es la INTERFAZ (solo HTML/JS estático). El API vive en
PythonAnywhere (ver `../api/README_PA.md`).

## PASO 0 — Configurar la URL del API (IMPORTANTE)

Antes de subir, edita estos 2 archivos y pon TU dominio real del API:

- `app.js` → línea ~4:
  ```js
  const API_BASE_URL = 'https://Laikimist.pythonanywhere.com';
  ```
- `tienda.js` → línea ~4: lo mismo.

Sin este cambio la web intentará conectarse a `Laikimist.pythonanywhere.com`
con el placeholder y no cargará datos.

## PASO 1 — Elegir dónde subir (SOLO 1 opción)

### Opción A: GitHub Pages (recomendada — dominio `tuusuario.github.io`)

1. Crea un repositorio en https://github.com (nombre p.ej. `sitcav`).
2. Sube DENTRO de la raíz del repositorio estos 4 archivos (tal cual, sin
   subcarpetas):
   - `index.html` (la TIENDA — es la página principal por defecto)
   - `panel.html` (el sistema interno / panel de administración)
   - `app.js`
   - `tienda.js`
3. En el repositorio: **Settings** → **Pages** → *Build and deployment* →
   Source **Deploy from a branch** → rama `main` / carpeta `/ (root)` → Save.
4. Espera 1-2 minutos. Tu web queda en: `https://aime309.github.io/sitcav/`
   (el API ya permite orígenes `*.github.io` en CORS — no hace falta nada más).
   Al entrar se carga la TIENDA; el panel queda en `/sitcav/panel.html`.

### Opción B: InfinityFree (tu dominio aimee.42web.io)

1. Entra al panel de InfinityFree (https://infinityfree.com/ → Member Area)
   y abre el **File Manager** de tu cuenta (o usa FTP: host `ftpupload.net`,
   usuario y clave de tu cuenta).
2. Navega a la carpeta `htdocs/`.
3. Crea la carpeta `sitcav/` si no existe.
4. Sube DENTRO de `htdocs/sitcav/` estos 4 archivos (tal cual, sin subcarpetas):
   - `index.html` (la TIENDA — página principal)
   - `panel.html` (el sistema interno)
   - `app.js`
   - `tienda.js`
5. Tu web queda en: `https://aimee.42web.io/sitcav/` (tienda por defecto;
   el panel en `.../sitcav/panel.html`)

## PASO 2 — Probar

- Abre tu web (`https://aime309.github.io/sitcav/` o
  `https://aimee.42web.io/sitcav/`) → se carga la TIENDA (landing).
- Registra un cliente (con captcha), inicia sesión, aparta un producto.
- Para el panel: entra por el link de abajo de la tienda ("Acceso al sistema
  interno") o navega a `panel.html` (sección de login). Usa tus credenciales
  exportadas.
- Si algo no carga: abre DevTools (F12) → pestaña Network y revisa que las
  peticiones a `https://Laikimist.pythonanywhere.com/...` devuelvan 200.

## Notas

- No subas `app.js?v=20` ni `tienda.js?v=5` — sube `app.js` y `tienda.js` (el `?v=` es solo cache-buster).
- Si cambias el frontend, sube de nuevo los archivos y sube el número `?v=`
  en el `<script>` del HTML para que el navegador no use la versión vieja.
- La tienda y el panel comparten el mismo API y los mismos datos.
- Sesión: el token dura 12 horas; al expirar la web te devuelve al login.
