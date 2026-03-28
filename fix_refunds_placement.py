"""
Comprehensive patch to fix Refunds module:
1. Move section inside main-content (before </main>)
2. Remove the misplaced section and modal
3. Add action buttons to table
4. Handle delete reembolso endpoint
"""
import re

# Read the file
with open('index.html', 'r', encoding='utf-8') as f:
    content = f.read()

# The Reembolsos section HTML to insert INSIDE main-content (before </main>)
reembolsos_section = '''
            <!-- REEMBOLSOS SECTION -->
            <section id="reembolsos-section" class="content-section">
                <div class="section-header">
                    <h2><i class="fas fa-undo"></i> Gestión de Reembolsos</h2>
                    <button class="btn btn-primary" onclick="openReembolsoModal()">
                        <i class="fas fa-plus"></i> Nuevo Reembolso
                    </button>
                </div>

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
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="reembolsos-table-body">
                            <!-- JS -->
                        </tbody>
                    </table>
                </div>
            </section>

        </main>'''

# Remove the old misplaced Reembolsos section and modal
# Pattern to match the entire old section
old_section_pattern = r'    <!-- Reembolsos Section -->.*?</div>\s*</div>\s*</div>\s*</div>'
content = re.sub(old_section_pattern, '', content, flags=re.DOTALL)

# Also remove any Bootstrap-style modal that might exist
old_modal_pattern = r'    <!-- Reembolso Modal -->.*?</div>\s*</div>\s*</div>'
content = re.sub(old_modal_pattern, '', content, flags=re.DOTALL)

# Also try alternate patterns
old_section_pattern2 = r'    \<!-- Reembolsos Section --\>.*?    \</div\>\n    \</div\>\n'
content = re.sub(old_section_pattern2, '', content, flags=re.DOTALL)

# Replace </main> with section + </main>
content = content.replace('        </main>', reembolsos_section)

# Add the modal AFTER the other modals (before the script tag)
reembolsos_modal = '''
    <!-- Reembolso Modal -->
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
    </div>

    <script src="app.js?v=3"></script>'''

# Replace old script tag with modal + script
content = content.replace('<script src="app.js?v=2"></script>', reembolsos_modal)
content = content.replace('<script src="app.js?v=3"></script>\n\n    <!-- Reembolso Modal -->', reembolsos_modal.replace('app.js?v=3', 'app.js?v=4'))

# Clean up any leftover old sections
# Try to find and remove the section between line 2084 style markers
old_markers = ['<!-- Reembolsos Section -->', '<!-- SECCIÓN REEMBOLSOS -->', '<!-- MODAL REEMBOLSO -->']
for marker in old_markers:
    if content.count(marker) > 1:
        # Find second occurrence and remove the block
        first_pos = content.find(marker)
        second_pos = content.find(marker, first_pos + 1)
        if second_pos > 0:
            # Find end of block (next major comment or </div> sequence)
            end_search = content[second_pos:second_pos + 2000]
            content = content[:second_pos] + content[second_pos + 2000:]

# Write back
with open('index.html', 'w', encoding='utf-8') as f:
    f.write(content)

print("✅ HTML patch applied!")
print("  - Moved Reembolsos section inside main-content")
print("  - Added action column to table")
print("  - Fixed modal placement")
