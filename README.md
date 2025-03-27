# SITCAV

## Estructura de directorios

```
+---.phpunit.cache
    ...
+---app
|   +---build
|   ...
+---bd
+---comandos
+---docs
+---node_modules
|   ...
+---src
|   +---Autorizadores
|   +---Controladores
|   |   ...
|   +---estructuras
|   +---Modelos
|   +---rutas
|   +---svelte
|   |   ...
+---tests
|   |   ...
+---vendor
    ...
```

- **.phpunit.cache**: Directorio de cache de PHPUnit.
- **app**: Punto de entrada de la aplicación.
  - **build**: Recursos compilados (Svelte, CSS, Fuentes, etc).
- **bd**: Scripts de creación de la base de datos.
- **comandos**: Scripts de comandos de consola.
- **docs**: Documentación.
- **node_modules**: Dependencias de Node.js.
- **src**: Código fuente.
  - **Autorizadores**: Clases de autorización.
  - **Controladores**: Clases de controladores de peticiones HTTP.
  - **estructuras**: Estructuras HTML (punto de entrada a Svelte).
  - **Modelos**: Clases de modelos de Eloquent.
  - **rutas**: Rutas web/api públicas y privadas del backend.
  - **svelte**: Código de la interfaz de usuario.
