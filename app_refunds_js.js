
// =====================================================================
// REEMBOLSOS
// =====================================================================
async function loadReembolsos() {
    const tbody = document.getElementById('reembolsos-table-body');
    if (!tbody) return;

    tbody.innerHTML = '<tr><td colspan="8" class="text-center">Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_URL}/reembolsos`);
        const reembolsos = await response.json();

        tbody.innerHTML = '';
        if (reembolsos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" class="text-center">No hay reembolsos registrados</td></tr>';
            return;
        }

        reembolsos.forEach(r => {
            const row = `
                <tr>
                    <td>${r.id}</td>
                    <td>#${r.id_venta}</td>
                    <td>${r.usuario_nombre}</td>
                    <td>$${r.monto_dolares.toFixed(2)}</td>
                    <td>${r.monto_bolivares.toFixed(2)} Bs</td>
                    <td>${r.tasa_cambio.toFixed(2)}</td>
                    <td>${r.fecha}</td>
                    <td>${r.motivo || '-'}</td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    } catch (error) {
        console.error('Error:', error);
        tbody.innerHTML = '<tr><td colspan="8" class="text-center text-danger">Error al cargar reembolsos</td></tr>';
    }
}

function openReembolsoModal() {
    $('#reembolso-modal').modal('show');
    document.getElementById('reembolso-form').reset();
    document.getElementById('venta-info-reembolso').textContent = '';
    document.getElementById('reembolso-calculo-bs').textContent = 'Monto en Bs: 0.00 (Tasa: 0.00)';
}

async function buscarVentaParaReembolso() {
    const ventaId = document.getElementById('reembolso-venta-id').value;
    if (!ventaId) return;

    try {
        // Usamos el endpoint de factura para verificar si existe, o podríamos crear uno específico
        // Por ahora intentaremos obtener la venta simulando una consulta o asumiendo que el backend validará
        // Idealmente deberíamos tener GET /api/ventas/:id

        // Como no tenemos GET /api/ventas/:id público fácil, usaremos la lógica de creación para validar
        // O mejor, implementamos una búsqueda simple en el cliente si ya cargamos ventas, pero no es eficiente.
        // Vamos a confiar en el usuario por ahora y mostrar la tasa si podemos.

        // NOTA: Para hacerlo bien, deberíamos tener un endpoint para obtener detalles de venta.
        // Asumiremos que el usuario ingresa el ID correcto y el backend valida.
        document.getElementById('venta-info-reembolso').textContent = 'Verificando ID...';

        // Hack: Usar el endpoint de factura para ver si existe (dará 200 o 404/500)
        const response = await fetch(`${API_URL}/factura/${ventaId}`);
        if (response.ok) {
            document.getElementById('venta-info-reembolso').textContent = 'Venta encontrada. Ingrese monto.';
            document.getElementById('venta-info-reembolso').className = 'form-text text-success';
        } else {
            document.getElementById('venta-info-reembolso').textContent = 'Venta no encontrada';
            document.getElementById('venta-info-reembolso').className = 'form-text text-danger';
        }

    } catch (error) {
        console.error(error);
    }
}

async function createReembolso() {
    const ventaId = document.getElementById('reembolso-venta-id').value;
    const monto = document.getElementById('reembolso-monto').value;
    const motivo = document.getElementById('reembolso-motivo').value;

    if (!ventaId || !monto || !motivo) {
        alert('Por favor complete todos los campos');
        return;
    }

    try {
        const response = await fetch(`${API_URL}/reembolsos`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                id_venta: ventaId,
                id_usuario: currentUser.id,
                monto_dolares: parseFloat(monto),
                motivo: motivo
            })
        });

        const result = await response.json();

        if (result.success) {
            alert('Reembolso procesado exitosamente');
            $('#reembolso-modal').modal('hide');
            loadReembolsos();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar reembolso');
    }
}
