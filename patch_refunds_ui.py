"""
Patch script to update Refunds UI in index.html
"""
import re

# Read the file
with open('index.html', 'r', encoding='utf-8') as f:
    content = f.read()

# Replace the Refunds section
old_section = '''    <!-- SECCIÓN REEMBOLSOS -->
    <div id="reembolsos-section" class="content-section">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Gestión de Reembolsos</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="openReembolsoModal()">
                    <i class="fas fa-plus"></i> Nuevo Reembolso
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Venta ID</th>
                        <th>Usuario</th>
                        <th>Monto ($)</th>
                        <th>Monto (Bs)</th>
                        <th>Tasa</th>
                        <th>Fecha</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody id="reembolsos-table-body">
                    <!-- JS -->
                </tbody>
            </table>
        </div>
    </div>'''

new_section = '''    <!-- Reembolsos Section -->
    <div id="reembolsos-section" class="content-section">
        <div class="section-header">
            <h2><i class="fas fa-undo"></i> Gestión de Reembolsos</h2>
            <button class="btn btn-primary" onclick="openReembolsoModal()">
                <i class="fas fa-plus"></i> Nuevo Reembolso
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Venta ID</th>
                                <th>Usuario</th>
                                <th>Monto ($)</th>
                                <th>Monto (Bs)</th>
                                <th>Tasa</th>
                                <th>Fecha</th>
                                <th>Motivo</th>
                            </tr>
                        </thead>
                        <tbody id="reembolsos-table-body">
                            <!-- JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>'''

content = content.replace(old_section, new_section)

# Replace the old Bootstrap modal with the app's vanilla JS modal pattern
old_modal = '''    <!-- MODAL REEMBOLSO -->
    <div class="modal fade" id="reembolso-modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Procesar Reembolso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="reembolso-form">
                        <div class="form-group">
                            <label>ID de Venta</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="reembolso-venta-id" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button"
                                        onclick="buscarVentaParaReembolso()">Buscar</button>
                                </div>
                            </div>
                            <small class="form-text text-muted" id="venta-info-reembolso"></small>
                        </div>
                        <div class="form-group">
                            <label>Monto a Reembolsar ($)</label>
                            <input type="number" class="form-control" id="reembolso-monto" step="0.01" required>
                        </div>
                        <div class="form-group">
                            <label>Motivo</label>
                            <textarea class="form-control" id="reembolso-motivo" rows="2" required></textarea>
                        </div>
                        <div class="alert alert-info" id="reembolso-calculo-bs">
                            Monto en Bs: 0.00 (Tasa: 0.00)
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="createReembolso()">Procesar
                        Reembolso</button>
                </div>
            </div>
        </div>
    </div>'''

new_modal = '''    <!-- Reembolso Modal -->
    <div id="reembolso-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>Procesar Reembolso</h3>
                <button class="close-modal" onclick="closeReembolsoModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="reembolso-form" onsubmit="event.preventDefault(); createReembolso();">
                <div class="form-group">
                    <label>ID de Venta</label>
                    <div style="display: flex; gap: 10px;">
                        <input type="number" id="reembolso-venta-id" required style="flex: 1;">
                        <button type="button" class="btn btn-outline" onclick="buscarVentaParaReembolso()">Buscar</button>
                    </div>
                    <small id="venta-info-reembolso" style="color: #6b7280; margin-top: 5px; display: block;"></small>
                </div>
                <div class="form-group">
                    <label>Monto a Reembolsar ($)</label>
                    <input type="number" id="reembolso-monto" step="0.01" required>
                </div>
                <div class="form-group">
                    <label>Motivo</label>
                    <textarea id="reembolso-motivo" rows="2" required></textarea>
                </div>
                <div id="reembolso-calculo-bs" style="background: #dbeafe; padding: 10px; border-radius: 8px; margin-bottom: 15px; color: #1e40af;">
                    Monto en Bs: 0.00 (Tasa: 0.00)
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeReembolsoModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Procesar Reembolso</button>
                </div>
            </form>
        </div>
    </div>'''

content = content.replace(old_modal, new_modal)

# Write back
with open('index.html', 'w', encoding='utf-8') as f:
    f.write(content)

print("✅ Patch applied successfully!")
print("  - Updated Reembolsos section to match app styling")
print("  - Converted Bootstrap modal to vanilla JS modal")
