# Código para Apartados e Inventario

Por favor, restaura tu `index.html` a la versión limpia y agrega el siguiente código manualmente.

---

## 1. Navegación del Sidebar (index.html)

Busca en tu sidebar los nav-items y añade estos **DESPUÉS de "Ventas"** y **ANTES de "Backup"**:

```html
<li class="nav-item" id="nav-apartados" style="display: none;">
    <a class="nav-link" onclick="showSection('apartados')">
        <i class="fas fa-clock"></i>
        <span>Apartados</span>
    </a>
</li>
<li class="nav-item" id="nav-inventario" style="display: none;">
    <a class="nav-link" onclick="showSection('inventario')">
        <i class="fas fa-warehouse"></i>
        <span>Inventario</span>
    </a>
</li>
```

---

## 2. Secciones HTML (index.html)

Añade estas secciones **DESPUÉS de la sección backup-section** y **ANTES de los modales**:

```html
<!-- APARTADOS SECTION -->
<section id="apartados-section" class="content-section">
    <div class="section-header">
        <h2>Gestión de Apartados</h2>
        <p>Sistema de apartado con pagos a plazos</p>
    </div>
    <div class="table-container">
        <div class="table-header">
            <button class="btn btn-primary" onclick="openApartadoModal()" id="btn-add-apartado">
                <i class="fas fa-plus"></i> Nuevo Apartado
            </button>
            <select id="filter-apartados-estado" onchange="loadApartados()" style="padding: 10px; border-radius: 8px; border: 1px solid #ddd;">
                <option value="">Todos los estados</option>
                <option value="activo">Activo</option>
                <option value="completado">Completado</option>
                <option value="cancelado">Cancelado</option>
            </select>
        </div>
        <table id="apartados-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Fecha</th>
                    <th>Fecha Límite</th>
                    <th>Total</th>
                    <th>Pagado</th>
                    <th>Pendiente</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="apartados-table-body">
                <tr>
                    <td colspan="9" class="loading active">
                        <div class="spinner"></div>
                        Cargando apartados...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<!-- INVENTARIO SECTION -->
<section id="inventario-section" class="content-section">
    <div class="section-header">
        <h2>Gestión de Inventario</h2>
        <p>Control de stock y movimientos</p>
    </div>
    <div class="table-container">
        <div class="table-header">
            <button class="btn btn-primary" onclick="openAjusteInventarioModal()">
                <i class="fas fa-edit"></i> Ajuste Manual
            </button>
            <button class="btn btn-secondary" onclick="verMovimientos()">
                <i class="fas fa-history"></i> Ver Movimientos
            </button>
            <div class="search-box">
                <input type="text" id="search-inventario" placeholder="Buscar producto..." 
                    onkeyup="searchTable('inventario-table', 'search-inventario')">
            </div>
        </div>
        <table id="inventario-table">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Producto</th>
                    <th>Categoría</th>
                    <th>Disponible</th>
                    <th>Apartado</th>
                    <th>Total</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody id="inventario-table-body">
                <tr>
                    <td colspan="7" class="loading active">
                        <div class="spinner"></div>
                        Cargando inventario...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</section>
```

---

## 3. Modales (index.html)

Añade estos modales **ANTES de `<script src="app.js">`**:

