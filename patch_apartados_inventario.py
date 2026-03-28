"""
Script para añadir los módulos de Apartados e Inventario a index.html
Ejecutar con: python patch_apartados_inventario.py
"""

import re

def patch_index_html():
    # Leer el archivo
    with open('index.html', 'r', encoding='utf-8') as f:
        content = f.read()
    
    # 1. Añadir nav-items para Apartados e Inventario DESPUÉS de Ventas y ANTES de Backup
    nav_apartados = '''                <li class="nav-item" id="nav-apartados" style="display: none;">
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
'''
    
    # Buscar el patrón de Ventas + Backup para insertar entre ellos
    ventas_pattern = r'(<li class="nav-item">\s*<a class="nav-link" onclick="showSection\(\'ventas\'\)">\s*<i class="fas fa-shopping-cart"></i>\s*<span>Ventas</span>\s*</a>\s*</li>\s*)(<li class="nav-item" id="nav-backup")'
    
    if re.search(ventas_pattern, content, re.DOTALL):
        content = re.sub(ventas_pattern, r'\1' + nav_apartados + r'\2', content, flags=re.DOTALL)
        print("✓ Nav items de Apartados e Inventario añadidos")
    else:
        print("⚠ No se encontró el patrón para nav items (puede que ya estén añadidos)")
    
    # 2. Añadir secciones HTML para Apartados e Inventario DESPUÉS de backup-section
    apartados_section = '''
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
                        <select id="filter-apartados-estado" onchange="loadApartados()" style="padding: 10px; border-radius: 8px; border: 1px solid #ddd; margin-left: 10px;">
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
                        <button class="btn btn-secondary" onclick="verMovimientos()" style="margin-left: 10px;">
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
'''
    
    # Buscar el cierre de backup-section para insertar después
    backup_close = '</section>\n        </main>'
    if backup_close in content and 'apartados-section' not in content:
        content = content.replace(backup_close, '</section>\n' + apartados_section + '\n        </main>')
        print("✓ Secciones de Apartados e Inventario añadidas")
    elif 'apartados-section' in content:
        print("⚠ Secciones ya existen")
    
    # 3. Añadir modales ANTES del script de app.js
    modales = '''
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

'''
    
    # Insertar modales antes de <script src="app.js">
    if 'apartado-modal' not in content:
        content = content.replace('<script src="app.js"></script>', modales + '    <script src="app.js"></script>')
        print("✓ Modales añadidos")
    else:
        print("⚠ Modales ya existen")
    
    # 4. Añadir CSS para badges
    badge_css = '''
        /* Badges para estados */
        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            display: inline-block;
        }
        .badge-success { background: #d4edda; color: #155724; }
        .badge-warning { background: #fff3cd; color: #856404; }
        .badge-danger { background: #f8d7da; color: #721c24; }
        .badge-secondary { background: #e2e3e5; color: #383d41; }
'''
    
    if '.badge {' not in content:
        content = content.replace('</style>', badge_css + '\n    </style>')
        print("✓ CSS de badges añadido")
    else:
        print("⚠ CSS de badges ya existe")
    
    # Guardar el archivo
    with open('index.html', 'w', encoding='utf-8') as f:
        f.write(content)
    
    print("\n✅ index.html parchado correctamente!")
    print("Ahora ejecuta: python patch_app_js.py para actualizar app.js")

if __name__ == '__main__':
    patch_index_html()
