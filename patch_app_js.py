"""
Script para añadir las funciones de Apartados e Inventario a app.js
Ejecutar con: python patch_app_js.py
"""

def patch_app_js():
    # Leer el archivo
    with open('app.js', 'r', encoding='utf-8') as f:
        content = f.read()
    
    # 1. Verificar si ya están las funciones
    if 'loadApartados' in content:
        print("⚠ Las funciones de Apartados ya existen en app.js")
        return
    
    # 2. Código de Apartados e Inventario a añadir
    apartados_code = '''

// =============================================
// MÓDULO DE APARTADOS E INVENTARIO
// =============================================

let apartadosProductosData = [];

// Cargar apartados
async function loadApartados() {
    const tbody = document.getElementById('apartados-table-body');
    if (!tbody) return;
    
    tbody.innerHTML = '<tr><td colspan="9" class="loading active"><div class="spinner"></div>Cargando apartados...</td></tr>';
    
    try {
        const estadoFilter = document.getElementById('filter-apartados-estado')?.value || '';
        const url = estadoFilter ? `${API_URL}/apartados?estado=${estadoFilter}` : `${API_URL}/apartados`;
        
        const response = await fetch(url);
        const data = await response.json();
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 40px;">No hay apartados registrados</td></tr>';
            return;
        }
        
        tbody.innerHTML = data.map(a => `
            <tr>
                <td>${a.id}</td>
                <td>${a.cliente?.nombre || 'N/A'} ${a.cliente?.apellidos || ''}</td>
                <td>${formatDateApartado(a.fecha_creacion)}</td>
                <td>${formatDateApartado(a.fecha_limite)}</td>
                <td>$${parseFloat(a.monto_total || 0).toFixed(2)}</td>
                <td style="color: var(--success);">$${parseFloat(a.monto_pagado || 0).toFixed(2)}</td>
                <td style="color: var(--danger);">$${(parseFloat(a.monto_total || 0) - parseFloat(a.monto_pagado || 0)).toFixed(2)}</td>
                <td><span class="badge ${getEstadoBadgeClass(a.estado)}">${a.estado}</span></td>
                <td>
                    <button class="btn-icon" onclick="verDetalleApartado(${a.id})" title="Ver detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    ${a.estado === 'activo' ? `
                        <button class="btn-icon" onclick="openPagoApartadoModal(${a.id}, ${a.monto_total - a.monto_pagado})" title="Registrar pago">
                            <i class="fas fa-dollar-sign"></i>
                        </button>
                        <button class="btn-icon" onclick="completarApartado(${a.id})" title="Completar" style="color: var(--success);">
                            <i class="fas fa-check"></i>
                        </button>
                        <button class="btn-icon" onclick="cancelarApartado(${a.id})" title="Cancelar" style="color: var(--danger);">
                            <i class="fas fa-times"></i>
                        </button>
                    ` : ''}
                </td>
            </tr>
        `).join('');
        
    } catch (error) {
        console.error('Error loading apartados:', error);
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 40px; color: var(--danger);">Error al cargar apartados</td></tr>';
    }
}

function getEstadoBadgeClass(estado) {
    switch(estado) {
        case 'activo': return 'badge-warning';
        case 'completado': return 'badge-success';
        case 'cancelado': return 'badge-danger';
        default: return 'badge-secondary';
    }
}

function formatDateApartado(dateStr) {
    if (!dateStr) return 'N/A';
    const date = new Date(dateStr);
    return date.toLocaleDateString('es-VE');
}

// Modal de nuevo apartado
async function openApartadoModal() {
    const modal = document.getElementById('apartado-modal');
    if (!modal) return;
    
    const clienteSelect = document.getElementById('apartado-cliente');
    try {
        const response = await fetch(`${API_URL}/clientes`);
        const clientes = await response.json();
        clienteSelect.innerHTML = '<option value="">Seleccione un cliente...</option>' + 
            clientes.map(c => `<option value="${c.id}">${c.nombre} ${c.apellidos} - ${c.cedula}</option>`).join('');
    } catch (error) {
        console.error('Error loading clientes:', error);
    }
    
    try {
        const response = await fetch(`${API_URL}/productos`);
        apartadosProductosData = await response.json();
    } catch (error) {
        console.error('Error loading productos:', error);
    }
    
    document.getElementById('apartado-dias').value = 90;
    document.getElementById('apartado-abono').value = '';
    document.getElementById('apartado-observaciones').value = '';
    document.getElementById('apartado-productos-body').innerHTML = '';
    document.getElementById('apartado-total').textContent = '0.00';
    
    addApartadoRow();
    modal.style.display = 'flex';
}

function closeApartadoModal() {
    document.getElementById('apartado-modal').style.display = 'none';
}

function addApartadoRow() {
    const tbody = document.getElementById('apartado-productos-body');
    const row = document.createElement('tr');
    const productOptions = apartadosProductosData
        .filter(p => p.cantidad_disponible > 0)
        .map(p => `<option value="${p.id}" data-precio="${p.precio}" data-stock="${p.cantidad_disponible}">${p.nombre} (Stock: ${p.cantidad_disponible})</option>`)
        .join('');
    
    row.innerHTML = `
        <td>
            <select class="apartado-producto" onchange="updateApartadoRow(this)" required>
                <option value="">Seleccione...</option>
                ${productOptions}
            </select>
        </td>
        <td><input type="number" class="apartado-cantidad" min="1" value="1" onchange="updateApartadoTotal()" required></td>
        <td><input type="number" class="apartado-precio" step="0.01" readonly style="background: #f5f5f5;"></td>
        <td><button type="button" class="btn-icon" onclick="removeApartadoRow(this)" style="color: var(--danger);"><i class="fas fa-trash"></i></button></td>
    `;
    tbody.appendChild(row);
}

function updateApartadoRow(select) {
    const row = select.closest('tr');
    const option = select.options[select.selectedIndex];
    const precio = option.dataset.precio || 0;
    const stock = parseInt(option.dataset.stock) || 0;
    
    row.querySelector('.apartado-precio').value = parseFloat(precio).toFixed(2);
    row.querySelector('.apartado-cantidad').max = stock;
    row.querySelector('.apartado-cantidad').value = 1;
    
    updateApartadoTotal();
}

function updateApartadoTotal() {
    let total = 0;
    document.querySelectorAll('#apartado-productos-body tr').forEach(row => {
        const cantidad = parseFloat(row.querySelector('.apartado-cantidad').value) || 0;
        const precio = parseFloat(row.querySelector('.apartado-precio').value) || 0;
        total += cantidad * precio;
    });
    document.getElementById('apartado-total').textContent = total.toFixed(2);
}

function removeApartadoRow(btn) {
    btn.closest('tr').remove();
    updateApartadoTotal();
}

async function saveApartado(event) {
    event.preventDefault();
    
    const clienteId = document.getElementById('apartado-cliente').value;
    const dias = parseInt(document.getElementById('apartado-dias').value) || 90;
    const abonoInicial = parseFloat(document.getElementById('apartado-abono').value) || 0;
    const observaciones = document.getElementById('apartado-observaciones').value;
    
    const productos = [];
    document.querySelectorAll('#apartado-productos-body tr').forEach(row => {
        const productoId = row.querySelector('.apartado-producto').value;
        const cantidad = parseInt(row.querySelector('.apartado-cantidad').value) || 0;
        const precioUnitario = parseFloat(row.querySelector('.apartado-precio').value) || 0;
        
        if (productoId && cantidad > 0) {
            productos.push({ producto_id: parseInt(productoId), cantidad, precio_unitario: precioUnitario });
        }
    });
    
    if (productos.length === 0) {
        alert('Debe agregar al menos un producto');
        return;
    }
    
    try {
        const response = await fetch(`${API_URL}/apartados`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                cliente_id: parseInt(clienteId),
                dias_limite: dias,
                productos,
                abono_inicial: abonoInicial,
                observaciones
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Apartado creado exitosamente');
            closeApartadoModal();
            loadApartados();
            loadProductos();
        } else {
            alert('Error: ' + (result.error || 'No se pudo crear el apartado'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión');
    }
}

function openPagoApartadoModal(apartadoId, pendiente) {
    document.getElementById('pago-apartado-id').value = apartadoId;
    document.getElementById('pago-apartado-pendiente').value = '$' + parseFloat(pendiente).toFixed(2);
    document.getElementById('pago-apartado-monto').value = '';
    document.getElementById('pago-apartado-monto').max = pendiente;
    document.getElementById('pago-apartado-observacion').value = '';
    document.getElementById('pago-apartado-modal').style.display = 'flex';
}

function closePagoApartadoModal() {
    document.getElementById('pago-apartado-modal').style.display = 'none';
}

async function registrarPagoApartado(event) {
    event.preventDefault();
    
    const apartadoId = document.getElementById('pago-apartado-id').value;
    const monto = parseFloat(document.getElementById('pago-apartado-monto').value);
    const observacion = document.getElementById('pago-apartado-observacion').value;
    
    try {
        const response = await fetch(`${API_URL}/apartados/${apartadoId}/pago`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ monto, observacion })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Pago registrado exitosamente');
            closePagoApartadoModal();
            loadApartados();
        } else {
            alert('Error: ' + (result.error || 'No se pudo registrar el pago'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión');
    }
}

async function completarApartado(id) {
    if (!confirm('¿Está seguro de completar este apartado? Se generará una venta.')) return;
    
    try {
        const response = await fetch(`${API_URL}/apartados/${id}/completar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Apartado completado exitosamente');
            loadApartados();
        } else {
            alert('Error: ' + (result.error || 'No se pudo completar'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión');
    }
}

async function cancelarApartado(id) {
    if (!confirm('¿Está seguro de cancelar este apartado? Los productos volverán al inventario. El reembolso debe gestionarse manualmente.')) return;
    
    try {
        const response = await fetch(`${API_URL}/apartados/${id}/cancelar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Apartado cancelado');
            loadApartados();
            loadProductos();
        } else {
            alert('Error: ' + (result.error || 'No se pudo cancelar'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión');
    }
}

async function verDetalleApartado(id) {
    try {
        const response = await fetch(`${API_URL}/apartados/${id}`);
        const data = await response.json();
        
        let detalleHTML = `
DETALLE DE APARTADO #${data.id}
========================
Cliente: ${data.cliente?.nombre} ${data.cliente?.apellidos}
Estado: ${data.estado}
Fecha: ${formatDateApartado(data.fecha_creacion)}
Límite: ${formatDateApartado(data.fecha_limite)}
Total: $${parseFloat(data.monto_total).toFixed(2)}
Pagado: $${parseFloat(data.monto_pagado).toFixed(2)}
Pendiente: $${(data.monto_total - data.monto_pagado).toFixed(2)}

PRODUCTOS:
`;
        
        data.detalles?.forEach(d => {
            detalleHTML += `- ${d.producto?.nombre}: ${d.cantidad} x $${parseFloat(d.precio_unitario).toFixed(2)}\\n`;
        });
        
        if (data.pagos?.length > 0) {
            detalleHTML += '\\nPAGOS:\\n';
            data.pagos.forEach(p => {
                detalleHTML += `- ${formatDateApartado(p.fecha)}: $${parseFloat(p.monto).toFixed(2)} ${p.observacion ? '(' + p.observacion + ')' : ''}\\n`;
            });
        }
        
        alert(detalleHTML);
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar detalle');
    }
}

// =============================================
// INVENTARIO
// =============================================

async function loadInventario() {
    const tbody = document.getElementById('inventario-table-body');
    if (!tbody) return;
    
    tbody.innerHTML = '<tr><td colspan="7" class="loading active"><div class="spinner"></div>Cargando inventario...</td></tr>';
    
    try {
        const response = await fetch(`${API_URL}/inventario`);
        const data = await response.json();
        
        if (data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px;">No hay productos</td></tr>';
            return;
        }
        
        tbody.innerHTML = data.map(p => {
            const apartado = p.cantidad_apartada || 0;
            const disponible = p.cantidad_disponible - apartado;
            const total = p.cantidad_disponible;
            const estado = disponible <= 0 ? 'Sin stock' : (disponible <= 5 ? 'Stock bajo' : 'Disponible');
            const estadoClass = disponible <= 0 ? 'badge-danger' : (disponible <= 5 ? 'badge-warning' : 'badge-success');
            
            return `
                <tr>
                    <td>${p.codigo || '-'}</td>
                    <td>${p.nombre}</td>
                    <td>${p.categoria?.nombre || 'Sin categoría'}</td>
                    <td style="color: ${disponible <= 5 ? 'var(--danger)' : 'inherit'};">${disponible}</td>
                    <td>${apartado}</td>
                    <td><strong>${total}</strong></td>
                    <td><span class="badge ${estadoClass}">${estado}</span></td>
                </tr>
            `;
        }).join('');
        
    } catch (error) {
        console.error('Error loading inventario:', error);
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 40px; color: var(--danger);">Error al cargar</td></tr>';
    }
}

async function openAjusteInventarioModal() {
    const modal = document.getElementById('ajuste-inventario-modal');
    if (!modal) return;
    
    const productoSelect = document.getElementById('ajuste-producto');
    try {
        const response = await fetch(`${API_URL}/productos`);
        const productos = await response.json();
        productoSelect.innerHTML = '<option value="">Seleccione un producto...</option>' + 
            productos.map(p => `<option value="${p.id}">${p.nombre} (Stock: ${p.cantidad_disponible})</option>`).join('');
    } catch (error) {
        console.error('Error loading productos:', error);
    }
    
    document.getElementById('ajuste-tipo').value = 'entrada';
    document.getElementById('ajuste-cantidad').value = '';
    document.getElementById('ajuste-observacion').value = '';
    
    modal.style.display = 'flex';
}

function closeAjusteInventarioModal() {
    document.getElementById('ajuste-inventario-modal').style.display = 'none';
}

async function realizarAjusteInventario(event) {
    event.preventDefault();
    
    const productoId = document.getElementById('ajuste-producto').value;
    const tipo = document.getElementById('ajuste-tipo').value;
    const cantidad = parseInt(document.getElementById('ajuste-cantidad').value);
    const observacion = document.getElementById('ajuste-observacion').value;
    
    try {
        const response = await fetch(`${API_URL}/inventario/ajuste`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                producto_id: parseInt(productoId),
                tipo,
                cantidad,
                observacion
            })
        });
        
        const result = await response.json();
        
        if (response.ok) {
            alert('Ajuste realizado exitosamente');
            closeAjusteInventarioModal();
            loadInventario();
            loadProductos();
        } else {
            alert('Error: ' + (result.error || 'No se pudo realizar el ajuste'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión');
    }
}

async function verMovimientos() {
    try {
        const response = await fetch(`${API_URL}/inventario/movimientos`);
        const data = await response.json();
        
        if (data.length === 0) {
            alert('No hay movimientos registrados');
            return;
        }
        
        let texto = 'HISTORIAL DE MOVIMIENTOS\\n========================\\n\\n';
        data.slice(0, 20).forEach(m => {
            texto += `${formatDateApartado(m.fecha)} | ${m.tipo.toUpperCase()} | ${m.producto?.nombre || 'N/A'} | Cant: ${m.cantidad} | ${m.razon || ''}\\n`;
        });
        
        alert(texto);
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar movimientos');
    }
}
'''
    
    # Añadir al final del archivo
    content += apartados_code
    
    # 3. Modificar showSection para incluir apartados e inventario
    if "case 'apartados':" not in content:
        # Buscar el switch de showSection
        show_section_pattern = r"(case 'backup':\s*loadBackups\(\);\s*break;)"
        replacement = r"\1\n        case 'apartados': loadApartados(); break;\n        case 'inventario': loadInventario(); break;"
        content = content.replace("case 'backup':", "case 'backup': break;\n        case 'apartados': loadApartados(); break;\n        case 'inventario': loadInventario(); break;\n        case 'backup-old':")
        
        # Intento alternativo si no hay case backup
        if "case 'apartados':" not in content:
            # Buscar el default del switch
            if "default:" in content:
                content = content.replace("default:", "case 'apartados': loadApartados(); break;\n        case 'inventario': loadInventario(); break;\n        default:")
        print("✓ showSection actualizado")
    else:
        print("⚠ showSection ya tiene los cases")
    
    # 4. Modificar setupRolePermissions para mostrar apartados e inventario a Encargado
    if "nav-apartados" not in content:
        # Buscar el bloque de Encargado en setupRolePermissions
        encargado_pattern = r"(if\s*\(\s*rol\s*===?\s*['\"]Encargado['\"]\s*\)\s*\{[^}]*)(})"
        if "document.getElementById('nav-backup')" in content:
            content = content.replace(
                "document.getElementById('nav-backup').style.display = 'block';",
                """document.getElementById('nav-backup').style.display = 'block';
        if (document.getElementById('nav-apartados')) document.getElementById('nav-apartados').style.display = 'block';
        if (document.getElementById('nav-inventario')) document.getElementById('nav-inventario').style.display = 'block';"""
            )
            print("✓ Permisos de Encargado actualizados")
    else:
        print("⚠ Permisos ya configurados")
    
    # Guardar el archivo
    with open('app.js', 'w', encoding='utf-8') as f:
        f.write(content)
    
    print("\n✅ app.js parchado correctamente!")
    print("\\nAhora:")
    print("1. Restaura index.html a la versión limpia")
    print("2. Ejecuta: python patch_apartados_inventario.py")
    print("3. Reinicia el servidor Flask")

if __name__ == '__main__':
    patch_app_js()