```html
<!-- Apartado Modal -->
<div id="apartado-modal" class="modal">
    <div class="modal-content" style="max-width: 800px;">
        <div class="modal-header">
            <h3>Nuevo Apartado</h3>
            <button class="close-modal" onclick="closeApartadoModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form onsubmit="saveApartado(event)">
            <div class="form-group">
                <label>Cliente</label>
                <select id="apartado-cliente" required>
                    <option value="">Seleccione un cliente...</option>
                </select>
            </div>
            <div class="form-group">
                <label>Días de Plazo (máximo 90)</label>
                <input type="number" id="apartado-dias" value="90" min="1" max="90">
            </div>
            <div class="form-group">
                <label>Productos</label>
                <div style="max-height: 200px; overflow-y: auto;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th style="width: 100px;">Cantidad</th>
                                <th style="width: 120px;">Precio ($)</th>
                                <th style="width: 50px;"></th>
                            </tr>
                        </thead>
                        <tbody id="apartado-productos-body"></tbody>
                    </table>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="addApartadoRow()" style="margin-top: 10px;">
                    <i class="fas fa-plus"></i> Agregar Producto
                </button>
            </div>
            <div class="form-group">
                <label>Abono Inicial (opcional)</label>
                <input type="number" id="apartado-abono" step="0.01" min="0" placeholder="0.00">
            </div>
            <div class="form-group">
                <label>Observaciones</label>
                <textarea id="apartado-observaciones" rows="2"></textarea>
            </div>
            <div class="form-group" style="text-align: right; font-size: 1.2em; font-weight: bold;">
                Total: $<span id="apartado-total">0.00</span>
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeApartadoModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Crear Apartado</button>
            </div>
        </form>
    </div>
</div>

<!-- Pago Apartado Modal -->
<div id="pago-apartado-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Registrar Pago</h3>
            <button class="close-modal" onclick="closePagoApartadoModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form onsubmit="registrarPagoApartado(event)">
            <input type="hidden" id="pago-apartado-id">
            <div class="form-group">
                <label>Monto Pendiente</label>
                <input type="text" id="pago-apartado-pendiente" readonly style="background: #f5f5f5;">
            </div>
            <div class="form-group">
                <label>Monto a Pagar</label>
                <input type="number" id="pago-apartado-monto" step="0.01" min="0.01" required>
            </div>
            <div class="form-group">
                <label>Observación</label>
                <input type="text" id="pago-apartado-observacion" placeholder="Ej: Abono mensual">
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closePagoApartadoModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Registrar Pago</button>
            </div>
        </form>
    </div>
</div>

<!-- Ajuste Inventario Modal -->
<div id="ajuste-inventario-modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Ajuste de Inventario</h3>
            <button class="close-modal" onclick="closeAjusteInventarioModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form onsubmit="realizarAjusteInventario(event)">
            <div class="form-group">
                <label>Producto</label>
                <select id="ajuste-producto" required>
                    <option value="">Seleccione un producto...</option>
                </select>
            </div>
            <div class="form-group">
                <label>Tipo de Ajuste</label>
                <select id="ajuste-tipo" required>
                    <option value="entrada">Entrada (aumentar stock)</option>
                    <option value="salida">Salida (reducir stock)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Cantidad</label>
                <input type="number" id="ajuste-cantidad" min="1" required>
            </div>
            <div class="form-group">
                <label>Observación</label>
                <input type="text" id="ajuste-observacion" placeholder="Motivo del ajuste">
            </div>
            <div class="form-actions">
                <button type="button" class="btn btn-outline" onclick="closeAjusteInventarioModal()">Cancelar</button>
                <button type="submit" class="btn btn-primary">Aplicar Ajuste</button>
            </div>
        </form>
    </div>
</div>
```

---

## 4. Permisos (app.js)

Busca la función `setupRolePermissions()` en app.js y añade estas líneas dentro de la condición del Encargado:

```javascript
// Dentro de if (rol === 'Encargado') { ... }
if (document.getElementById('nav-apartados')) {
    document.getElementById('nav-apartados').style.display = 'block';
}
if (document.getElementById('nav-inventario')) {
    document.getElementById('nav-inventario').style.display = 'block';
}
```

---

## 5. Backend (Ya Completado ✅)

Los archivos `models.py` y `app.py` ya contienen:
- Modelos: `Apartado`, `DetalleApartado`, `PagoApartado`, `MovimientoInventario`
- Endpoints API para todas las operaciones

---

## 6. Importante: Migración de Base de Datos

Después de reiniciar el servidor Flask, las nuevas tablas se crearán automáticamente con `db.create_all()`.
