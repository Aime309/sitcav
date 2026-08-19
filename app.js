// =====================================================
// CONFIGURATION
// =====================================================
// ONLINE: el frontend vive en tu web gratuita (GitHub Pages / aimee.42web.io)
// y el API en PythonAnywhere. REEMPLAZA Laikimist por tu usuario real de
// PythonAnywhere (sin / final):
const API_BASE_URL = 'https://Laikimist.pythonanywhere.com';
let currentUser = null;
let currentCarouselPosition = 0;
let productsData = [];

// =====================================================
// SESIÓN ONLINE: token Bearer en TODAS las peticiones
// El API exige token (salvo login/registro/tienda pública).
// El override de fetch inyecta el token y expulsa al usuario si el
// token expiró (401), salvo en los flujos de login/recovery.
// =====================================================
const SESSION_TOKEN_KEY = 'sgadmin_token';
const __origFetch = window.fetch;

window.fetch = function (url, opts) {
    opts = opts || {};
    opts.headers = opts.headers || {};
    const token = localStorage.getItem(SESSION_TOKEN_KEY);
    if (token) opts.headers['Authorization'] = 'Bearer ' + token;
    return __origFetch(url, opts).then(function (res) {
        if (res.status === 401 && token && !String(url).includes('/login') && !String(url).includes('/recovery')) {
            localStorage.removeItem(SESSION_TOKEN_KEY);
            localStorage.removeItem('currentUser');
            showWelcome();
        }
        return res;
    });
};

function downloadPdf(url) {
    fetch(url).then(function (res) {
        if (!res.ok) { alert('No se pudo generar el PDF. Tu sesión pudo expirar.'); return; }
        return res.blob();
    }).then(function (blob) {
        if (!blob) return;
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        link.download = url.split('/').pop().split('?')[0] + '.pdf';
        document.body.appendChild(link);
        link.click();
        link.remove();
        setTimeout(function () { URL.revokeObjectURL(link.href); }, 4000);
    }).catch(function () {
        alert('No se pudo generar el PDF.');
    });
}

// =====================================================
// THEME (claro/oscuro) — persistido en localStorage
// =====================================================
function applyTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    const icon = document.getElementById('theme-icon');
    if (icon) icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
    localStorage.setItem('sgadmin_theme', theme);
}

function toggleTheme() {
    const current = document.documentElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
    applyTheme(current);
}

applyTheme(localStorage.getItem('sgadmin_theme') || 'light');

// Placeholder SVG para imágenes rotas (data URI)
const PLACEHOLDER_IMAGE = 'data:image/svg+xml,' + encodeURIComponent(`
<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 200 200">
  <rect width="200" height="200" fill="#e5e7eb"/>
  <text x="100" y="100" font-family="Arial" font-size="14" fill="#9ca3af" text-anchor="middle" dominant-baseline="middle">Sin imagen</text>
  <path d="M80 70 L120 70 L120 95 L115 90 L105 100 L95 90 L85 100 L80 95 Z" fill="#9ca3af"/>
  <circle cx="92" cy="80" r="5" fill="#9ca3af"/>
</svg>
`);

// Función global para manejar errores de carga de imágenes
function handleImageError(img) {
    img.onerror = null; // Prevenir loops infinitos
    img.src = PLACEHOLDER_IMAGE;
    img.style.display = 'block'; // Asegurar que sea visible
}

// =====================================================
// SITCAV UI INFRASTRUCTURE (toasts, confirm modal, overrides)
// =====================================================
function titleCaseInput(el) {
    const words = el.value.trim().toLowerCase().split(/\s+/).filter(Boolean);
    el.value = words.map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
}

const nativeAlert = window.alert.bind(window);

function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;
    const toast = document.createElement('div');
    toast.className = `sitcav-toast ${type}`;
    const icons = { success: 'fa-check-circle', error: 'fa-exclamation-circle', warning: 'fa-exclamation-triangle', info: 'fa-info-circle' };
    toast.innerHTML = `<i class="fas ${icons[type] || icons.info}"></i><span>${message}</span><button type="button" class="toast-close" onclick="this.parentElement.remove()"><i class="fas fa-times"></i></button>`;
    container.appendChild(toast);
    setTimeout(() => { toast.classList.add('out'); setTimeout(() => toast.remove(), 400); }, 4200);
}

window.alert = function (message) {
    const text = String(message || '');
    const lower = text.toLowerCase();
    let type = 'info';
    if (/(error|incorrecta|inv[aá]lid|no se pudo|fallo|falla|debe |requerido|no puede|ocupada)/.test(lower)) type = 'error';
    else if (/(exitoso|correctamente|registrado|eliminado|actualizado|aprobado|creado)/.test(lower)) type = 'success';
    else if (/(aviso|atención|precaución|cuidado)/.test(lower)) type = 'warning';
    showToast(text, type);
};

let confirmResolver = null;

function confirmAsync(message) {
    return new Promise((resolve) => {
        const root = document.getElementById('confirm-overlay-root');
        if (!root) { resolve(true); return; }
        confirmResolver = resolve;
        root.innerHTML = `
            <div class="confirm-overlay" id="confirm-overlay">
                <div class="confirm-box">
                    <div class="confirm-icon"><i class="fas fa-question-circle"></i></div>
                    <div class="confirm-message">${message}</div>
                    <div class="confirm-actions">
                        <button type="button" class="btn btn-outline" id="confirm-no">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="confirm-yes">Sí, continuar</button>
                    </div>
                </div>
            </div>`;
        const overlay = document.getElementById('confirm-overlay');
        const close = (result) => {
            if (overlay) overlay.remove();
            root.innerHTML = '';
            confirmResolver = null;
            resolve(result);
        };
        document.getElementById('confirm-yes').onclick = () => close(true);
        document.getElementById('confirm-no').onclick = () => close(false);
        overlay.onclick = (e) => { if (e.target === overlay) close(false); };
        document.addEventListener('keydown', function escKey(e) {
            if (e.key === 'Escape' && overlay && overlay.parentElement) { close(false); document.removeEventListener('keydown', escKey); }
        });
    });
}

window.confirm = function (message) {
    return confirmAsync(String(message || ''));
};

// =====================================================
// PASSWORD POLICY CHECKER (vista) — ids pw-len/pw-may/pw-min/pw-esp
// =====================================================
function checkPasswordPolicy(pw, prefix = 'pw') {
    const results = {
        len: pw.length >= 8,
        may: /[A-Z]/.test(pw),
        min: /[a-z]/.test(pw),
        esp: /[^A-Za-z0-9]/.test(pw)
    };
    ['len', 'may', 'min', 'esp'].forEach(key => {
        const el = document.getElementById(`${prefix}-${key}`);
        if (!el) return;
        el.className = results[key] ? 'ok' : 'bad';
        el.querySelector('i').className = results[key] ? 'fas fa-check-circle' : 'fas fa-circle';
    });
    return results.len && results.may && results.min && results.esp;
}

// =====================================================
// REGISTRO: pestañas empleado / cliente
// =====================================================
function setRegTipo(tipo) {
    const empTab = document.getElementById('reg-tab-empleado');
    const cliTab = document.getElementById('reg-tab-cliente');
    if (tipo === 'cliente') {
        empTab.classList.remove('active');
        cliTab.classList.add('active');
        document.getElementById('register-empleado-block').style.display = 'none';
        document.getElementById('register-telefono-group').style.display = 'block';
        ['register-pregunta-1', 'register-pregunta-2', 'register-pregunta-3',
         'register-respuesta-1', 'register-respuesta-2', 'register-respuesta-3'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.removeAttribute('required');
        });
    } else {
        empTab.classList.add('active');
        cliTab.classList.remove('active');
        document.getElementById('register-empleado-block').style.display = 'block';
        document.getElementById('register-telefono-group').style.display = 'none';
        ['register-pregunta-1', 'register-pregunta-2', 'register-pregunta-3',
         'register-respuesta-1', 'register-respuesta-2', 'register-respuesta-3'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.setAttribute('required', '');
        });
    }
    document.getElementById('register-error').style.display = 'none';
}

// =====================================================
// TÉRMINOS Y CONDICIONES
// =====================================================
function openTermsModal(event) {
    if (event) event.preventDefault();
    document.getElementById('terms-modal').classList.add('active');
}

function closeTermsModal() {
    document.getElementById('terms-modal').classList.remove('active');
}

// =======================================================================
// AUTHENTICATION & NAVIGATION
// =====================================================
function showWelcome() {
    document.getElementById('welcome-screen').classList.remove('hidden');
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('register-form').classList.add('hidden');
    document.getElementById('app-container').classList.remove('active');
    const launcher = document.getElementById('chat-soporte-launcher');
    if (launcher) launcher.style.display = 'none';
}

function showLogin() {
    document.getElementById('welcome-screen').classList.add('hidden');
    document.getElementById('login-form').classList.remove('hidden');
    document.getElementById('register-form').classList.add('hidden');
}

function showRegister() {
    document.getElementById('welcome-screen').classList.add('hidden');
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('register-form').classList.remove('hidden');
}

async function handleLogin(event) {
    event.preventDefault();

    const cedula = document.getElementById('login-cedula').value;
    const password = document.getElementById('login-password').value;
    const errorDiv = document.getElementById('login-error');

    try {
        const response = await fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                usuario: cedula,
                contrasena: password
            })
        });

        const data = await response.json();

        console.log('=== DEBUG handleLogin ===');
        console.log('Login response data:', JSON.stringify(data));
        console.log('data.cedula:', data.cedula);
        console.log('data.foto_url:', data.foto_url);

        if (data.success) {
            const nombreLimpio = (data.nombre || '').replace(/\s*\([^)]*\)\s*$/, '');
            currentUser = {
                id: data.usuario_id,
                nombre: nombreLimpio,
                apellidos: data.apellidos || '',
                rol: data.rol,
                cedula: data.cedula,
                foto_url: data.foto_url
            };
            console.log('currentUser set to:', JSON.stringify(currentUser));
            localStorage.setItem('currentUser', JSON.stringify(currentUser));
            if (data.token) localStorage.setItem(SESSION_TOKEN_KEY, data.token);
            loadDashboard();
        } else {
            errorDiv.textContent = data.message;
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'Error de conexión con el servidor';
        errorDiv.style.display = 'block';
    }
}

async function handleRegister(event) {
    event.preventDefault();

    const tipo = document.getElementById('reg-tab-cliente').classList.contains('active') ? 'cliente' : 'empleado';
    const nombre = document.getElementById('register-nombre').value;
    const apellidos = document.getElementById('register-apellidos').value;
    const cedula = document.getElementById('register-cedula').value;
    const telefono = document.getElementById('register-telefono').value;
    const password = document.getElementById('register-password').value;
    const passwordConfirm = document.getElementById('register-password-confirm').value;
    const terminos = document.getElementById('register-terminos').checked;

    const errorDiv = document.getElementById('register-error');
    const successDiv = document.getElementById('register-success');

    errorDiv.style.display = 'none';
    successDiv.style.display = 'none';

    if (password !== passwordConfirm) {
        errorDiv.textContent = 'Las contraseñas no coinciden';
        errorDiv.style.display = 'block';
        return;
    }

    if (!checkPasswordPolicy(password)) {
        errorDiv.textContent = 'La contraseña no cumple la política de seguridad';
        errorDiv.style.display = 'block';
        return;
    }

    if (!terminos) {
        errorDiv.textContent = 'Debe aceptar los Términos y Condiciones';
        errorDiv.style.display = 'block';
        return;
    }

    const payload = {
        nombre,
        apellidos,
        cedula,
        contrasena: password,
        terminos_aceptados: terminos,
        tipo
    };
    if (tipo === 'cliente') {
        payload.telefono = telefono || null;
    } else {
        payload.pregunta_1 = document.getElementById('register-pregunta-1').value;
        payload.respuesta_1 = document.getElementById('register-respuesta-1').value;
        payload.pregunta_2 = document.getElementById('register-pregunta-2').value;
        payload.respuesta_2 = document.getElementById('register-respuesta-2').value;
        payload.pregunta_3 = document.getElementById('register-pregunta-3').value;
        payload.respuesta_3 = document.getElementById('register-respuesta-3').value;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (data.success) {
            if (tipo === 'cliente') {
                successDiv.textContent = '¡Cuenta creada exitosamente! Ya puedes iniciar sesión.';
            } else {
                successDiv.textContent = '¡Usuario registrado exitosamente! Tu cuenta será activada tras la validación del Administrador.';
            }
            showToast(data.message || 'Usuario Registrado Exitosamente', 'success');
            successDiv.style.display = 'block';
            document.getElementById('register-form').querySelector('form').reset();
            setRegTipo('empleado');
            document.getElementById('register-password').dispatchEvent(new Event('input'));
            setTimeout(() => {
                showLogin();
                document.getElementById('login-cedula').value = cedula;
            }, 2500);
        } else {
            errorDiv.textContent = data.message;
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'Error de conexión con el servidor';
        errorDiv.style.display = 'block';
    }
}

function loginAsAnonymous() {
    currentUser = {
        id: null,
        nombre: 'Invitado',
        rol: 'Anónimo'
    };
    localStorage.setItem('currentUser', JSON.stringify(currentUser));
    loadDashboard();
}

function handleLogout() {
    currentUser = null;
    localStorage.removeItem('currentUser');
    localStorage.removeItem(SESSION_TOKEN_KEY);
    showWelcome();
}

function loadDashboard() {
    document.getElementById('welcome-screen').classList.add('hidden');
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('register-form').classList.add('hidden');
    document.getElementById('app-container').classList.add('active');

    console.log('=== DEBUG loadDashboard ===');
    console.log('currentUser:', JSON.stringify(currentUser));
    console.log('currentUser.foto_url:', currentUser?.foto_url);

    // Set user info
    document.getElementById('user-name').textContent = currentUser.nombre;
    document.getElementById('user-role').textContent = currentUser.rol;

    // Set avatar with photo or initial
    if (currentUser.foto_url) {
        const fullUrl = currentUser.foto_url.startsWith('http') ? currentUser.foto_url : `${API_BASE_URL}${currentUser.foto_url}`;
        console.log('Setting avatar with photo URL:', fullUrl);
        document.getElementById('user-avatar').innerHTML = `<img src="${fullUrl}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;" onerror="this.parentElement.textContent='${currentUser.nombre.charAt(0).toUpperCase()}'">`;
    } else {
        console.log('No foto_url, using initial');
        document.getElementById('user-avatar').textContent = currentUser.nombre.charAt(0).toUpperCase();
    }

    // Show/hide navigation based on role
    setupRolePermissions();

    // Burbuja de soporte del personal: SOLO con sesión iniciada y rol de personal
    const esPersonalRol = ['Administrador', 'Encargado', 'Vendedor'].indexOf(currentUser.rol) !== -1;
    const launcher = document.getElementById('chat-soporte-launcher');
    if (launcher) launcher.style.display = esPersonalRol ? 'flex' : 'none';

    // Load dashboard data
    loadDashboardStats();
    loadProductCarousel();
    loadNotificaciones();
}

function setupRolePermissions() {
    const rol = currentUser.rol;
    console.log('Current role:', rol);

    const allNavs = [
        'nav-empleados', 'nav-proveedores', 'nav-compras', 'nav-backup',
        'nav-productos', 'nav-clientes', 'nav-ventas', 'nav-consultas',
        'nav-apartados', 'nav-inventario', 'nav-cotizacion', 'nav-credenciales',
        'nav-reembolsos', 'nav-estadisticas', 'nav-sucursales', 'nav-buzon', 'nav-soporte'
    ];
    const allCards = [
        'dash-card-empleados', 'dash-card-proveedores', 'dash-card-compras',
        'dash-card-productos', 'dash-card-clientes', 'dash-card-ventas', 'dash-card-consultas',
        'dash-card-apartados', 'dash-card-inventario', 'dash-card-cotizacion', 'dash-card-credenciales',
        'dash-card-reembolsos', 'dash-card-estadisticas'
    ];

    // ADMINISTRADOR: ve todo
    if (rol === 'Administrador') {
        allNavs.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'block';
        });
        allCards.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'block';
        });
        document.getElementById('btn-add-product')?.removeAttribute('disabled');
        return;
    }

    // ENCARGADO: todo excepto gestión de empleados y credenciales
    if (rol === 'Encargado') {
        allNavs.forEach(id => {
            const el = document.getElementById(id);
            if (el && id !== 'nav-empleados' && id !== 'nav-credenciales') el.style.display = 'block';
        });
        document.getElementById('nav-empleados')?.style.setProperty('display', 'none');
        document.getElementById('nav-credenciales')?.style.setProperty('display', 'none');
        allCards.forEach(id => {
            const el = document.getElementById(id);
            if (el && id !== 'dash-card-empleados' && id !== 'dash-card-credenciales') el.style.display = 'block';
        });
        document.getElementById('dash-card-empleados')?.style.setProperty('display', 'none');
        document.getElementById('dash-card-credenciales')?.style.setProperty('display', 'none');
        document.getElementById('btn-add-product')?.removeAttribute('disabled');
        return;
    }

    // VENDEDOR: operativa sin admin
    if (rol === 'Vendedor') {
        const hiddenNavs = ['nav-empleados', 'nav-proveedores', 'nav-compras', 'nav-backup',
            'nav-consultas', 'nav-reembolsos', 'nav-credenciales', 'nav-cotizacion', 'nav-sucursales', 'nav-estadisticas'];
        hiddenNavs.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
        ['nav-productos', 'nav-clientes', 'nav-ventas', 'nav-apartados', 'nav-inventario', 'nav-buzon', 'nav-soporte'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'block';
        });
        const hiddenCards = ['dash-card-empleados', 'dash-card-proveedores', 'dash-card-compras', 'dash-card-consultas',
            'dash-card-reembolsos', 'dash-card-credenciales', 'dash-card-cotizacion', 'dash-card-estadisticas'];
        hiddenCards.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'none';
        });
        ['dash-card-productos', 'dash-card-clientes', 'dash-card-ventas', 'dash-card-apartados', 'dash-card-inventario'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.style.display = 'block';
        });
        document.getElementById('btn-add-product')?.removeAttribute('disabled');
        return;
    }

    // CLIENTE ONLINE: vista limitada (productos + sus ventas/consultas, solo lectura)
    const clienteNavs = ['nav-productos', 'nav-ventas', 'nav-consultas'];
    allNavs.forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        if (clienteNavs.includes(id)) el.style.display = 'block';
        else el.style.display = 'none';
    });
    ['dash-card-productos', 'dash-card-ventas', 'dash-card-consultas'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.style.display = 'block';
    });
    allCards.forEach(id => {
        const el = document.getElementById(id);
        if (el && !['dash-card-productos', 'dash-card-ventas', 'dash-card-consultas'].includes(id)) el.style.display = 'none';
    });
    document.getElementById('btn-add-product')?.setAttribute('disabled', 'true');
}

function canManage(rol) {
    return rol === 'Administrador' || rol === 'Encargado' || rol === 'Vendedor';
}

// =====================================================
// DASHBOARD
// =====================================================
async function loadDashboardStats() {
    try {
        // Load products count
        const productos = await fetch(`${API_BASE_URL}/api/productos`).then(r => r.json());
        document.getElementById('stat-productos').textContent = productos.length;

        // Load clients count
        const clientes = await fetch(`${API_BASE_URL}/api/clientes`).then(r => r.json());
        document.getElementById('stat-clientes').textContent = clientes.length;

        // Load sales count
        const ventas = await fetch(`${API_BASE_URL}/api/ventas`).then(r => r.json());
        document.getElementById('stat-ventas').textContent = ventas.length;

        // Load low stock count
        const stockBajo = await fetch(`${API_BASE_URL}/api/productos/stock-bajo`).then(r => r.json());
        document.getElementById('stat-stock-bajo').textContent = stockBajo.length;

    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function loadProductCarousel() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/productos`);
        productsData = await response.json();
        renderCarousel();
    } catch (error) {
        console.error('Error loading products:', error);
    }
}

function renderCarousel() {
    const track = document.getElementById('carousel-track');
    if (!track) return;
    track.innerHTML = '';

    productsData.forEach(product => {
        const card = document.createElement('div');
        card.className = 'product-card';
        const imgUrl = product.imagen_url ?
            (product.imagen_url.startsWith('http') ? product.imagen_url : `${API_BASE_URL}${product.imagen_url}`) :
            '';
        card.innerHTML = `
            <img src="${imgUrl}"
                 alt="${product.nombre}"
                 class="product-image"
                 onerror="handleImageError(this)">
            <h4>${product.nombre}</h4>
            <div class="product-price">$${parseFloat(product.precio_unitario_actual_dolares).toFixed(2)}</div>
            <div class="product-stock">Stock: ${product.cantidad_disponible} unidades</div>
        `;
        track.appendChild(card);
    });
}

// =====================================================
// SECTION NAVIGATION
// =====================================================
function showSection(sectionName) {
    // Update navigation - safely handle event
    document.querySelectorAll('.nav-link').forEach(link => link.classList.remove('active'));
    if (window.event && window.event.target) {
        const navLink = window.event.target.closest('.nav-link');
        if (navLink) navLink.classList.add('active');
    }

    // Update sections
    document.querySelectorAll('.content-section').forEach(section => section.classList.remove('active'));
    const targetSection = document.getElementById(`${sectionName}-section`);
    if (targetSection) {
        targetSection.classList.add('active');
    } else {
        console.warn(`Section ${sectionName}-section not found`);
    }

    // Update title
    const titles = {
        'dashboard': 'Dashboard',
        'productos': 'Productos',
        'clientes': 'Clientes',
        'proveedores': 'Proveedores',
        'ventas': 'Ventas',
        'empleados': 'Empleados',
        'backup': 'Backup',
        'compras': 'Compras',
        'apartados': 'Apartados',
        'inventario': 'Inventario',
        'cotizacion': 'Cotización',
        'credenciales': 'Credenciales',
        'reembolsos': 'Reembolsos',
        'estadisticas': 'Estadísticas',
        'sucursales': 'Sucursales y Transferencias',
        'buzon': 'Buzón de Comentarios',
        'soporte': 'Módulo de Soporte'
    };
    const titleElement = document.getElementById('current-section-title');
    if (titleElement) {
        titleElement.textContent = titles[sectionName] || sectionName;
    }

    // Load data for section
    switch (sectionName) {
        case 'productos':
            loadProducts();
            break;
        case 'clientes':
            loadClients();
            break;
        case 'ventas':
            loadVentas();
            break;
        case 'empleados':
            loadEmpleados();
            break;
        case 'proveedores':
            loadProveedores();
            break;
        case 'compras':
            loadCompras();
            break;
        case 'apartados':
            loadApartados();
            break;
        case 'inventario':
            loadInventario();
            break;
        case 'cotizacion':
            loadCotizacion();
            break;
        case 'credenciales':
            loadCredenciales();
            break;
        case 'reembolsos':
            loadReembolsos();
            break;
        case 'consultas':
            loadConsultas();
            loadFiltrosConsultas();
            break;
        case 'estadisticas':
            loadEstadisticas();
            break;
        case 'sucursales':
            loadSucursales();
            break;
        case 'buzon':
            loadBuzonPreguntas();
            loadBuzonValoraciones();
            break;
        case 'soporte':
            loadSoporteChats();
            loadSoporteTickets();
            break;
        default:
            console.log(`Section ${sectionName} loaded`);
    }
}

// Función de búsqueda en tablas
function searchTable(tableId, inputId) {
    const input = document.getElementById(inputId);
    const filter = input.value.toLowerCase();
    const table = document.getElementById(tableId);
    const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

    for (let i = 0; i < rows.length; i++) {
        const cells = rows[i].getElementsByTagName('td');
        let found = false;

        for (let j = 0; j < cells.length; j++) {
            const cellText = cells[j].textContent || cells[j].innerText;
            if (cellText.toLowerCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }

        rows[i].style.display = found ? '' : 'none';
    }
}

function moveCarousel(direction) {
    const track = document.getElementById('carousel-track');
    const cardWidth = 270; // 250px card + 20px gap
    const visibleCards = Math.floor(track.parentElement.offsetWidth / cardWidth);
    const maxPosition = Math.max(0, productsData.length - visibleCards);

    currentCarouselPosition += direction;
    currentCarouselPosition = Math.max(0, Math.min(currentCarouselPosition, maxPosition));

    track.style.transform = `translateX(-${currentCarouselPosition * cardWidth}px)`;
}

// =====================================================
// PRODUCTS MODULE
// =====================================================
async function loadProducts() {
    const tbody = document.getElementById('productos-table-body');
    tbody.innerHTML = '<tr><td colspan="8" class="loading active"><div class="spinner"></div>Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/productos`);
        const products = await response.json();

        tbody.innerHTML = '';

        if (products.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px;">No hay productos registrados</td></tr>';
            return;
        }

        products.forEach(product => {
            const tr = document.createElement('tr');
            const stockBadge = product.cantidad_disponible < 10 ?
                `<span class="badge danger">Bajo</span>` :
                `<span class="badge success">OK</span>`;

            const imageUrl = product.imagen_url ?
                (product.imagen_url.startsWith('http') ? product.imagen_url : `${API_BASE_URL}${product.imagen_url}`) :
                '';

            tr.innerHTML = `
                <td>
                    <img src="${imageUrl}"
                         style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; background: #e5e7eb;"
                         onerror="handleImageError(this)">
                </td>
                <td>${product.codigo}</td>
                <td>${product.nombre}</td>
                <td>${product.categoria_nombre || 'N/A'}</td>
                <td>$${parseFloat(product.precio_unitario_actual_dolares).toFixed(2)}</td>
                <td>${product.cantidad_disponible}</td>
                <td>${stockBadge}</td>
                <td>
                    <div class="action-btns">
                        ${canManage(currentUser.rol) ? `
                            <button class="action-btn edit" onclick="editProduct(${product.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteProduct(${product.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 40px; color: red;">Error al cargar productos</td></tr>';
    }
}

async function openProductModal(productId = null) {
    const modal = document.getElementById('product-modal');
    modal.classList.add('active');

    // Load categories
    const categories = await fetch(`${API_BASE_URL}/api/categorias`).then(r => r.json());
    const select = document.getElementById('product-categoria');
    select.innerHTML = '<option value="">Seleccione...</option>';
    categories.forEach(cat => {
        select.innerHTML += `<option value="${cat.id}">${cat.nombre}</option>`;
    });
    // Agregar opción para nueva categoría
    select.innerHTML += '<option value="nueva">➕ Nueva categoría...</option>';

    // Resetear el contenedor de nueva categoría
    const nuevaCatContainer = document.getElementById('nueva-categoria-container');
    if (nuevaCatContainer) {
        nuevaCatContainer.style.display = 'none';
        document.getElementById('nueva-categoria-nombre').value = '';
    }

    if (productId) {
        // Edit mode
        document.getElementById('product-modal-title').textContent = 'Editar Producto';
        const response = await fetch(`${API_BASE_URL}/api/productos`);
        const products = await response.json();
        const product = products.find(p => p.id === productId);

        if (product) {
            document.getElementById('product-id').value = product.id;
            document.getElementById('product-nombre').value = product.nombre;
            document.getElementById('product-codigo').value = product.codigo;
            document.getElementById('product-imei').value = product.imei || '';
            document.getElementById('product-descripcion').value = product.descripcion || '';
            document.getElementById('product-categoria').value = product.id_categoria;
            document.getElementById('product-precio').value = product.precio_unitario_actual_dolares;
            document.getElementById('product-stock').value = product.cantidad_disponible;
            document.getElementById('product-imagen').value = product.imagen_url || '';
            document.getElementById('product-imagen-file').value = ''; // Clear file input

            // Show image preview
            updateProductImagePreview(product.imagen_url);
        }
    } else {
        // Add mode
        document.getElementById('product-modal-title').textContent = 'Agregar Producto';
        document.querySelector('#product-modal form').reset();
        document.getElementById('product-id').value = '';

        // Clear image preview
        clearProductImage();
    }
}

function closeProductModal() {
    document.getElementById('product-modal').classList.remove('active');
}

// Product image preview functions
function updateProductImagePreview(imageUrl) {
    const img = document.getElementById('product-image-preview-img');
    const placeholder = document.getElementById('product-image-placeholder');

    if (imageUrl) {
        const fullUrl = imageUrl.startsWith('http') ? imageUrl : `${API_BASE_URL}${imageUrl}`;
        img.src = fullUrl;
        img.style.display = 'block';
        placeholder.style.display = 'none';
    } else {
        img.style.display = 'none';
        placeholder.style.display = 'block';
    }
}

function previewProductImageUrl() {
    const url = document.getElementById('product-imagen').value;
    if (url) {
        document.getElementById('product-imagen-file').value = ''; // Clear file input
        updateProductImagePreview(url);
    }
}

function previewProductImageFile() {
    const fileInput = document.getElementById('product-imagen-file');
    const file = fileInput.files[0];

    if (file) {
        document.getElementById('product-imagen').value = ''; // Clear URL input

        const reader = new FileReader();
        reader.onload = function (e) {
            const img = document.getElementById('product-image-preview-img');
            const placeholder = document.getElementById('product-image-placeholder');
            img.src = e.target.result;
            img.style.display = 'block';
            placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

function clearProductImage() {
    document.getElementById('product-imagen').value = '';
    document.getElementById('product-imagen-file').value = '';

    const img = document.getElementById('product-image-preview-img');
    const placeholder = document.getElementById('product-image-placeholder');
    if (img) img.style.display = 'none';
    if (placeholder) placeholder.style.display = 'block';
}

async function saveProduct(event) {
    event.preventDefault();

    const productId = document.getElementById('product-id').value;
    const formData = new FormData();

    formData.append('nombre', document.getElementById('product-nombre').value);
    formData.append('codigo', document.getElementById('product-codigo').value);
    formData.append('imei', document.getElementById('product-imei').value);
    formData.append('descripcion', document.getElementById('product-descripcion').value);
    formData.append('id_categoria', document.getElementById('product-categoria').value);
    formData.append('precio_unitario_actual_dolares', document.getElementById('product-precio').value);
    formData.append('cantidad_disponible', document.getElementById('product-stock').value);
    formData.append('imagen_url', document.getElementById('product-imagen').value);

    const fileInput = document.getElementById('product-imagen-file');
    if (fileInput.files.length > 0) {
        formData.append('imagen_file', fileInput.files[0]);
    }

    try {
        const url = productId ?
            `${API_BASE_URL}/api/productos/${productId}` :
            `${API_BASE_URL}/api/productos`;
        const method = productId ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            body: formData // No Content-Type header needed, browser sets it with boundary
        });

        if (response.ok) {
            closeProductModal();
            loadProducts();
            loadProductCarousel();
            alert('Producto guardado exitosamente');
        } else {
            const data = await response.json();
            alert('Error al guardar producto: ' + (data.message || 'Error desconocido'));
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function editProduct(id) {
    openProductModal(id);
}

async function deleteProduct(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar este producto?'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/productos/${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            loadProducts();
            loadProductCarousel();
            alert('Producto eliminado');
        } else {
            alert('Error al eliminar producto');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

// =====================================================
// CLIENTS MODULE
// =====================================================
async function loadClients() {
    const tbody = document.getElementById('clientes-table-body');
    tbody.innerHTML = '<tr><td colspan="6" class="loading active"><div class="spinner"></div>Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/clientes?cedula=${encodeURIComponent(currentUser ? currentUser.cedula : '')}`);
        const clients = await response.json();

        tbody.innerHTML = '';

        if (clients.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">No hay clientes registrados</td></tr>';
            return;
        }

        clients.forEach(client => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${client.id}</td>
                <td>${client.nombre} ${client.apellidos}</td>
                <td>${client.cedula}</td>
                <td>${client.telefono || 'N/A'}</td>
                <td>${client.tiene_cuenta ? '<span class="badge proceso">Sí (tienda)</span>' : '<span class="badge">No</span>'}</td>
                <td>
                    <div class="action-btns">
                        ${canManage(currentUser.rol) ? `
                            <button class="action-btn edit" onclick="editClient(${client.id})" title="Editar / cambiar contraseña">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteClient(${client.id})" title="Eliminar de la base de datos">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: red;">Error al cargar clientes</td></tr>';
    }
}

function openClientModal() {
    document.getElementById('client-modal').classList.add('active');
    document.querySelector('#client-modal form').reset();
    document.getElementById('client-id').value = '';  // Clear ID for new client
    document.getElementById('client-cuenta-info').value = '';
    document.getElementById('client-contrasena-hash').value = '';
    document.getElementById('client-cuenta-group').style.display = 'none';
    document.getElementById('client-hash-group').style.display = 'none';
    document.getElementById('client-nueva-pass-group').style.display = 'block';
    document.getElementById('client-nueva-pass-label').textContent = 'Contraseña de la tienda (opcional: crea su cuenta para iniciar sesión)';
}

function closeClientModal() {
    document.getElementById('client-modal').classList.remove('active');
    document.getElementById('client-id').value = '';  // Clear ID when closing
}

function toggleClientePassword() {
    const input = document.getElementById('client-contrasena');
    input.type = input.type === 'password' ? 'text' : 'password';
}

async function saveClient(event) {
    event.preventDefault();

    const clientId = document.getElementById('client-id').value;
    const clientData = {
        nombre: document.getElementById('client-nombre').value,
        apellidos: document.getElementById('client-apellidos').value,
        cedula: document.getElementById('client-cedula').value,
        telefono: document.getElementById('client-telefono').value,
        direccion: document.getElementById('client-direccion').value
    };
    const nuevaPass = document.getElementById('client-contrasena').value;
    if (nuevaPass) clientData.contrasena = nuevaPass;

    try {
        const url = clientId
            ? `${API_BASE_URL}/api/clientes/${clientId}?cedula=${encodeURIComponent(currentUser.cedula)}`
            : `${API_BASE_URL}/api/clientes`;
        const method = clientId ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(clientData)
        });

        if (response.ok) {
            closeClientModal();
            loadClients();
            alert(clientId ? 'Cliente actualizado exitosamente' : 'Cliente guardado exitosamente');
        } else {
            const error = await response.json();
            alert(error.message || 'Error al guardar cliente');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function deleteClient(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar este cliente? Se eliminará también su cuenta de la tienda (chats, tickets, preguntas y valoraciones).'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/clientes/${id}?cedula=${encodeURIComponent(currentUser.cedula)}`, {
            method: 'DELETE'
        });

        const data = await response.json().catch(() => ({}));
        if (response.ok) {
            loadClients();
            alert(data.message || 'Cliente eliminado');
        } else {
            alert(data.message || 'Error al eliminar cliente');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function editClient(id) {
    // Open client modal and populate with client data
    const modal = document.getElementById('client-modal');
    modal.classList.add('active');

    try {
        const response = await fetch(`${API_BASE_URL}/api/clientes?cedula=${encodeURIComponent(currentUser ? currentUser.cedula : '')}`);
        const clients = await response.json();
        const client = clients.find(c => c.id === id);

        if (client) {
            document.getElementById('client-id').value = client.id;  // Save ID for PUT
            document.getElementById('client-nombre').value = client.nombre;
            document.getElementById('client-apellidos').value = client.apellidos || '';
            document.getElementById('client-cedula').value = client.cedula;
            document.getElementById('client-telefono').value = client.telefono || '';
            document.getElementById('client-direccion').value = client.direccion || '';
            document.getElementById('client-contrasena').value = '';
            document.getElementById('client-cuenta-info').value = client.tiene_cuenta ? 'Sí — puede iniciar sesión en la tienda' : 'No tiene cuenta en la tienda';
            document.getElementById('client-contrasena-hash').value = client.contrasena_hash || '(sin cuenta)';
            document.getElementById('client-cuenta-group').style.display = 'block';
            document.getElementById('client-hash-group').style.display = 'block';
            document.getElementById('client-nueva-pass-group').style.display = 'block';
            document.getElementById('client-nueva-pass-label').textContent = 'Nueva contraseña (dejar vacío para no cambiarla)';
        }
    } catch (error) {
        alert('Error al cargar datos del cliente');
    }
}

// =====================================================
// VENTAS MODULE
// =====================================================
async function loadVentas() {
    const tbody = document.getElementById('ventas-table-body');
    tbody.innerHTML = '<tr><td colspan="6" class="loading active"><div class="spinner"></div>Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/ventas`);
        const ventas = await response.json();

        tbody.innerHTML = '';

        if (ventas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">No hay ventas registradas</td></tr>';
            return;
        }

        ventas.forEach(venta => {
            const tr = document.createElement('tr');
            const fecha = new Date(venta.fecha_creacion).toLocaleDateString('es-VE');
            const clienteNombre = venta.cliente ? `${venta.cliente.nombre} ${venta.cliente.apellidos || ''}`.trim() : 'Cliente General';

            // Calculate total from detalles
            let total = 0;
            if (venta.detalles && venta.detalles.length > 0) {
                venta.detalles.forEach(d => {
                    total += (parseFloat(d.precio_unitario_tipo_dolares) || 0) * (d.cantidad || 0);
                });
            }

            tr.innerHTML = `
                <td>${venta.id}</td>
                <td>${fecha}</td>
                <td>${clienteNombre}</td>
                <td>$${total.toFixed(2)}</td>
                <td><span class="badge success">Completada</span></td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn edit" onclick="verFactura(${venta.id})" title="Ver Factura">
                            <i class="fas fa-file-pdf"></i>
                        </button>
                        <button class="action-btn delete" onclick="deleteVenta(${venta.id})" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px; color: red;">Error al cargar ventas</td></tr>';
    }
}

async function verFactura(ventaId) {
    window.open(`${API_BASE_URL}/api/factura/${ventaId}`, '_blank');
}

// Variable to store products for venta modal
let ventaProductsData = [];

async function openVentaModal() {
    const modal = document.getElementById('venta-modal');
    modal.classList.add('active');

    // Reset form
    document.getElementById('venta-id').value = '';
    document.getElementById('venta-cliente').value = '';
    document.getElementById('venta-productos-body').innerHTML = '';
    document.getElementById('venta-total').textContent = '0.00';
    document.getElementById('venta-modal-title').textContent = 'Nueva Venta';

    // Load clients
    try {
        const clientesRes = await fetch(`${API_BASE_URL}/api/clientes`);
        const clientes = await clientesRes.json();
        const clienteSelect = document.getElementById('venta-cliente');
        clienteSelect.innerHTML = '<option value="">Seleccione un cliente...</option>';
        clientes.forEach(c => {
            clienteSelect.innerHTML += `<option value="${c.id}">${c.nombre} ${c.apellidos || ''} - ${c.cedula}</option>`;
        });
    } catch (error) {
        console.error('Error loading clients:', error);
    }

    // Load products
    try {
        const productosRes = await fetch(`${API_BASE_URL}/api/productos`);
        ventaProductsData = await productosRes.json();
    } catch (error) {
        console.error('Error loading products:', error);
    }

    // Add first row
    addVentaRow();
}

function closeVentaModal() {
    document.getElementById('venta-modal').classList.remove('active');
}

function addVentaRow() {
    const tbody = document.getElementById('venta-productos-body');
    const tr = document.createElement('tr');

    let productOptions = '<option value="">Seleccione...</option>';
    ventaProductsData.forEach(p => {
        if (p.cantidad_disponible > 0) {
            productOptions += `<option value="${p.id}" data-precio="${p.precio_unitario_actual_dolares}" data-stock="${p.cantidad_disponible}">${p.nombre} (Stock: ${p.cantidad_disponible})</option>`;
        }
    });

    tr.innerHTML = `
        <td>
            <select class="venta-producto-select" onchange="onVentaProductSelect(this)" required>
                ${productOptions}
            </select>
        </td>
        <td>
            <input type="number" class="venta-cantidad" min="1" value="1" onchange="calculateVentaTotal()" required>
        </td>
        <td>
            <input type="number" class="venta-precio" step="0.01" readonly style="background-color: #f5f5f5;">
        </td>
        <td>
            <button type="button" class="action-btn delete" onclick="removeVentaRow(this)">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);
}

function onVentaProductSelect(select) {
    const tr = select.closest('tr');
    const option = select.options[select.selectedIndex];
    const precio = option.dataset.precio || 0;
    const stock = parseInt(option.dataset.stock) || 1;

    tr.querySelector('.venta-precio').value = parseFloat(precio).toFixed(2);
    tr.querySelector('.venta-cantidad').max = stock;
    tr.querySelector('.venta-cantidad').value = 1;

    calculateVentaTotal();
}

function removeVentaRow(btn) {
    const tbody = document.getElementById('venta-productos-body');
    if (tbody.children.length > 1) {
        btn.closest('tr').remove();
        calculateVentaTotal();
    } else {
        alert('Debe haber al menos un producto');
    }
}

function calculateVentaTotal() {
    const rows = document.querySelectorAll('#venta-productos-body tr');
    let total = 0;

    rows.forEach(row => {
        const cantidad = parseFloat(row.querySelector('.venta-cantidad').value) || 0;
        const precio = parseFloat(row.querySelector('.venta-precio').value) || 0;
        total += cantidad * precio;
    });

    document.getElementById('venta-total').textContent = total.toFixed(2);
}

async function saveVenta(event) {
    event.preventDefault();

    const clienteId = document.getElementById('venta-cliente').value;
    const rows = document.querySelectorAll('#venta-productos-body tr');

    const productos = [];
    let valid = true;

    rows.forEach(row => {
        const productoId = row.querySelector('.venta-producto-select').value;
        const cantidad = parseInt(row.querySelector('.venta-cantidad').value);
        const precio = parseFloat(row.querySelector('.venta-precio').value);

        if (!productoId) {
            valid = false;
            return;
        }

        productos.push({
            id_producto: parseInt(productoId),
            cantidad: cantidad,
            precio_unitario: precio
        });
    });

    if (!valid || productos.length === 0) {
        alert('Por favor seleccione al menos un producto');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/ventas`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_cliente: parseInt(clienteId),
                id_vendedor: currentUser ? currentUser.id : null,
                detalles: productos
            })
        });

        const data = await response.json();

        if (response.ok) {
            alert('Venta registrada exitosamente');
            closeVentaModal();
            loadVentas();
            loadDashboardStats();
        } else {
            alert('Error: ' + (data.message || 'Error al registrar venta'));
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function deleteVenta(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar esta venta?'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/ventas/${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            loadVentas();
            loadDashboardStats();
            alert('Venta eliminada');
        } else {
            const data = await response.json();
            alert('Error: ' + (data.message || 'Error al eliminar venta'));
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

// =====================================================
// EMPLEADOS MODULE
// =====================================================
async function loadEmpleados() {
    const tbody = document.getElementById('empleados-table-body');
    tbody.innerHTML = '<tr><td colspan="5" class="loading active"><div class="spinner"></div>Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/empleados`);
        const empleados = await response.json();

        tbody.innerHTML = '';

        if (empleados.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px;">No hay empleados registrados</td></tr>';
            return;
        }

        empleados.forEach(emp => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${emp.id}</td>
                <td>${emp.nombre}</td>
                <td>${emp.cedula}</td>
                <td><span class="badge">${emp.rol || 'Empleado'}</span></td>
                <td>
                    <div class="action-btns">
                        ${currentUser.rol === 'Encargado' ? `
                            <button class="action-btn edit" onclick="editEmpleado(${emp.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteEmpleado(${emp.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px; color: red;">Error al cargar empleados</td></tr>';
    }
}

async function editEmpleado(id) {
    alert('Función de edición de empleado en desarrollo');
}

async function deleteEmpleado(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar este empleado?'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/empleados/${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            loadEmpleados();
            alert('Empleado eliminado');
        } else {
            alert('Error al eliminar empleado');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

// =====================================================
// PROVEEDORES MODULE
// =====================================================
async function loadProveedores() {
    const tbody = document.getElementById('proveedores-table-body');
    tbody.innerHTML = '<tr><td colspan="5" class="loading active"><div class="spinner"></div>Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/proveedores`);
        const proveedores = await response.json();

        tbody.innerHTML = '';

        if (proveedores.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px;">No hay proveedores registrados</td></tr>';
            return;
        }

        proveedores.forEach(prov => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${prov.id}</td>
                <td>${prov.nombre}</td>
                <td>${prov.rif || 'N/A'}</td>
                <td>${prov.telefono || 'N/A'}</td>
                <td>
                    <div class="action-btns">
                        ${canManage(currentUser.rol) ? `
                            <button class="action-btn edit" onclick="editProveedor(${prov.id})" title="Editar">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteProveedor(${prov.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px; color: red;">Error al cargar proveedores</td></tr>';
    }
}

function openProveedorModal(proveedorId = null) {
    const modal = document.getElementById('proveedor-modal');
    modal.classList.add('active');
    document.querySelector('#proveedor-modal form').reset();
    document.getElementById('proveedor-id').value = proveedorId || '';

    // Update modal title
    const title = document.getElementById('proveedor-modal-title');
    if (title) {
        title.textContent = proveedorId ? 'Editar Proveedor' : 'Agregar Proveedor';
    }
}

function closeProveedorModal() {
    document.getElementById('proveedor-modal').classList.remove('active');
}

async function saveProveedor(event) {
    event.preventDefault();

    const proveedorId = document.getElementById('proveedor-id').value;
    const provData = {
        nombre: document.getElementById('proveedor-nombre').value,
        rif: document.getElementById('proveedor-rif').value,
        telefono: document.getElementById('proveedor-telefono').value,
        direccion: document.getElementById('proveedor-direccion').value
    };

    try {
        let url = `${API_BASE_URL}/api/proveedores`;
        let method = 'POST';

        if (proveedorId) {
            url = `${API_BASE_URL}/api/proveedores/${proveedorId}`;
            method = 'PUT';
        }

        const response = await fetch(url, {
            method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(provData)
        });

        if (response.ok) {
            closeProveedorModal();
            loadProveedores();
            alert(proveedorId ? 'Proveedor actualizado exitosamente' : 'Proveedor guardado exitosamente');
        } else {
            const data = await response.json();
            alert(data.message || 'Error al guardar proveedor');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function editProveedor(id) {
    try {
        const response = await fetch(`${API_BASE_URL}/api/proveedores`);
        const proveedores = await response.json();
        const proveedor = proveedores.find(p => p.id === id);

        if (proveedor) {
            openProveedorModal(id);
            document.getElementById('proveedor-nombre').value = proveedor.nombre;
            document.getElementById('proveedor-rif').value = proveedor.rif || '';
            document.getElementById('proveedor-telefono').value = proveedor.telefono || '';
            document.getElementById('proveedor-direccion').value = proveedor.direccion || '';
        } else {
            alert('Proveedor no encontrado');
        }
    } catch (error) {
        alert('Error al cargar datos del proveedor');
    }
}

async function deleteProveedor(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar este proveedor?'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/proveedores/${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            loadProveedores();
            alert('Proveedor eliminado');
        } else {
            alert('Error al eliminar proveedor');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

// =====================================================
// BACKUP MODULE
// =====================================================
async function createBackup() {
    const statusDiv = document.getElementById('backup-status');
    if (statusDiv) {
        statusDiv.innerHTML = '<div class="spinner"></div> Creando respaldo...';
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/backup`, {
            method: 'POST'
        });

        if (response.ok) {
            const data = await response.json();
            alert('Respaldo creado exitosamente: ' + (data.filename || 'backup.db'));
            if (statusDiv) {
                statusDiv.innerHTML = '✅ Último respaldo: ' + new Date().toLocaleString();
            }
        } else {
            alert('Error al crear respaldo');
            if (statusDiv) {
                statusDiv.innerHTML = '❌ Error al crear respaldo';
            }
        }
    } catch (error) {
        alert('Error de conexión al crear respaldo');
        if (statusDiv) {
            statusDiv.innerHTML = '❌ Error de conexión';
        }
    }
    for (let i = 1; i < tr.length; i++) {
        let found = false;
        const td = tr[i].getElementsByTagName('td');

        for (let j = 0; j < td.length; j++) {
            if (td[j]) {
                const txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    found = true;
                    break;
                }
            }
        }

        tr[i].style.display = found ? '' : 'none';
    }
}

// =====================================================
// INITIALIZATION
// =====================================================
window.addEventListener('DOMContentLoaded', () => {
    // Check if user is already logged in
    const savedUser = localStorage.getItem('currentUser');
    if (savedUser) {
        currentUser = JSON.parse(savedUser);
        loadDashboard();
    }
});

// Password Toggle Function
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');

    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// =====================================================
// PASSWORD RECOVERY
// =====================================================
function showRecovery() {
    document.getElementById('welcome-screen').classList.add('hidden');
    document.getElementById('login-form').classList.add('hidden');
    document.getElementById('recovery-modal').classList.add('active');
    showRecoveryStep(1);
}

function closeRecoveryModal() {
    document.getElementById('recovery-modal').classList.remove('active');
    document.getElementById('welcome-screen').classList.remove('hidden');
    // Reset forms
    document.getElementById('recovery-cedula').value = '';
    document.getElementById('recovery-respuesta-1').value = '';
    document.getElementById('recovery-respuesta-2').value = '';
    document.getElementById('recovery-respuesta-3').value = '';
    document.getElementById('recovery-password').value = '';
    document.getElementById('recovery-confirm').value = '';
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
}

function showRecoveryStep(step) {
    document.getElementById('recovery-step-1').style.display = 'none';
    document.getElementById('recovery-step-2').style.display = 'none';
    document.getElementById('recovery-step-3').style.display = 'none';
    document.getElementById(`recovery-step-${step}`).style.display = 'block';
}

async function checkUserForRecovery() {
    const cedula = document.getElementById('recovery-cedula').value;
    const errorDiv = document.getElementById('recovery-error-1');

    if (!cedula) {
        errorDiv.textContent = 'Por favor ingresa tu cédula';
        errorDiv.style.display = 'block';
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/security-questions/${cedula}`);
        const data = await response.json();

        if (data.success) {
            // Populate questions
            document.getElementById('label-pregunta-1').textContent = formatQuestion(data.preguntas[0]);
            document.getElementById('label-pregunta-2').textContent = formatQuestion(data.preguntas[1]);
            document.getElementById('label-pregunta-3').textContent = formatQuestion(data.preguntas[2]);

            errorDiv.textContent = '';
            showRecoveryStep(2);
        } else {
            errorDiv.textContent = data.message;
        }
    } catch (error) {
        errorDiv.textContent = 'Error de conexión';
    }
}

function formatQuestion(key) {
    const questions = {
        'nombre_mascota': '¿Cuál es el nombre de tu primera mascota?',
        'ciudad_nacimiento': '¿En qué ciudad naciste?',
        'apellido_madre': '¿Cuál es el primer apellido de tu madre?',
        'escuela_primaria': '¿Cómo se llamaba tu escuela primaria?',
        'mejor_amigo': '¿Cuál es el nombre de tu mejor amigo de la infancia?',
        'pelicula_favorita': '¿Cuál es tu película favorita?',
        'comida_favorita': '¿Cuál es tu comida favorita?',
        'primer_trabajo': '¿Cuál fue tu primer trabajo?',
        'color_favorito': '¿Cuál es tu color favorito?'
    };
    return questions[key] || key;
}

async function verifyAnswers() {
    const cedula = document.getElementById('recovery-cedula').value;
    const r1 = document.getElementById('recovery-respuesta-1').value;
    const r2 = document.getElementById('recovery-respuesta-2').value;
    const r3 = document.getElementById('recovery-respuesta-3').value;
    const errorDiv = document.getElementById('recovery-error-2');

    if (!r1 || !r2 || !r3) {
        errorDiv.textContent = 'Por favor responde todas las preguntas';
        errorDiv.style.display = 'block';
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/verify-security-answers`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                cedula,
                respuestas: [r1, r2, r3]
            })
        });
        const data = await response.json();

        if (data.success) {
            errorDiv.textContent = '';
            showRecoveryStep(3);
        } else {
            errorDiv.textContent = data.message;
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'Error de conexión';
        errorDiv.style.display = 'block';
    }
}

async function resetPassword() {
    const cedula = document.getElementById('recovery-cedula').value;
    const password = document.getElementById('recovery-password').value;
    const confirm = document.getElementById('recovery-confirm').value;
    const errorDiv = document.getElementById('recovery-error-3');

    if (password.length < 4) {
        errorDiv.textContent = 'La contraseña debe tener al menos 4 caracteres';
        errorDiv.style.display = 'block';
        return;
    }

    if (password !== confirm) {
        errorDiv.textContent = 'Las contraseñas no coinciden';
        errorDiv.style.display = 'block';
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/reset-password`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                cedula,
                nueva_contrasena: password
            })
        });
        const data = await response.json();

        if (data.success) {
            alert('Contraseña actualizada exitosamente. Por favor inicia sesión.');
            closeRecoveryModal();
            showLogin();
        } else {
            errorDiv.textContent = data.message;
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'Error de conexión';
        errorDiv.style.display = 'block';
    }
}



// =====================================================
// COMPRAS MODULE
// =====================================================
async function loadCompras() {
    const tbody = document.getElementById('compras-table-body');
    tbody.innerHTML = '<tr><td colspan="5" class="loading active"><div class="spinner"></div>Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/compras`);
        const compras = await response.json();

        tbody.innerHTML = '';

        if (compras.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px;">No hay compras registradas</td></tr>';
            return;
        }

        compras.forEach(compra => {
            const tr = document.createElement('tr');
            // Calculate total from details
            const total = compra.detalles.reduce((sum, d) => sum + (d.cantidad * parseFloat(d.precio_unitario)), 0);

            tr.innerHTML = `
                <td>${compra.id}</td>
                <td>${compra.proveedor?.nombre || 'Proveedor #' + compra.id_proveedor}</td>
                <td>${new Date(compra.fecha_creacion).toLocaleDateString()}</td>
                <td>$${total.toFixed(2)}</td>
                <td>
                    <button class="action-btn" onclick="downloadCompraPDF(${compra.id})" title="Descargar Factura">
                        <i class="fas fa-file-pdf"></i>
                    </button>
                    <button class="action-btn" onclick="viewCompra(${compra.id})" title="Ver Detalles">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteCompra(${compra.id})" title="Eliminar Compra">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 40px; color: red;">Error al cargar compras</td></tr>';
    }
}

async function openCompraModal() {
    document.getElementById('compra-modal').classList.add('active');

    // Load Proveedores for Select
    const select = document.getElementById('compra-proveedor');
    select.innerHTML = '<option value="">Cargando...</option>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/proveedores`);
        const proveedores = await response.json();

        select.innerHTML = '<option value="">Seleccione un proveedor...</option>';
        proveedores.forEach(p => {
            const option = document.createElement('option');
            option.value = p.id;
            option.textContent = p.nombre;
            select.appendChild(option);
        });
    } catch (error) {
        select.innerHTML = '<option value="">Error al cargar proveedores</option>';
    }

    // Reset table
    document.getElementById('compra-productos-body').innerHTML = '';
    document.getElementById('compra-total').textContent = '0.00';

    // Add first row
    addCompraRow();
}

function closeCompraModal() {
    document.getElementById('compra-modal').classList.remove('active');
}

async function addCompraRow() {
    const tbody = document.getElementById('compra-productos-body');
    const tr = document.createElement('tr');

    // Fetch products for the select
    let productsOptions = '<option value="">Cargando...</option>';
    try {
        const response = await fetch(`${API_BASE_URL}/api/productos`);
        const productos = await response.json();
        productsOptions = '<option value="">Seleccione...</option>' +
            productos.map(p => `<option value="${p.id}">${p.nombre}</option>`).join('');
    } catch (error) {
        productsOptions = '<option value="">Error</option>';
    }

    tr.innerHTML = `
        <td>
            <select class="compra-product-select" required onchange="calculateCompraTotal()">
                ${productsOptions}
            </select>
        </td>
        <td>
            <input type="number" class="compra-cantidad" min="1" value="1" required onchange="calculateCompraTotal()">
        </td>
        <td>
            <input type="number" class="compra-precio" step="0.01" min="0" required onchange="calculateCompraTotal()">
        </td>
        <td>
            <button type="button" class="action-btn delete" onclick="this.closest('tr').remove(); calculateCompraTotal()">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;
    tbody.appendChild(tr);

    // Re-populate select if we already have products loaded (optimization)
    // For now, fetching every time is safer but slower.
    // In a real app, we'd cache the product list.
}

function calculateCompraTotal() {
    const rows = document.querySelectorAll('#compra-productos-body tr');
    let total = 0;

    rows.forEach(row => {
        const cantidad = parseFloat(row.querySelector('.compra-cantidad').value) || 0;
        const precio = parseFloat(row.querySelector('.compra-precio').value) || 0;
        total += cantidad * precio;
    });

    document.getElementById('compra-total').textContent = total.toFixed(2);
}

async function createCompra(event) {
    event.preventDefault();

    const id_proveedor = document.getElementById('compra-proveedor').value;
    const rows = document.querySelectorAll('#compra-productos-body tr');
    const detalles = [];

    rows.forEach(row => {
        const id_producto = row.querySelector('.compra-product-select').value;
        const cantidad = row.querySelector('.compra-cantidad').value;
        const precio = row.querySelector('.compra-precio').value;

        if (id_producto && cantidad && precio) {
            detalles.push({
                id_producto: parseInt(id_producto),
                cantidad: parseInt(cantidad),
                precio_unitario: parseFloat(precio)
            });
        }
    });

    if (detalles.length === 0) {
        alert('Debe agregar al menos un producto');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/compras`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_proveedor: parseInt(id_proveedor),
                detalles: detalles
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Compra registrada exitosamente');
            closeCompraModal();
            loadCompras();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

function downloadCompraPDF(id) {
    window.open(`${API_BASE_URL}/api/compras/${id}/pdf`, '_blank');
}

async function deleteCompra(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar esta compra? Esto revertirá el stock de los productos.'))) {
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/compras/${id}`, {
            method: 'DELETE'
        });
        const data = await response.json();

        if (data.success) {
            alert('Compra eliminada exitosamente');
            loadCompras();
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function viewCompra(id) {
    // Reuse the modal but in "view mode" or just show a simple alert with details for now
    // A better approach is to fill the modal and disable inputs, or use a separate simple modal.
    // Given the constraints, let's use a formatted alert or a simple prompt-like display.
    // Or better, let's populate the existing modal but hide the save button and make fields readonly.

    try {
        // We need to fetch the single purchase details first.
        // Since we don't have a specific GET /api/compras/:id endpoint that returns full details easily accessible
        // (we have list_compras which returns all), we can filter from the list or add the endpoint.
        // Actually, list_compras returns full details including 'detalles'.
        // Let's fetch the list again or find it in the DOM?
        // Better: fetch the specific purchase. We added get_compra endpoint in app.py?
        // Checking app.py... we added get_compra_pdf but maybe not get_compra.
        // Wait, list_compras returns everything. We can just fetch all and find it, or implement get_compra.
        // Let's assume we can fetch all and find it for now to save a step, or just add the endpoint.
        // BUT, for efficiency, a GET /api/compras/:id is better.
        // Let's assume we can fetch all and find it for now to save a step, or just add the endpoint.
        // Actually, I see get_compra in app.py in the previous `replace_file_content` output!
        // It was at the bottom: `def get_compra(id): ...`
        // Let's verify if it exists.

        const response = await fetch(`${API_BASE_URL}/api/compras/${id}`);
        if (!response.ok) throw new Error('Error al cargar detalles');

        const compra = await response.json();

        let detalleHTML = `
DETALLE DE LA COMPRA #${compra.id}
========================
Fecha: ${new Date(compra.fecha_creacion).toLocaleDateString()}
Proveedor ID: ${compra.id_proveedor}
Tasa: ${compra.cotizacion_dolar_bolivares} Bs/$

Productos:
`;

        compra.detalles.forEach(d => {
            detalleHTML += `- Producto ID ${d.id_producto}: ${d.cantidad} x $${d.precio_unitario}\n`;
        });

        alert(detalleHTML);

    } catch (error) {
        alert('Error al cargar detalles: ' + error.message);
    }
}

// =====================================================
// EMPLOYEES MODULE
// =====================================================
async function openRegisterForEmployee() {
    if (await confirmAsync('Para registrar un nuevo empleado, debe cerrar la sesión actual e ir al registro. ¿Desea continuar?')) {
        handleLogout();
        // Force show register after logout (needs a small delay or flag)
        // Since handleLogout reloads or clears state, we might just rely on the user clicking register.
        // But to be helpful, we can set a flag.
        localStorage.setItem('showRegisterOnLoad', 'true');
    }
}

async function loadEmpleados() {
    const tbody = document.getElementById('empleados-table-body');
    tbody.innerHTML = '<tr><td colspan="6" class="loading active"><div class="spinner"></div>Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/usuarios`);
        const empleados = await response.json();

        tbody.innerHTML = '';

        if (empleados.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 40px;">No hay empleados registrados</td></tr>';
            return;
        }

        const soloEmpleados = empleados.filter(u => u.rol !== 'Cliente online');
        const pendientes = soloEmpleados.filter(u => !u.activo);
        const infoEl = document.getElementById('empleados-pendientes-info');
        if (infoEl) {
            infoEl.textContent = pendientes.length > 0
                ? `${pendientes.length} registro(s) pendiente(s) de aprobación`
                : `${soloEmpleados.length} empleado(s) registrados`;
        }

        soloEmpleados.forEach(emp => {
            const tr = document.createElement('tr');
            const estadoBadge = emp.activo
                ? '<span class="badge success">Activo</span>'
                : '<span class="badge warning">Pendiente</span>';
            const nombreCompleto = `${emp.nombre}${emp.apellidos ? ' ' + emp.apellidos : ''}`;
            tr.innerHTML = `
                <td>${emp.id}</td>
                <td>${nombreCompleto}</td>
                <td>${emp.cedula}</td>
                <td><span class="badge ${getRoleBadgeClass(emp.rol)}">${emp.rol}</span></td>
                <td>${estadoBadge}</td>
                <td>
                    <div class="action-btns">
                        ${currentUser.rol === 'Administrador' && !emp.activo ? `
                            <button class="action-btn success" onclick="openAprobarEmpleadoModal(${emp.id}, '${nombreCompleto.replace(/'/g, '')}')" title="Aprobar registro">
                                <i class="fas fa-user-check"></i>
                            </button>
                        ` : ''}
                        ${currentUser.rol === 'Administrador' && emp.rol !== 'Administrador' && emp.activo ? `
                            <button class="action-btn edit" onclick="editEmpleado(${emp.id})" title="Editar Rol">
                                <i class="fas fa-edit"></i>
                            </button>
                        ` : ''}
                        ${currentUser.rol === 'Administrador' && emp.rol !== 'Administrador' ? `
                            <button class="action-btn delete" onclick="deleteEmpleado(${emp.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        console.error('Error loading employees:', error);
        tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: var(--danger);">Error al cargar empleados</td></tr>';
    }
}

function getRoleBadgeClass(rol) {
    switch (rol) {
        case 'Administrador': return 'primary';
        case 'Encargado': return 'warning';
        case 'Vendedor': return 'success';
        case 'Cliente online': return 'secondary';
        default: return 'secondary';
    }
}

async function editEmpleado(id) {
    try {
        const response = await fetch(`${API_BASE_URL}/api/usuarios`);
        const users = await response.json();
        const user = users.find(u => u.id === id);

        if (user) {
            document.getElementById('empleado-id').value = user.id;
            document.getElementById('empleado-nombre').value = `${user.nombre}${user.apellidos ? ' ' + user.apellidos : ''}`;
            document.getElementById('empleado-cedula').value = user.cedula;
            document.getElementById('empleado-rol').value = user.rol;

            document.getElementById('empleado-modal').classList.add('active');
        }
    } catch (error) {
        alert('Error al cargar datos del empleado');
    }
}

function closeEmpleadoModal() {
    document.getElementById('empleado-modal').classList.remove('active');
}

async function saveEmpleado(event) {
    event.preventDefault();

    const id = document.getElementById('empleado-id').value;
    const rol = document.getElementById('empleado-rol').value;

    try {
        const response = await fetch(`${API_BASE_URL}/api/usuarios/${id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ rol: rol })
        });

        if (response.ok) {
            alert('Rol de empleado actualizado exitosamente');
            closeEmpleadoModal();
            loadEmpleados();
        } else {
            const data = await response.json();
            alert(data.message || 'Error al actualizar empleado');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function deleteEmpleado(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar este empleado? Esta acción no se puede deshacer.'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/usuarios/${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            alert('Empleado eliminado exitosamente');
            loadEmpleados();
        } else {
            alert('Error al eliminar empleado');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

// =====================================================
// REGISTRO DIRECTO DE EMPLEADOS (Administrador) + APROBACIÓN
// =====================================================
function openNuevoEmpleadoModal() {
    document.getElementById('nuevo-empleado-nombre').value = '';
    document.getElementById('nuevo-empleado-apellidos').value = '';
    document.getElementById('nuevo-empleado-cedula').value = '';
    document.getElementById('nuevo-empleado-rol').value = '';
    document.getElementById('nuevo-empleado-password').value = '';
    document.getElementById('password-check-emp').querySelectorAll('span').forEach(s => {
        s.className = 'bad';
        s.querySelector('i').className = 'fas fa-circle';
    });
    document.getElementById('nuevo-empleado-modal').classList.add('active');
}

function closeNuevoEmpleadoModal() {
    document.getElementById('nuevo-empleado-modal').classList.remove('active');
}

async function saveNuevoEmpleado(event) {
    event.preventDefault();

    const nombre = document.getElementById('nuevo-empleado-nombre').value;
    const apellidos = document.getElementById('nuevo-empleado-apellidos').value;
    const cedula = document.getElementById('nuevo-empleado-cedula').value;
    const rol = document.getElementById('nuevo-empleado-rol').value;
    const contrasena = document.getElementById('nuevo-empleado-password').value;

    if (!checkPasswordPolicy(contrasena, 'emp')) {
        alert('La contraseña no cumple la política de seguridad (8+ caracteres, mayúscula, minúscula y especial)');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/usuarios`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ nombre, apellidos, cedula, rol, contrasena })
        });
        const data = await response.json();

        if (response.ok) {
            alert('Empleado registrado exitosamente');
            closeNuevoEmpleadoModal();
            loadEmpleados();
        } else {
            alert(data.message || 'Error al registrar empleado');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

let aprobarEmpleadoId = null;

function openAprobarEmpleadoModal(id, nombreCompleto) {
    aprobarEmpleadoId = id;
    document.getElementById('aprobar-empleado-info').textContent =
        `Aprobar el registro de ${nombreCompleto}. Se activará su cuenta y podrá iniciar sesión con el rol asignado.`;
    document.getElementById('aprobar-empleado-rol').value = 'Vendedor';
    document.getElementById('aprobar-empleado-modal').classList.add('active');
}

function closeAprobarEmpleadoModal() {
    document.getElementById('aprobar-empleado-modal').classList.remove('active');
}

async function confirmarAprobacion() {
    if (!aprobarEmpleadoId) return;
    const rol = document.getElementById('aprobar-empleado-rol').value;

    try {
        const response = await fetch(`${API_BASE_URL}/api/empleados/${aprobarEmpleadoId}/aprobar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ rol })
        });
        const data = await response.json();

        if (response.ok) {
            alert('Empleado aprobado exitosamente');
            closeAprobarEmpleadoModal();
            loadEmpleados();
            loadNotificaciones();
        } else {
            alert(data.message || 'Error al aprobar empleado');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

// =====================================================
// PASSWORD RECOVERY
// =====================================================
let recoveryUserId = null;

function showRecovery() {
    document.getElementById('recovery-modal').classList.add('active');
    showRecoveryStep(1);
}

function closeRecoveryModal() {
    document.getElementById('recovery-modal').classList.remove('active');
    document.getElementById('recovery-cedula').value = '';
    document.getElementById('recovery-respuesta-1').value = '';
    document.getElementById('recovery-respuesta-2').value = '';
    document.getElementById('recovery-respuesta-3').value = '';
    document.getElementById('recovery-password').value = '';
    document.getElementById('recovery-confirm').value = '';
    document.querySelectorAll('.error-message').forEach(el => el.textContent = '');
    recoveryUserId = null;
}

function showRecoveryStep(step) {
    document.getElementById('recovery-step-1').style.display = 'none';
    document.getElementById('recovery-step-2').style.display = 'none';
    document.getElementById('recovery-step-3').style.display = 'none';
    document.getElementById(`recovery-step-${step}`).style.display = 'block';
}

async function checkUserForRecovery() {
    const cedula = document.getElementById('recovery-cedula').value;
    const errorDiv = document.getElementById('recovery-error-1');

    if (!cedula) {
        errorDiv.textContent = 'Por favor ingrese su cédula';
        errorDiv.style.display = 'block';
        return;
    }

    errorDiv.textContent = '';

    try {
        const response = await fetch(`${API_BASE_URL}/check-user-recovery`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula })
        });

        const data = await response.json();

        if (data.success) {
            recoveryUserId = data.user_id;
            // Set questions
            document.getElementById('label-pregunta-1').textContent = data.preguntas[0];
            document.getElementById('label-pregunta-2').textContent = data.preguntas[1];
            document.getElementById('label-pregunta-3').textContent = data.preguntas[2];
            showRecoveryStep(2);
        } else {
            errorDiv.textContent = data.message || 'Usuario no encontrado';
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'Error de conexión';
        errorDiv.style.display = 'block';
    }
}

async function verifyAnswers() {
    const r1 = document.getElementById('recovery-respuesta-1').value;
    const r2 = document.getElementById('recovery-respuesta-2').value;
    const r3 = document.getElementById('recovery-respuesta-3').value;
    const errorDiv = document.getElementById('recovery-error-2');

    if (!r1 || !r2 || !r3) {
        errorDiv.textContent = 'Por favor responda todas las preguntas';
        errorDiv.style.display = 'block';
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/verify-security-answers`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: recoveryUserId,
                respuestas: [r1, r2, r3]
            })
        });

        const data = await response.json();

        if (data.success) {
            showRecoveryStep(3);
        } else {
            errorDiv.textContent = data.message || 'Respuestas incorrectas';
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'Error de conexión';
        errorDiv.style.display = 'block';
    }
}

async function resetPassword() {
    const pass = document.getElementById('recovery-password').value;
    const confirm = document.getElementById('recovery-confirm').value;
    const errorDiv = document.getElementById('recovery-error-3');

    if (pass.length < 4) {
        errorDiv.textContent = 'La contraseña debe tener al menos 4 caracteres';
        errorDiv.style.display = 'block';
        return;
    }

    if (pass !== confirm) {
        errorDiv.textContent = 'Las contraseñas no coinciden';
        errorDiv.style.display = 'block';
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/reset-password-recovery`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: recoveryUserId,
                new_password: pass
            })
        });

        const data = await response.json();

        if (data.success) {
            alert('Contraseña actualizada exitosamente. Por favor inicie sesión.');
            closeRecoveryModal();
        } else {
            errorDiv.textContent = data.message || 'Error al actualizar contraseña';
            errorDiv.style.display = 'block';
        }
    } catch (error) {
        errorDiv.textContent = 'Error de conexión';
        errorDiv.style.display = 'block';
    }
}


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
        const url = estadoFilter ? `${API_BASE_URL}/api/apartados?estado=${estadoFilter}` : `${API_BASE_URL}/api/apartados`;

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
                    <button class="btn-icon" onclick="viewApartadoPDF(${a.id})" title="Ver PDF" style="color: var(--primary);">
                        <i class="fas fa-file-pdf"></i>
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
                    ${(a.estado === 'cancelado' || a.estado === 'completado') ? `
                        <button class="btn-icon" onclick="deleteApartado(${a.id})" title="Eliminar" style="color: var(--danger);">
                            <i class="fas fa-trash"></i>
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
    switch (estado) {
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
        const response = await fetch(`${API_BASE_URL}/api/clientes`);
        const clientes = await response.json();
        clienteSelect.innerHTML = '<option value="">Seleccione un cliente...</option>' +
            clientes.map(c => `<option value="${c.id}">${c.nombre} ${c.apellidos} - ${c.cedula}</option>`).join('');
    } catch (error) {
        console.error('Error loading clientes:', error);
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/productos`);
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
        .map(p => `<option value="${p.id}" data-precio="${p.precio_unitario_actual_dolares || p.precio || 0}" data-stock="${p.cantidad_disponible}">${p.nombre} (Stock: ${p.cantidad_disponible})</option>`)
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
            productos.push({ id_producto: parseInt(productoId), cantidad, precio_unitario: precioUnitario });
        }
    });

    if (productos.length === 0) {
        alert('Debe agregar al menos un producto');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/apartados`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_cliente: parseInt(clienteId),
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
            loadProducts();
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
        const response = await fetch(`${API_BASE_URL}/api/apartados/${apartadoId}/pago`, {
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
    if (!(await confirmAsync('¿Está seguro de completar este apartado? Se generará una venta.'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/apartados/${id}/completar`, {
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
    if (!(await confirmAsync('¿Está seguro de cancelar este apartado? Los productos volverán al inventario. El reembolso debe gestionarse manualmente.'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/apartados/${id}/cancelar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' }
        });

        const result = await response.json();

        if (response.ok) {
            alert('Apartado cancelado');
            loadApartados();
            loadProducts();
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
        const response = await fetch(`${API_BASE_URL}/api/apartados/${id}`);
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
            detalleHTML += `- ${d.producto?.nombre}: ${d.cantidad} x $${parseFloat(d.precio_unitario).toFixed(2)}\n`;
        });

        if (data.pagos?.length > 0) {
            detalleHTML += '\nPAGOS:\n';
            data.pagos.forEach(p => {
                detalleHTML += `- ${formatDateApartado(p.fecha)}: $${parseFloat(p.monto).toFixed(2)} ${p.observacion ? '(' + p.observacion + ')' : ''}\n`;
            });
        }

        alert(detalleHTML);
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar detalle');
    }
}

function viewApartadoPDF(id) {
    window.open(`${API_BASE_URL}/api/apartados/${id}/pdf`, '_blank');
}

async function deleteApartado(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar este apartado permanentemente? Esta acción no se puede deshacer.'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/apartados/${id}`, {
            method: 'DELETE'
        });

        const result = await response.json();

        if (response.ok) {
            alert('Apartado eliminado exitosamente');
            loadApartados();
        } else {
            alert('Error: ' + (result.message || 'No se pudo eliminar'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión');
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
        const response = await fetch(`${API_BASE_URL}/api/inventario`);
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
                    <td>${p.categoria_nombre || 'Sin categoría'}</td>
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
        const response = await fetch(`${API_BASE_URL}/api/productos`);
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
        const response = await fetch(`${API_BASE_URL}/api/inventario/ajuste`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                id_producto: parseInt(productoId),
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
            loadProducts();
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
        const response = await fetch(`${API_BASE_URL}/api/inventario/movimientos`);
        const data = await response.json();

        if (data.length === 0) {
            alert('No hay movimientos registrados');
            return;
        }

        let texto = 'HISTORIAL DE MOVIMIENTOS\n========================\n\n';
        data.slice(0, 20).forEach(m => {
            texto += `${formatDateApartado(m.fecha)} | ${m.tipo.toUpperCase()} | ${m.producto?.nombre || 'N/A'} | Cant: ${m.cantidad} | ${m.razon || ''}\n`;
        });

        alert(texto);
    } catch (error) {
        console.error('Error:', error);
        alert('Error al cargar movimientos');
    }
}

// ===== FUNCIONES DE CATEGORÍA PERSONALIZADA =====

// Manejar el cambio en el selector de categoría
function handleCategoriaChange() {
    const select = document.getElementById('product-categoria');
    const container = document.getElementById('nueva-categoria-container');
    const btnEliminar = document.getElementById('btn-eliminar-categoria');

    if (select.value === 'nueva') {
        container.style.display = 'block';
        if (btnEliminar) btnEliminar.style.display = 'none';
        select.removeAttribute('required');
    } else if (select.value && select.value !== '') {
        container.style.display = 'none';
        if (btnEliminar) btnEliminar.style.display = 'flex';
        select.setAttribute('required', 'required');
    } else {
        container.style.display = 'none';
        if (btnEliminar) btnEliminar.style.display = 'none';
        select.setAttribute('required', 'required');
    }
}

// Crear una nueva categoría
async function crearNuevaCategoria() {
    const nombreInput = document.getElementById('nueva-categoria-nombre');
    const nombre = nombreInput.value.trim();

    if (!nombre) {
        alert('Por favor ingresa un nombre para la categoría');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/categorias`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ nombre: nombre })
        });

        if (response.ok) {
            const data = await response.json();

            // Agregar la nueva categoría al select
            const select = document.getElementById('product-categoria');
            const newOption = document.createElement('option');
            newOption.value = data.id;
            newOption.textContent = nombre;

            // Insertar antes de la opción "Nueva categoría..."
            const nuevaOption = select.querySelector('option[value="nueva"]');
            if (nuevaOption) {
                select.insertBefore(newOption, nuevaOption);
            } else {
                select.appendChild(newOption);
            }

            // Seleccionar la nueva categoría
            select.value = data.id;

            // Limpiar y ocultar el contenedor
            nombreInput.value = '';
            document.getElementById('nueva-categoria-container').style.display = 'none';
            select.setAttribute('required', 'required');

            alert('Categoría creada exitosamente');
        } else {
            const errorData = await response.json();
            alert('Error al crear categoría: ' + (errorData.message || 'Error desconocido'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión al crear categoría');
    }
}

// Eliminar una categoría existente
async function eliminarCategoria() {
    const select = document.getElementById('product-categoria');
    const categoriaId = select.value;

    if (!categoriaId || categoriaId === 'nueva' || categoriaId === '') {
        alert('Selecciona una categoría válida para eliminar');
        return;
    }

    // Obtener el nombre de la categoría seleccionada
    const nombreCategoria = select.options[select.selectedIndex].text;

    if (!(await confirmAsync(`¿Estás seguro de que deseas eliminar la categoría "${nombreCategoria}"?\n\nNota: No se puede eliminar si tiene productos asociados.`))) {
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/categorias/${categoriaId}`, {
            method: 'DELETE'
        });

        const data = await response.json();

        if (response.ok) {
            // Eliminar la opción del select
            select.remove(select.selectedIndex);
            select.value = '';

            // Ocultar botón de eliminar
            document.getElementById('btn-eliminar-categoria').style.display = 'none';

            alert('Categoría eliminada exitosamente');
        } else {
            alert(data.message || 'Error al eliminar categoría');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión al eliminar categoría');
    }
}

// ==========================================
// CONSULTAS MODULE
// ==========================================
async function loadConsultas() {
    const tbody = document.getElementById('consultas-table-body');
    if (!tbody) return;

    tbody.innerHTML = `<tr><td colspan="6" class="loading active"><div class="spinner"></div>Cargando consultas...</td></tr>`;

    try {
        const vendedor = document.getElementById('filtro-vendedor').value;
        const cliente = document.getElementById('filtro-cliente').value;
        const fechaDesde = document.getElementById('filtro-fecha-desde').value;
        const fechaHasta = document.getElementById('filtro-fecha-hasta').value;

        const params = new URLSearchParams();
        if (vendedor) params.append('id_vendedor', vendedor);
        if (cliente) params.append('id_cliente', cliente);
        if (fechaDesde) params.append('fecha_desde', fechaDesde);
        if (fechaHasta) params.append('fecha_hasta', fechaHasta);

        const response = await fetch(`${API_BASE_URL}/api/consultas/ventas?${params.toString()}`);
        const ventas = await response.json();

        tbody.innerHTML = '';

        if (ventas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" style="text-align: center;">No se encontraron ventas</td></tr>';
            return;
        }

        ventas.forEach(v => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>#${String(v.id).padStart(6, '0')}</td>
                <td>${new Date(v.fecha_creacion).toLocaleString()}</td>
                <td>${v.vendedor.nombre || 'Sin asignar'}</td>
                <td>${v.cliente.nombre} ${v.cliente.apellidos || ''}</td>
                <td>$ ${v.total.toFixed(2)}</td>
                <td>
                    <button class="action-btn view" onclick="verFactura(${v.id})" title="Ver Detalle">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="action-btn" onclick="verFactura(${v.id})" title="Imprimir" style="background: #6b7280; color: white;">
                        <i class="fas fa-print"></i>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });

    } catch (error) {
        console.error('Error loading consultas:', error);
        tbody.innerHTML = `<tr><td colspan="6" style="color: red; text-align: center;">Error al cargar datos: ${error.message}</td></tr>`;
    }
}

async function loadFiltrosConsultas() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/empleados`);
        const empleados = await response.json();

        const select = document.getElementById('filtro-vendedor');
        if (select) {
            select.innerHTML = '<option value="">Todos los vendedores</option>';
            empleados.forEach(e => {
                const option = document.createElement('option');
                option.value = e.id;
                option.textContent = e.nombre;
                select.appendChild(option);
            });
        }
    } catch (e) {
        console.error("Error cargando vendedores", e);
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/clientes`);
        const clientes = await response.json();

        const select = document.getElementById('filtro-cliente');
        if (select) {
            select.innerHTML = '<option value="">Todos los clientes</option>';
            clientes.forEach(c => {
                const option = document.createElement('option');
                option.value = c.id;
                option.textContent = `${c.nombre} ${c.apellidos || ''}`;
                select.appendChild(option);
            });
        }
    } catch (e) {
        console.error("Error cargando clientes", e);
    }
}

async function exportarConsultasPDF() {
    const vendedor = document.getElementById('filtro-vendedor').value;
    const cliente = document.getElementById('filtro-cliente').value;
    const fechaDesde = document.getElementById('filtro-fecha-desde').value;
    const fechaHasta = document.getElementById('filtro-fecha-hasta').value;

    const params = new URLSearchParams();
    if (vendedor) params.append('id_vendedor', vendedor);
    if (cliente) params.append('id_cliente', cliente);
    if (fechaDesde) params.append('fecha_desde', fechaDesde);
    if (fechaHasta) params.append('fecha_hasta', fechaHasta);

    window.open(`${API_BASE_URL}/api/consultas/ventas/pdf?${params.toString()}`, '_blank');
}

// =====================================================
// COTIZACION MODULE
// =====================================================
async function loadCotizacion() {
    const tbody = document.getElementById('cotizacion-table-body');
    const displayTasa = document.getElementById('cotizacion-actual-display');
    const displayFecha = document.getElementById('cotizacion-fecha-display');

    tbody.innerHTML = '<tr><td colspan="3" class="loading active"><div class="spinner"></div>Cargando historial...</td></tr>';
    displayTasa.textContent = 'Cargando...';

    try {
        // Cargar tasa actual
        const actualRes = await fetch(`${API_BASE_URL}/api/cotizacion/actual`);
        const actualData = await actualRes.json();

        if (actualData.tasa_dolar_bolivares) {
            displayTasa.textContent = `${parseFloat(actualData.tasa_dolar_bolivares).toFixed(2)} Bs/USD`;
            const fecha = new Date(actualData.fecha_hora).toLocaleDateString('es-VE');
            displayFecha.textContent = `Actualizado: ${fecha}`;
        } else {
            displayTasa.textContent = 'No definida';
        }

        // Cargar historial
        const historyRes = await fetch(`${API_BASE_URL}/api/cotizacion`);
        const historyData = await historyRes.json();

        tbody.innerHTML = '';

        if (historyData.length === 0) {
            tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; padding: 20px;">No hay historial disponible</td></tr>';
            return;
        }

        historyData.forEach(item => {
            const tr = document.createElement('tr');
            const fecha = new Date(item.fecha_hora).toLocaleString('es-VE');
            const usuario = item.id_usuario ? `ID: ${item.id_usuario}` : 'Sistema';

            tr.innerHTML = `
                <td>${fecha}</td>
                <td>${parseFloat(item.tasa_dolar_bolivares).toFixed(2)}</td>
                <td>${usuario}</td>
            `;
            tbody.appendChild(tr);
        });

    } catch (error) {
        console.error('Error loading cotizacion:', error);
        tbody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: red;">Error al cargar datos</td></tr>';
        displayTasa.textContent = 'Error';
    }
}

async function updateCotizacion(event) {
    event.preventDefault();

    const nuevaTasa = document.getElementById('nueva-tasa').value;
    if (!nuevaTasa || parseFloat(nuevaTasa) <= 0) {
        alert('Por favor ingrese una tasa válida');
        return;
    }

    if (!(await confirmAsync(`¿Está seguro de actualizar la tasa a ${nuevaTasa} Bs/USD?`))) {
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/cotizacion`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                usuario_id: currentUser.id,
                tasa: parseFloat(nuevaTasa)
            })
        });

        const data = await response.json();

        if (response.ok) {
            alert('Cotización actualizada correctamente');
            document.getElementById('nueva-tasa').value = '';
            loadCotizacion(); // Recargar datos
        } else {
            alert('Error al actualizar: ' + (data.message || 'Error desconocido'));
        }
    } catch (error) {
        alert('Error de conexión');
        console.error(error);
    }
}

// =====================================================
// CREDENCIALES MODULE
// =====================================================
async function loadCredenciales() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/negocio`);
        if (response.ok) {
            const data = await response.json();
            document.getElementById('cred-nombre').value = data.nombre || '';
            document.getElementById('cred-rif').value = data.rif || '';
            document.getElementById('cred-telefono').value = data.telefono || '';
            document.getElementById('cred-direccion').value = data.direccion || '';
        } else {
            console.warn('No se pudieron cargar los datos del negocio');
        }
    } catch (error) {
        console.error('Error loading credenciales:', error);
    }
}

async function loadDashboardStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/dashboard/stats`);
        const data = await response.json();

        // Actualizar contadores principales
        document.getElementById('stat-productos').textContent = data.total_productos || 0;
        document.getElementById('stat-clientes').textContent = data.total_clientes || 0;
        document.getElementById('stat-ventas').textContent = data.total_ventas || 0;
        document.getElementById('stat-stock-bajo').textContent = data.stock_bajo || 0;

        // Actualizar contadores de tarjetas de módulos (si existen en el DOM)
        if (document.getElementById('dash-count-empleados'))
            document.getElementById('dash-count-empleados').textContent = data.total_empleados || 0;

        if (document.getElementById('dash-count-proveedores'))
            document.getElementById('dash-count-proveedores').textContent = data.total_proveedores || 0;

        if (document.getElementById('dash-count-compras'))
            document.getElementById('dash-count-compras').textContent = data.total_compras || 0;

        if (document.getElementById('dash-count-productos'))
            document.getElementById('dash-count-productos').textContent = data.total_productos || 0;

        if (document.getElementById('dash-count-clientes'))
            document.getElementById('dash-count-clientes').textContent = data.total_clientes || 0;

        if (document.getElementById('dash-count-ventas'))
            document.getElementById('dash-count-ventas').textContent = data.total_ventas || 0;

        if (document.getElementById('dash-count-apartados'))
            document.getElementById('dash-count-apartados').textContent = data.total_apartados_activos || 0;

        if (document.getElementById('dash-count-inventario'))
            document.getElementById('dash-count-inventario').textContent = data.total_inventario || 0;

        if (document.getElementById('dash-count-reembolsos'))
            document.getElementById('dash-count-reembolsos').textContent = data.total_reembolsos || 0;

        if (document.getElementById('dash-count-cotizacion'))
            document.getElementById('dash-count-cotizacion').textContent = (data.total_cotizacion || 0).toFixed(2);

    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

async function saveCredenciales(event) {
    event.preventDefault();

    const data = {
        nombre: document.getElementById('cred-nombre').value,
        rif: document.getElementById('cred-rif').value,
        telefono: document.getElementById('cred-telefono').value,
        direccion: document.getElementById('cred-direccion').value
    };

    try {
        const response = await fetch(`${API_BASE_URL}/api/negocio`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (response.ok) {
            alert('Datos de la empresa actualizados correctamente');
        } else {
            alert('Error al actualizar: ' + (result.message || 'Error desconocido'));
        }
    } catch (error) {
        alert('Error de conexión');
        console.error(error);
    }
}

// =====================================================================
// REEMBOLSOS
// =====================================================================
async function loadReembolsos() {
    const tbody = document.getElementById('reembolsos-table-body');
    if (!tbody) return;

    tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 20px;">Cargando...</td></tr>';

    try {
        const response = await fetch(`${API_BASE_URL}/api/reembolsos`);
        const reembolsos = await response.json();

        tbody.innerHTML = '';
        if (reembolsos.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 40px; color: #6b7280;">No hay reembolsos registrados</td></tr>';
            return;
        }

        reembolsos.forEach(r => {
            const row = `
                <tr>
                    <td>${r.id}</td>
                    <td>#${r.id_venta}</td>
                    <td>${r.usuario_nombre || 'N/A'}</td>
                    <td>$${parseFloat(r.monto_dolares).toFixed(2)}</td>
                    <td>${parseFloat(r.monto_bolivares).toFixed(2)} Bs</td>
                    <td>${parseFloat(r.tasa_cambio).toFixed(2)}</td>
                    <td>${r.fecha}</td>
                    <td>${r.motivo || '-'}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="btn-icon" onclick="printReembolso(${r.id})" title="Imprimir">
                                <i class="fas fa-print"></i>
                            </button>
                            <button class="btn-icon danger" onclick="deleteReembolso(${r.id})" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.innerHTML += row;
        });
    } catch (error) {
        console.error('Error:', error);
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center; padding: 40px; color: red;">Error al cargar reembolsos</td></tr>';
    }
}

function openReembolsoModal() {
    const modal = document.getElementById('reembolso-modal');
    modal.classList.add('active');
    document.getElementById('reembolso-form').reset();
    document.getElementById('venta-info-reembolso').textContent = '';
    document.getElementById('reembolso-calculo-bs').textContent = 'Monto en Bs: 0.00 (Tasa: 0.00)';
}

function closeReembolsoModal() {
    document.getElementById('reembolso-modal').classList.remove('active');
}

// Variable global para guardar la tasa histórica de la venta seleccionada
let tasaHistoricaVentaReembolso = 0;

async function buscarVentaParaReembolso() {
    const ventaId = document.getElementById('reembolso-venta-id').value;
    if (!ventaId) return;

    try {
        document.getElementById('venta-info-reembolso').textContent = 'Verificando ID...';

        // Obtener datos de la venta incluyendo la cotización histórica
        const response = await fetch(`${API_BASE_URL}/api/ventas/${ventaId}`);

        if (response.ok) {
            const venta = await response.json();
            tasaHistoricaVentaReembolso = parseFloat(venta.cotizacion_dolar_bolivares) || 0;

            const clienteNombre = venta.cliente ? `${venta.cliente.nombre} ${venta.cliente.apellidos || ''}` : 'N/A';
            const fecha = new Date(venta.fecha_creacion).toLocaleDateString('es-VE');

            document.getElementById('venta-info-reembolso').textContent =
                `Venta encontrada: ${clienteNombre} - Fecha: ${fecha} - Tasa histórica: ${tasaHistoricaVentaReembolso.toFixed(2)} Bs/$`;
            document.getElementById('venta-info-reembolso').className = 'form-text text-success';

            // Actualizar el cálculo de Bs con la tasa histórica
            actualizarCalculoReembolsoBs();
        } else {
            tasaHistoricaVentaReembolso = 0;
            document.getElementById('venta-info-reembolso').textContent = 'Venta no encontrada';
            document.getElementById('venta-info-reembolso').className = 'form-text text-danger';
            document.getElementById('reembolso-calculo-bs').textContent = 'Monto en Bs: 0.00 (Tasa: 0.00)';
        }

    } catch (error) {
        console.error(error);
        tasaHistoricaVentaReembolso = 0;
        document.getElementById('venta-info-reembolso').textContent = 'Error al buscar venta';
        document.getElementById('venta-info-reembolso').className = 'form-text text-danger';
    }
}

// Función para actualizar el cálculo de bolívares usando la tasa histórica
function actualizarCalculoReembolsoBs() {
    const montoInput = document.getElementById('reembolso-monto');
    const monto = parseFloat(montoInput?.value) || 0;
    const montoBs = monto * tasaHistoricaVentaReembolso;
    document.getElementById('reembolso-calculo-bs').textContent =
        `Monto en Bs: ${montoBs.toFixed(2)} (Tasa histórica: ${tasaHistoricaVentaReembolso.toFixed(2)} Bs/$)`;
}

// Agregar event listener al campo de monto para actualizar el cálculo
document.addEventListener('DOMContentLoaded', function () {
    const montoInput = document.getElementById('reembolso-monto');
    if (montoInput) {
        montoInput.addEventListener('input', actualizarCalculoReembolsoBs);
    }
});

async function createReembolso() {
    const ventaId = document.getElementById('reembolso-venta-id').value;
    const monto = document.getElementById('reembolso-monto').value;
    const motivo = document.getElementById('reembolso-motivo').value;

    if (!ventaId || !monto || !motivo) {
        alert('Por favor complete todos los campos');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/reembolsos`, {
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
            closeReembolsoModal();
            loadReembolsos();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al procesar reembolso');
    }
}

async function deleteReembolso(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar este reembolso?'))) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/reembolsos/${id}`, {
            method: 'DELETE'
        });
        const result = await response.json();

        if (result.success) {
            alert('Reembolso eliminado correctamente');
            loadReembolsos();
        } else {
            alert('Error: ' + result.message);
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error al eliminar reembolso');
    }
}

function printReembolso(id) {
    window.open(`${API_BASE_URL}/api/reembolsos/${id}/pdf`, '_blank');
}

// =====================================================
// USER PROFILE PANEL
// =====================================================

// Toggle profile dropdown
function toggleProfileDropdown(event) {
    event.stopPropagation();

    // Check if user is 'Anónimo' - don't show dropdown for anonymous users
    if (currentUser && currentUser.rol === 'Anónimo') {
        return;
    }

    const dropdown = document.getElementById('profile-dropdown');
    dropdown.classList.toggle('active');

    // Update dropdown info
    if (dropdown.classList.contains('active')) {
        updateProfileDropdownInfo();
    }
}

// Update profile dropdown info
function updateProfileDropdownInfo() {
    if (!currentUser) return;

    const avatarLarge = document.getElementById('profile-avatar-large');
    const dropdownName = document.getElementById('profile-dropdown-name');
    const dropdownRole = document.getElementById('profile-dropdown-role');

    // Update avatar with photo or initial
    if (avatarLarge) {
        if (currentUser.foto_url) {
            const fullUrl = currentUser.foto_url.startsWith('http') ? currentUser.foto_url : `${API_BASE_URL}${currentUser.foto_url}`;
            avatarLarge.innerHTML = `<img src="${fullUrl}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
        } else {
            avatarLarge.innerHTML = currentUser.nombre ? currentUser.nombre.charAt(0).toUpperCase() : 'U';
        }
    }
    if (dropdownName) dropdownName.textContent = currentUser.nombre || 'Usuario';
    if (dropdownRole) dropdownRole.textContent = currentUser.rol || 'Rol';
}

// Close dropdown when clicking outside
document.addEventListener('click', function (event) {
    const dropdown = document.getElementById('profile-dropdown');
    const userInfo = document.querySelector('.user-info');

    if (dropdown && !userInfo.contains(event.target)) {
        dropdown.classList.remove('active');
    }
});

// ===== PROFILE EDIT MODAL =====
function openProfileEditModal(event) {
    event.stopPropagation();

    if (currentUser && currentUser.rol === 'Anónimo') {
        alert('El usuario anónimo no puede editar su perfil.');
        return;
    }

    // Close dropdown
    document.getElementById('profile-dropdown').classList.remove('active');

    // Load current user data into form
    loadProfileDataToForm();

    // Show modal
    document.getElementById('profile-edit-modal').style.display = 'flex';
}

function closeProfileEditModal() {
    document.getElementById('profile-edit-modal').style.display = 'none';
}

async function loadProfileDataToForm() {
    if (!currentUser) return;

    try {
        const response = await fetch(`${API_BASE_URL}/api/usuarios`);
        const usuarios = await response.json();

        const usuario = usuarios.find(u => u.id === currentUser.id);

        if (usuario) {
            document.getElementById('profile-nombre').value = usuario.nombre || '';
            document.getElementById('profile-apellidos').value = usuario.apellidos || '';
            document.getElementById('profile-cedula').value = usuario.cedula || '';
            document.getElementById('profile-direccion').value = usuario.direccion || '';
            document.getElementById('profile-foto-url').value = usuario.foto_url || '';
            document.getElementById('profile-foto-file').value = '';

            // Update photo preview
            updateProfilePhotoPreview(usuario.foto_url, usuario.nombre);

            // Store current foto_url for reference
            currentUser.foto_url = usuario.foto_url;
        }
    } catch (error) {
        console.error('Error loading profile:', error);
    }
}

// Update profile photo preview in modal
function updateProfilePhotoPreview(photoUrl, nombre) {
    const photoImg = document.getElementById('profile-photo-img');
    const photoInitial = document.getElementById('profile-photo-initial');

    if (photoUrl) {
        const fullUrl = photoUrl.startsWith('http') ? photoUrl : `${API_BASE_URL}${photoUrl}`;
        photoImg.src = fullUrl;
        photoImg.style.display = 'block';
        photoInitial.style.display = 'none';
    } else {
        photoImg.style.display = 'none';
        photoInitial.style.display = 'block';
        photoInitial.textContent = nombre ? nombre.charAt(0).toUpperCase() : 'U';
    }
}

// Preview photo from URL input
function previewProfilePhotoUrl() {
    const url = document.getElementById('profile-foto-url').value;
    const nombre = document.getElementById('profile-nombre').value || currentUser?.nombre || 'U';

    if (url) {
        // Clear file input
        document.getElementById('profile-foto-file').value = '';
        updateProfilePhotoPreview(url, nombre);
    }
}

// Preview photo from file input
function previewProfilePhotoFile() {
    const fileInput = document.getElementById('profile-foto-file');
    const file = fileInput.files[0];

    if (file) {
        // Clear URL input
        document.getElementById('profile-foto-url').value = '';

        // Show preview
        const reader = new FileReader();
        reader.onload = function (e) {
            const photoImg = document.getElementById('profile-photo-img');
            const photoInitial = document.getElementById('profile-photo-initial');
            photoImg.src = e.target.result;
            photoImg.style.display = 'block';
            photoInitial.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }
}

// Clear profile photo
function clearProfilePhoto() {
    document.getElementById('profile-foto-url').value = '';
    document.getElementById('profile-foto-file').value = '';

    const nombre = document.getElementById('profile-nombre').value || currentUser?.nombre || 'U';
    updateProfilePhotoPreview(null, nombre);
}

async function saveProfileChanges(event) {
    event.preventDefault();

    const nombre = document.getElementById('profile-nombre').value;
    const apellidos = document.getElementById('profile-apellidos').value;
    const cedula = document.getElementById('profile-cedula').value;
    const direccion = document.getElementById('profile-direccion').value;
    const fotoUrl = document.getElementById('profile-foto-url').value;
    const fotoFile = document.getElementById('profile-foto-file').files[0];

    console.log('=== DEBUG saveProfileChanges ===');
    console.log('currentUser:', currentUser);
    console.log('currentUser.id:', currentUser?.id);
    console.log('fotoUrl:', fotoUrl);
    console.log('fotoFile:', fotoFile);

    if (!currentUser || !currentUser.id) {
        alert('Error: No hay usuario activo. Por favor, cierra sesión e inicia de nuevo.');
        return;
    }

    try {
        let finalFotoUrl = fotoUrl;

        // If there's a file, upload it first
        if (fotoFile) {
            console.log('Uploading file...');
            const formData = new FormData();
            formData.append('foto', fotoFile);

            const uploadResponse = await fetch(`${API_BASE_URL}/api/usuarios/${currentUser.id}/foto`, {
                method: 'POST',
                body: formData
            });

            console.log('Upload response status:', uploadResponse.status);
            const uploadResult = await uploadResponse.json();
            console.log('Upload result:', uploadResult);

            if (uploadResult.success) {
                finalFotoUrl = uploadResult.foto_url;
            } else {
                alert('Error al subir foto: ' + uploadResult.message);
                return;
            }
        }

        console.log('Final foto_url:', finalFotoUrl);
        console.log('Updating profile with PUT to:', `${API_BASE_URL}/api/usuarios/${currentUser.id}`);

        // Now update profile data
        const response = await fetch(`${API_BASE_URL}/api/usuarios/${currentUser.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nombre,
                apellidos,
                cedula,
                direccion,
                foto_url: finalFotoUrl || null
            })
        });

        console.log('PUT response status:', response.status);
        const result = await response.json();
        console.log('PUT result:', result);

        if (response.ok) {
            // Update currentUser
            currentUser.nombre = nombre;
            currentUser.foto_url = finalFotoUrl;

            // Also update localStorage
            localStorage.setItem('currentUser', JSON.stringify(currentUser));

            // Update UI
            updateUserAvatarUI(nombre, finalFotoUrl);
            updateProfileDropdownInfo();

            alert('Perfil actualizado correctamente');
            closeProfileEditModal();
        } else {
            alert('Error: ' + (result.message || 'No se pudo actualizar el perfil'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión: ' + error.message);
    }
}

// Update user avatar in top bar and dropdown
function updateUserAvatarUI(nombre, fotoUrl) {
    const userAvatar = document.getElementById('user-avatar');
    const dropdownAvatar = document.getElementById('profile-avatar-large');
    const userName = document.getElementById('user-name');

    if (userName) userName.textContent = nombre;

    if (fotoUrl) {
        const fullUrl = fotoUrl.startsWith('http') ? fotoUrl : `${API_BASE_URL}${fotoUrl}`;

        // Update main avatar
        userAvatar.innerHTML = `<img src="${fullUrl}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;

        // Update dropdown avatar
        if (dropdownAvatar) {
            dropdownAvatar.innerHTML = `<img src="${fullUrl}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
        }
    } else {
        userAvatar.innerHTML = nombre.charAt(0).toUpperCase();
        if (dropdownAvatar) {
            dropdownAvatar.innerHTML = nombre.charAt(0).toUpperCase();
        }
    }
}


// ===== CHANGE PASSWORD MODAL =====
function openChangePasswordModal(event) {
    event.stopPropagation();

    if (currentUser && currentUser.rol === 'Anónimo') {
        alert('El usuario anónimo no puede cambiar su contraseña.');
        return;
    }

    // Close dropdown
    document.getElementById('profile-dropdown').classList.remove('active');

    // Clear form
    document.getElementById('current-password').value = '';
    document.getElementById('new-password').value = '';
    document.getElementById('confirm-new-password').value = '';

    // Show modal
    document.getElementById('change-password-modal').style.display = 'flex';
}

function closeChangePasswordModal() {
    document.getElementById('change-password-modal').style.display = 'none';
}

async function saveNewPassword(event) {
    event.preventDefault();

    const currentPassword = document.getElementById('current-password').value;
    const newPassword = document.getElementById('new-password').value;
    const confirmPassword = document.getElementById('confirm-new-password').value;

    console.log('=== DEBUG saveNewPassword ===');
    console.log('currentUser:', JSON.stringify(currentUser));

    if (newPassword !== confirmPassword) {
        alert('Las contraseñas no coinciden');
        return;
    }

    if (!checkPasswordPolicy(newPassword)) {
        alert('La contraseña no cumple la política de seguridad (mínimo 8 caracteres, mayúscula, minúscula y carácter especial)');
        return;
    }

    if (!currentUser || !currentUser.id) {
        alert('Error: No hay usuario activo. Por favor, cierra sesión e inicia de nuevo.');
        return;
    }

    try {
        // ALWAYS fetch user data from API to ensure we have cedula
        console.log('Fetching user data from API for id:', currentUser.id);
        const userResponse = await fetch(`${API_BASE_URL}/api/usuarios`);
        const usuarios = await userResponse.json();
        const usuario = usuarios.find(u => u.id === currentUser.id);

        if (!usuario || !usuario.cedula) {
            alert('Error: No se encontró el usuario en el sistema.');
            return;
        }

        const userCedula = usuario.cedula;
        console.log('User cedula obtained:', userCedula);

        // Verify current password by attempting login
        console.log('Verifying password...');
        const verifyResponse = await fetch(`${API_BASE_URL}/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                usuario: userCedula,
                contrasena: currentPassword
            })
        });

        console.log('Verify response status:', verifyResponse.status);
        const verifyResult = await verifyResponse.json();
        console.log('Verify result:', verifyResult);

        if (!verifyResponse.ok || !verifyResult.success) {
            alert('La contraseña actual es incorrecta');
            return;
        }
        if (verifyResult.token) localStorage.setItem(SESSION_TOKEN_KEY, verifyResult.token);

        console.log('Password verified! Updating password...');

        // Update the password
        const updateResponse = await fetch(`${API_BASE_URL}/api/usuarios/${currentUser.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                contrasena: newPassword
            })
        });

        console.log('Update response status:', updateResponse.status);

        if (updateResponse.ok) {
            alert('Contraseña cambiada correctamente');
            closeChangePasswordModal();
        } else {
            const result = await updateResponse.json();
            alert('Error: ' + (result.message || 'No se pudo cambiar la contraseña'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión');
    }
}

// ===== SECURITY QUESTIONS MODAL =====
function openSecurityQuestionsModal(event) {
    event.stopPropagation();

    if (currentUser && currentUser.rol === 'Anónimo') {
        alert('El usuario anónimo no puede configurar preguntas de seguridad.');
        return;
    }

    // Close dropdown
    document.getElementById('profile-dropdown').classList.remove('active');

    // Load current security questions
    loadSecurityQuestions();

    // Show modal
    document.getElementById('security-questions-modal').style.display = 'flex';
}

function closeSecurityQuestionsModal() {
    document.getElementById('security-questions-modal').style.display = 'none';
}

async function loadSecurityQuestions() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/usuarios`);
        const usuarios = await response.json();

        const usuario = usuarios.find(u => u.id === currentUser.id);

        if (usuario) {
            // Set questions and answers if they exist
            if (usuario.pregunta_1) {
                document.getElementById('security-pregunta-1').value = usuario.pregunta_1;
            }
            if (usuario.pregunta_2) {
                document.getElementById('security-pregunta-2').value = usuario.pregunta_2;
            }
            if (usuario.pregunta_3) {
                document.getElementById('security-pregunta-3').value = usuario.pregunta_3;
            }

            // Note: We don't show answers for security
            document.getElementById('security-respuesta-1').value = '';
            document.getElementById('security-respuesta-2').value = '';
            document.getElementById('security-respuesta-3').value = '';
        }
    } catch (error) {
        console.error('Error loading security questions:', error);
    }
}

async function saveSecurityQuestions(event) {
    event.preventDefault();

    const pregunta1 = document.getElementById('security-pregunta-1').value;
    const respuesta1 = document.getElementById('security-respuesta-1').value;
    const pregunta2 = document.getElementById('security-pregunta-2').value;
    const respuesta2 = document.getElementById('security-respuesta-2').value;
    const pregunta3 = document.getElementById('security-pregunta-3').value;
    const respuesta3 = document.getElementById('security-respuesta-3').value;

    console.log('=== DEBUG saveSecurityQuestions ===');
    console.log('currentUser.id:', currentUser?.id);
    console.log('Pregunta 1:', pregunta1, 'Respuesta 1:', respuesta1);
    console.log('Pregunta 2:', pregunta2, 'Respuesta 2:', respuesta2);
    console.log('Pregunta 3:', pregunta3, 'Respuesta 3:', respuesta3);

    if (!currentUser || !currentUser.id) {
        alert('Error: No hay usuario activo. Por favor, cierra sesión e inicia de nuevo.');
        return;
    }

    try {
        const response = await fetch(`${API_BASE_URL}/api/usuarios/${currentUser.id}`, {
            method: 'PUT',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                pregunta_1: pregunta1,
                respuesta_1: respuesta1,
                pregunta_2: pregunta2,
                respuesta_2: respuesta2,
                pregunta_3: pregunta3,
                respuesta_3: respuesta3
            })
        });

        console.log('Response status:', response.status);
        const result = await response.json();
        console.log('Response result:', result);

        if (response.ok) {
            alert('Preguntas de seguridad actualizadas correctamente');
            closeSecurityQuestionsModal();
        } else {
            alert('Error: ' + (result.message || 'No se pudieron actualizar las preguntas'));
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Error de conexión');
    }
}

// =============================================
// MÓDULO DE ESTADÍSTICAS
// =============================================
let chartHistorico = null;
let chartCategorias = null;
let chartTopProductos = null;

async function loadEstadisticas() {
    // Cargar Resumen (KPIs)
    try {
        const responseKpi = await fetch(`${API_BASE_URL}/api/estadisticas/resumen`);
        const kpis = await responseKpi.json();

        document.getElementById('stat-ventas-hoy').textContent = `$${kpis.ventas_hoy_monto.toFixed(2)}`;
        document.getElementById('stat-compras-hoy').textContent = `$${kpis.compras_hoy_monto.toFixed(2)}`;
        document.getElementById('stat-beneficio-hoy').textContent = `$${kpis.beneficio_hoy.toFixed(2)}`;
        document.getElementById('stat-apartados-activos').textContent = kpis.apartados_activos;

    } catch (error) {
        console.error('Error loading KPIs:', error);
    }

    // Cargar Gráficas
    try {
        const responseHist = await fetch(`${API_BASE_URL}/api/estadisticas/historico`);
        const dataHist = await responseHist.json();

        // 1. Gráfica Histórica (Line)
        const ctxHistorico = document.getElementById('chart-historico').getContext('2d');
        if (chartHistorico) chartHistorico.destroy();

        chartHistorico = new Chart(ctxHistorico, {
            type: 'line',
            data: {
                labels: dataHist.fechas,
                datasets: [
                    {
                        label: 'Ingresos (Ventas)',
                        data: dataHist.ventas,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    },
                    {
                        label: 'Egresos (Compras)',
                        data: dataHist.compras,
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom' }
                },
                scales: {
                    y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Gráfica Categorías (Doughnut)
        const ctxCategorias = document.getElementById('chart-categorias').getContext('2d');
        if (chartCategorias) chartCategorias.destroy();

        chartCategorias = new Chart(ctxCategorias, {
            type: 'doughnut',
            data: {
                labels: dataHist.ventas_por_categoria.labels,
                datasets: [{
                    data: dataHist.ventas_por_categoria.data,
                    backgroundColor: [
                        '#3b82f6', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#ec4899'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12 } }
                }
            }
        });

        // 3. Top Productos (Bar)
        const ctxTop = document.getElementById('chart-top-productos').getContext('2d');
        if (chartTopProductos) chartTopProductos.destroy();

        chartTopProductos = new Chart(ctxTop, {
            type: 'bar',
            data: {
                labels: dataHist.top_productos.labels,
                datasets: [{
                    label: 'Unidades Vendidas',
                    data: dataHist.top_productos.data,
                    backgroundColor: '#10b981',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    x: { beginAtZero: true }
                }
            }
        });

    } catch (error) {
        console.error('Error loading charts:', error);
    }
}

// =====================================================
// SITCAV — NOTIFICACIONES (campana)
// =====================================================
const NOTIF_ICONS = {
    apartado: 'fa-box-open',
    pago_apartado: 'fa-money-bill-wave',
    stock_bajo: 'fa-exclamation-triangle',
    cotizacion: 'fa-dollar-sign',
    transferencia: 'fa-exchange-alt',
    sistema: 'fa-cog',
    pregunta: 'fa-question-circle',
    chat: 'fa-comments',
    ticket: 'fa-life-ring'
};

let notifPanelAbierto = false;

function toggleNotifPanel(event) {
    if (event) event.stopPropagation();
    const panel = document.getElementById('notif-panel');
    notifPanelAbierto = !notifPanelAbierto;
    panel.classList.toggle('open', notifPanelAbierto);
    if (notifPanelAbierto) loadNotificaciones();
}

document.addEventListener('click', (e) => {
    const panel = document.getElementById('notif-panel');
    if (panel && notifPanelAbierto && !panel.contains(e.target) && !e.target.closest('.bell-btn')) {
        panel.classList.remove('open');
        notifPanelAbierto = false;
    }
});

async function loadNotificaciones() {
    try {
        const response = await fetch(`${API_BASE_URL}/api/notificaciones`);
        const data = await response.json();
        const badge = document.getElementById('bell-badge');
        if (data.no_leidas > 0) {
            badge.textContent = data.no_leidas > 99 ? '99+' : data.no_leidas;
            badge.style.display = 'flex';
        } else {
            badge.style.display = 'none';
        }

        const list = document.getElementById('notif-list');
        if (!list) return;
        if (!data.notificaciones || data.notificaciones.length === 0) {
            list.innerHTML = '<div style="text-align:center;padding:24px;color:var(--muted);font-size:.9em;"><i class="fas fa-bell-slash"></i><br>Sin notificaciones</div>';
            return;
        }

        list.innerHTML = data.notificaciones.map(n => `
            <div class="notif-item ${n.leida ? 'leida' : 'no-leida'}" onclick="leerNotificacion(${n.id})">
                <div class="notif-icon"><i class="fas ${NOTIF_ICONS[n.tipo] || 'fa-bell'}"></i></div>
                <div class="notif-body">
                    <strong>${n.titulo}</strong>
                    <span>${n.mensaje}</span>
                    <small>${n.fecha}</small>
                </div>
                <button class="notif-delete" onclick="event.stopPropagation(); eliminarNotificacion(${n.id})" title="Eliminar">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error loading notifications:', error);
    }
}

async function leerNotificacion(id) {
    try {
        await fetch(`${API_BASE_URL}/api/notificaciones/${id}/leer`, { method: 'POST' });
        loadNotificaciones();
    } catch (error) {
        console.error('Error marking notification:', error);
    }
}

async function marcarTodasLeidas() {
    try {
        await fetch(`${API_BASE_URL}/api/notificaciones/leer-todas`, { method: 'POST' });
        loadNotificaciones();
    } catch (error) {
        console.error('Error marking all notifications:', error);
    }
}

async function eliminarNotificacion(id) {
    try {
        await fetch(`${API_BASE_URL}/api/notificaciones/${id}`, { method: 'DELETE' });
        loadNotificaciones();
    } catch (error) {
        console.error('Error deleting notification:', error);
    }
}

setInterval(() => {
    if (currentUser) loadNotificaciones();
}, 60000);

// OJO (sesión 12): la llamada a iniciarPollingChatSoporte() está al FINAL del
// archivo, DESPUÉS de las declaraciones let de soporte (regla 74: TDZ).

// =====================================================
// SITCAV — STOCK BAJO (modal del dashboard)
// =====================================================
async function openStockBajoModal() {
    const modal = document.getElementById('stock-bajo-modal');
    const list = document.getElementById('stock-bajo-list');
    modal.classList.add('active');
    list.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);">Cargando...</div>';
    try {
        const productos = await fetch(`${API_BASE_URL}/api/productos`).then(r => r.json());
        const bajos = productos.filter(p => p.cantidad_disponible < 10);
        if (bajos.length === 0) {
            list.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--success);"><i class="fas fa-check-circle"></i> Todo el inventario está por encima del mínimo.</div>';
            return;
        }
        list.innerHTML = bajos.map(p => `
            <div class="stock-row">
                <div>
                    <strong>${p.nombre}</strong>
                    <span>${p.codigo} · ${p.categoria_nombre || 'N/A'}</span>
                </div>
                <div class="estado-badge danger">${p.cantidad_disponible} u.</div>
            </div>
        `).join('');
    } catch (error) {
        list.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--danger);">Error al cargar productos</div>';
    }
}

function closeStockBajoModal() {
    document.getElementById('stock-bajo-modal').classList.remove('active');
}

// =====================================================
// SITCAV — SUCURSALES
// =====================================================
async function loadSucursales() {
    const cards = document.getElementById('sucursales-cards');
    cards.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);">Cargando sucursales...</div>';
    try {
        const sucursales = await fetch(`${API_BASE_URL}/api/sucursales`).then(r => r.json());
        cards.innerHTML = '';
        if (sucursales.length === 0) {
            cards.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);">No hay sucursales registradas</div>';
            return;
        }
        sucursales.forEach(s => {
            const card = document.createElement('div');
            card.className = 'suc-card';
            card.innerHTML = `
                <div class="suc-header">
                    <div class="suc-logo"><i class="fas ${s.es_principal ? 'fa-home' : 'fa-store-alt'}"></i></div>
                    <div>
                        <h4>${s.nombre}</h4>
                        ${s.es_principal ? '<span class="suc-badge">Principal</span>' : ''}
                    </div>
                </div>
                <div class="suc-body">
                    ${s.direccion ? `<p><i class="fas fa-map-marker-alt"></i> ${s.direccion}</p>` : ''}
                    ${s.telefono ? `<p><i class="fas fa-phone"></i> ${s.telefono}</p>` : ''}
                    <p class="suc-stock"><i class="fas fa-boxes"></i> ${s.total_unidades} unidades · ${s.total_productos} productos</p>
                </div>
                <div class="suc-actions">
                    <button class="btn btn-outline" onclick="openStockSucursalModal(${s.id}, '${s.nombre.replace(/'/g, '')}')">
                        <i class="fas fa-boxes"></i> Ver Stock
                    </button>
                    ${!s.es_principal && currentUser.rol === 'Administrador' ? `
                        <button class="btn btn-outline" onclick="openSucursalModal(${s.id})"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-outline danger" onclick="deleteSucursal(${s.id})"><i class="fas fa-trash"></i></button>
                    ` : ''}
                </div>
            `;
            cards.appendChild(card);
        });
    } catch (error) {
        cards.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--danger);">Error al cargar sucursales</div>';
    }
    loadTransferencias();
}

async function openSucursalModal(id = null) {
    const modal = document.getElementById('sucursal-modal');
    document.getElementById('sucursal-id').value = id || '';
    document.getElementById('sucursal-nombre').value = '';
    document.getElementById('sucursal-direccion').value = '';
    document.getElementById('sucursal-telefono').value = '';
    document.getElementById('sucursal-principal').checked = false;

    if (id) {
        document.getElementById('sucursal-modal-title').innerHTML = '<i class="fas fa-store-alt"></i> Editar Sucursal';
        const sucursales = await fetch(`${API_BASE_URL}/api/sucursales`).then(r => r.json());
        const s = sucursales.find(x => x.id === id);
        if (s) {
            document.getElementById('sucursal-nombre').value = s.nombre;
            document.getElementById('sucursal-direccion').value = s.direccion || '';
            document.getElementById('sucursal-telefono').value = s.telefono || '';
            document.getElementById('sucursal-principal').checked = !!s.es_principal;
            document.getElementById('sucursal-principal').disabled = !!s.es_principal;
        }
    } else {
        document.getElementById('sucursal-modal-title').innerHTML = '<i class="fas fa-store-alt"></i> Nueva Sucursal';
        document.getElementById('sucursal-principal').disabled = false;
    }
    modal.classList.add('active');
}

function closeSucursalModal() {
    document.getElementById('sucursal-modal').classList.remove('active');
}

async function saveSucursal(event) {
    event.preventDefault();
    const id = document.getElementById('sucursal-id').value;
    const payload = {
        nombre: document.getElementById('sucursal-nombre').value,
        direccion: document.getElementById('sucursal-direccion').value,
        telefono: document.getElementById('sucursal-telefono').value,
        es_principal: document.getElementById('sucursal-principal').checked
    };

    try {
        const response = await fetch(`${API_BASE_URL}/api/sucursales${id ? '/' + id : ''}`, {
            method: id ? 'PUT' : 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await response.json();

        if (response.ok) {
            alert(id ? 'Sucursal actualizada exitosamente' : 'Sucursal creada exitosamente');
            closeSucursalModal();
            loadSucursales();
        } else {
            alert(data.message || 'Error al guardar sucursal');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function deleteSucursal(id) {
    if (!(await confirmAsync('¿Está seguro de eliminar esta sucursal? El inventario asignado se perderá.'))) return;
    try {
        const response = await fetch(`${API_BASE_URL}/api/sucursales/${id}`, { method: 'DELETE' });
        const data = await response.json();
        if (response.ok) {
            alert('Sucursal eliminada');
            loadSucursales();
        } else {
            alert(data.message || 'Error al eliminar sucursal');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function openStockSucursalModal(id, nombre) {
    const modal = document.getElementById('stock-sucursal-modal');
    const list = document.getElementById('stock-sucursal-list');
    document.getElementById('stock-sucursal-title').innerHTML = `<i class="fas fa-boxes"></i> Stock de ${nombre}`;
    modal.classList.add('active');
    list.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);">Cargando...</div>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/sucursales/${id}/stock`).then(r => r.json());
        if (!data.stock || data.stock.length === 0) {
            list.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);">Sin productos asignados</div>';
            return;
        }
        list.innerHTML = data.stock.map(f => `
            <div class="stock-row">
                <div>
                    <strong>${f.producto}</strong>
                    <span>$${f.precio.toFixed(2)} USD</span>
                </div>
                <div class="estado-badge ${f.cantidad < 10 ? 'danger' : 'success'}">${f.cantidad} u.</div>
            </div>
        `).join('');
    } catch (error) {
        list.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--danger);">Error al cargar stock</div>';
    }
}

function closeStockSucursalModal() {
    document.getElementById('stock-sucursal-modal').classList.remove('active');
}

// =====================================================
// SITCAV — TRANSFERENCIAS DE INVENTARIO
// =====================================================
let sucursalesCache = [];

async function loadTransferencias() {
    const tbody = document.getElementById('transferencias-table-body');
    if (!tbody) return;
    tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px;">Cargando transferencias...</td></tr>';
    try {
        const transferencias = await fetch(`${API_BASE_URL}/api/transferencias`).then(r => r.json());
        if (transferencias.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px;">Sin transferencias registradas</td></tr>';
            return;
        }
        tbody.innerHTML = '';
        transferencias.forEach(t => {
            const estadoBadge = t.estado === 'completada'
                ? '<span class="badge success">Completada</span>'
                : t.estado === 'cancelada'
                    ? '<span class="badge danger">Cancelada</span>'
                    : '<span class="badge warning">Pendiente</span>';
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>#${t.id}</td>
                <td>${t.origen}</td>
                <td>${t.destino}</td>
                <td>${t.fecha_creacion}</td>
                <td>${estadoBadge}</td>
                <td>${t.detalles.map(d => `${d.producto_nombre} ×${d.cantidad}`).join('<br>') || '-'}</td>
                <td>
                    <div class="action-btns">
                        ${t.estado === 'pendiente' && currentUser.rol === 'Administrador' ? `
                            <button class="action-btn success" onclick="recibirTransferencia(${t.id})" title="Confirmar recepción">
                                <i class="fas fa-check-double"></i>
                            </button>
                            <button class="action-btn warning" onclick="cancelarTransferencia(${t.id})" title="Cancelar transferencia (el stock vuelve al origen)">
                                <i class="fas fa-ban"></i>
                            </button>
                        ` : ''}
                        ${currentUser.rol === 'Administrador' ? `
                            <button class="action-btn delete" onclick="eliminarTransferencia(${t.id})" title="Eliminar transferencia (si está pendiente, el stock vuelve al origen)">
                                <i class="fas fa-trash"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            `;
            tbody.appendChild(tr);
        });
    } catch (error) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: var(--danger);">Error al cargar transferencias</td></tr>';
    }
}

async function openTransferenciaModal() {
    const modal = document.getElementById('transferencia-modal');
    sucursalesCache = await fetch(`${API_BASE_URL}/api/sucursales`).then(r => r.json());
    const origen = document.getElementById('transferencia-origen');
    const destino = document.getElementById('transferencia-destino');
    origen.innerHTML = '<option value="">Seleccione origen...</option>' + sucursalesCache.map(s => `<option value="${s.id}">${s.nombre}</option>`).join('');
    destino.innerHTML = '<option value="">Seleccione destino...</option>' + sucursalesCache.map(s => `<option value="${s.id}">${s.nombre}</option>`).join('');
    document.getElementById('transferencia-notas').value = '';
    document.getElementById('transferencia-simple').checked = true;
    document.getElementById('transferencia-productos-list').innerHTML =
        '<div style="text-align: center; padding: 20px; color: var(--muted); font-size: .85em;">Seleccione el origen para ver productos</div>';
    modal.classList.add('active');
}

function closeTransferenciaModal() {
    document.getElementById('transferencia-modal').classList.remove('active');
}

async function cargarProductosTransferencia() {
    const idOrigen = document.getElementById('transferencia-origen').value;
    const destinoSelect = document.getElementById('transferencia-destino');
    const lista = document.getElementById('transferencia-productos-list');
    if (!idOrigen) {
        lista.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--muted); font-size: .85em;">Seleccione el origen para ver productos</div>';
        return;
    }
    destinoSelect.innerHTML = '<option value="">Seleccione destino...</option>' +
        sucursalesCache.filter(s => s.id !== parseInt(idOrigen)).map(s => `<option value="${s.id}">${s.nombre}</option>`).join('');

    lista.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--muted);">Cargando stock...</div>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/sucursales/${idOrigen}/stock`).then(r => r.json());
        const conStock = (data.stock || []).filter(f => f.cantidad > 0);
        if (conStock.length === 0) {
            lista.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--muted);">Sin stock disponible en el origen</div>';
            return;
        }
        lista.innerHTML = conStock.map(f => `
            <div class="stock-row">
                <div>
                    <strong>${f.producto}</strong>
                    <span>Disponible: ${f.cantidad} u.</span>
                </div>
                <input type="number" min="0" max="${f.cantidad}" value="0" id="tfr-qty-${f.id_producto}"
                    style="width: 90px; padding: 6px 8px; border: 1px solid var(--border); border-radius: 8px;">
            </div>
        `).join('');
    } catch (error) {
        lista.innerHTML = '<div style="text-align: center; padding: 20px; color: var(--danger);">Error al cargar stock</div>';
    }
}

async function saveTransferencia(event) {
    event.preventDefault();

    const idOrigen = parseInt(document.getElementById('transferencia-origen').value);
    const idDestino = parseInt(document.getElementById('transferencia-destino').value);
    const notas = document.getElementById('transferencia-notas').value;
    const simple = document.getElementById('transferencia-simple').checked;

    if (!idOrigen || !idDestino) {
        alert('Seleccione origen y destino');
        return;
    }
    if (idOrigen === idDestino) {
        alert('Origen y destino deben ser distintos');
        return;
    }

    const productos = [];
    document.querySelectorAll('#transferencia-productos-list input[type=number]').forEach(input => {
        const cantidad = parseInt(input.value || '0');
        if (cantidad > 0) productos.push({ id_producto: parseInt(input.id.replace('tfr-qty-', '')), cantidad });
    });

    if (productos.length === 0) {
        alert('Seleccione al menos un producto con cantidad mayor a 0');
        return;
    }

    const payload = {
        id_origen: idOrigen,
        id_destino: idDestino,
        id_usuario: currentUser ? currentUser.id : null,
        estado: simple ? 'completada' : 'pendiente',
        notas,
        productos
    };

    try {
        const response = await fetch(`${API_BASE_URL}/api/transferencias`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const data = await response.json();

        if (response.ok) {
            alert('Transferencia creada exitosamente');
            closeTransferenciaModal();
            loadSucursales();
            loadNotificaciones();
        } else {
            alert(data.message || 'Error al crear transferencia');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function recibirTransferencia(id) {
    if (!(await confirmAsync('¿Confirmar la recepción? El stock se sumará al destino.'))) return;
    try {
        const response = await fetch(`${API_BASE_URL}/api/transferencias/${id}/recibir`, { method: 'POST' });
        const data = await response.json();
        if (response.ok) {
            alert('Recepción confirmada');
            loadTransferencias();
            loadSucursales();
            loadNotificaciones();
        } else {
            alert(data.message || 'Error al recibir');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function cancelarTransferencia(id) {
    if (!(await confirmAsync('¿Cancelar la transferencia? El stock volverá al origen.'))) return;
    try {
        const response = await fetch(`${API_BASE_URL}/api/transferencias/${id}/cancelar`, { method: 'POST' });
        const data = await response.json();
        if (response.ok) {
            alert('Transferencia cancelada');
            loadTransferencias();
            loadSucursales();
            loadNotificaciones();
        } else {
            alert(data.message || 'Error al cancelar');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function eliminarTransferencia(id) {
    if (!(await confirmAsync('¿Eliminar la transferencia del historial? Si está pendiente, el stock volverá al origen.'))) return;
    try {
        const response = await fetch(`${API_BASE_URL}/api/transferencias/${id}`, { method: 'DELETE' });
        const data = await response.json();
        if (response.ok) {
            alert('Transferencia eliminada');
            loadTransferencias();
            loadSucursales();
            loadNotificaciones();
        } else {
            alert(data.message || 'Error al eliminar');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

// =====================================================
// SESIÓN 10 — BUZÓN DE COMENTARIOS (preguntas + valoraciones)
// =====================================================
async function loadBuzonPreguntas() {
    const cont = document.getElementById('buzon-preguntas');
    if (!cont) return;
    cont.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);"><i class="fas fa-spinner fa-spin"></i> Cargando preguntas...</div>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/preguntas`).then(r => r.json());
        const preguntas = data.preguntas || [];
        if (preguntas.length === 0) {
            cont.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);"><i class="fas fa-inbox"></i> No hay preguntas de clientes.</div>';
            return;
        }
        cont.innerHTML = '';
        preguntas.forEach(q => {
            const card = document.createElement('div');
            card.className = 'qa-item';
            card.innerHTML = `
                <div class="q"><i class="fas fa-user"></i> ${q.nombre_cliente || 'Cliente'} · <i class="fas fa-box"></i> ${q.nombre_producto || 'Producto'} ${q.estado === 'pendiente' ? '<span class="badge warn" style="margin-left:8px;">Pendiente</span>' : '<span class="badge resuelto" style="margin-left:8px;">Respondida</span>'}</div>
                <div class="q">${q.pregunta}</div>
                <div class="meta">${q.fecha_pregunta || ''}</div>
                ${q.respuesta ? `<div class="a"><i class="fas fa-reply"></i> ${q.respuesta} <span class="meta">${q.fecha_respuesta || ''}</span></div>` : `
                <div class="resp-box">
                    <textarea id="resp-pregunta-${q.id}" rows="2" placeholder="Escribe la respuesta para el cliente..."></textarea>
                    <button class="btn btn-primary" onclick="responderPregunta(${q.id})"><i class="fas fa-paper-plane"></i> Responder</button>
                </div>`}
            `;
            cont.appendChild(card);
        });
    } catch (error) {
        cont.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--danger);">Error al cargar preguntas</div>';
    }
}

async function responderPregunta(id) {
    const textarea = document.getElementById(`resp-pregunta-${id}`);
    const respuesta = textarea.value.trim();
    if (respuesta.length < 3) { alert('Escribe una respuesta'); return; }
    try {
        const response = await fetch(`${API_BASE_URL}/api/preguntas/${id}/responder`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ respuesta })
        });
        const data = await response.json();
        if (response.ok) {
            alert('Respuesta enviada al cliente');
            loadBuzonPreguntas();
        } else {
            alert(data.message || 'Error al responder');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function loadBuzonValoraciones() {
    const cont = document.getElementById('buzon-valoraciones');
    if (!cont) return;
    cont.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);"><i class="fas fa-spinner fa-spin"></i> Cargando valoraciones...</div>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/valoraciones`).then(r => r.json());
        const valoraciones = data.valoraciones || [];
        if (valoraciones.length === 0) {
            cont.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--muted);"><i class="fas fa-star"></i> Aún no hay valoraciones.</div>';
            return;
        }
        cont.innerHTML = '';
        valoraciones.forEach(v => {
            const card = document.createElement('div');
            card.className = 'val-item';
            card.innerHTML = `
                <div class="name"><i class="fas fa-user"></i> ${v.nombre_cliente || 'Cliente'} · <i class="fas fa-box"></i> ${v.nombre_producto || 'Producto'}</div>
                <div class="stars-display" style="color:#f59e0b;">${'★'.repeat(v.estrellas)}${'☆'.repeat(5 - v.estrellas)}</div>
                ${v.comentario ? `<div class="comment">${v.comentario}</div>` : ''}
                <div class="meta">${v.fecha || ''}</div>
            `;
            cont.appendChild(card);
        });
    } catch (error) {
        cont.innerHTML = '<div style="text-align: center; padding: 30px; color: var(--danger);">Error al cargar valoraciones</div>';
    }
}

// =====================================================
// SESIÓN 10 — SOPORTE: CHATS EN VIVO (personal)
// =====================================================
let soporteConversaciones = [];
let soporteChatActualId = null;
let soporteChatTimer = null;
let soporteWidgetAbierto = false;
let soporteSolicitudesVistas = new Set();

function switchSoporteTab(tab) {
    document.getElementById('tab-soporte-chats').classList.toggle('active', tab === 'chats');
    document.getElementById('tab-soporte-tickets').classList.toggle('active', tab === 'tickets');
    document.getElementById('soporte-chats-pane').style.display = tab === 'chats' ? '' : 'none';
    document.getElementById('soporte-tickets-pane').style.display = tab === 'tickets' ? '' : 'none';
    if (tab === 'chats') loadSoporteChats();
    else loadSoporteTickets();
}

async function loadSoporteChats() {
    const body = document.getElementById('soporte-chats-body');
    if (!body) return;
    if (!currentUser || ['Administrador', 'Encargado', 'Vendedor'].indexOf(currentUser.rol) === -1) {
        body.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px; color: var(--muted);">Inicia sesión como personal para ver las conversaciones de soporte.</td></tr>';
        return;
    }
    body.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px;"><i class="fas fa-spinner fa-spin"></i> Cargando conversaciones...</td></tr>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/chat/soporte?cedula=${encodeURIComponent(currentUser.cedula)}`).then(r => r.json());
        const conversaciones = data.conversaciones || [];
        soporteConversaciones = conversaciones;
        if (conversaciones.length === 0) {
            body.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px; color: var(--muted);">No hay conversaciones aún. Los clientes pueden iniciar una desde el chat flotante de la tienda.</td></tr>';
            return;
        }
        body.innerHTML = '';
        conversaciones.forEach(c => {
            const tr = document.createElement('tr');
            const badge = c.estado === 'solicitada' ? '<span class="badge warn">Solicitada</span>'
                : c.estado === 'activa' ? '<span class="badge proceso">Activa</span>'
                : c.estado === 'finalizada' ? '<span class="badge cerrado">Finalizada</span>'
                : '<span class="badge resuelto">Calificada</span>';
            const calif = c.estado === 'calificada' ? `${'★'.repeat(c.calificacion || 0)}${'☆'.repeat(5 - (c.calificacion || 0))}${c.comentario_calificacion ? ' · ' + c.comentario_calificacion : ''}` : '—';
            tr.innerHTML = `
                <td>#${c.id}</td>
                <td><strong>${c.nombre_cliente || ''}</strong><br><small style="color:var(--muted);">${c.cedula || ''}</small></td>
                <td>${badge}</td>
                <td>${c.nombre_agente || '—'}</td>
                <td>${(c.mensajes || []).length}</td>
                <td>${calif}</td>
                <td><button class="btn btn-primary" style="padding:6px 12px; font-size:.78rem;" onclick="abrirChatSoporte(${c.id})"><i class="fas fa-comments"></i> ${c.estado === 'solicitada' ? 'Aceptar' : 'Ver'}</button></td>
            `;
            body.appendChild(tr);
        });
    } catch (error) {
        body.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px; color: var(--danger);">Error al cargar conversaciones</td></tr>';
    }
}

function abrirChatSoporte(id) {
    const select = document.getElementById('chat-soporte-select');
    select.value = String(id);
    chatSoporteSeleccionar(String(id));
    toggleChatSoporteWidget(true);
}

function toggleChatSoporteWidget(forzar) {
    if (!currentUser || ['Administrador', 'Encargado', 'Vendedor'].indexOf(currentUser.rol) === -1) return;
    soporteWidgetAbierto = forzar !== undefined ? forzar : !soporteWidgetAbierto;
    document.getElementById('chat-soporte-window').classList.toggle('open', soporteWidgetAbierto);
    if (soporteWidgetAbierto) {
        refrescarChatSoporte();
        const conv = soporteConversaciones.find(c => c.id === soporteChatActualId);
        if (conv && (conv.estado === 'activa' || conv.estado === 'solicitada')) marcarLeidoChatSoporte(conv.id);
        soporteConversaciones.forEach(c => { if (c.estado === 'solicitada') soporteSolicitudesVistas.add(c.id); });
    }
}

function chatSoporteSeleccionar(id) {
    soporteChatActualId = id ? parseInt(id) : null;
    renderChatSoporte();
}

function actualizarBadgeChatSoporte() {
    const badge = document.getElementById('chat-soporte-badge');
    if (!badge) return;
    let pendientes = 0;
    soporteConversaciones.forEach(c => {
        if (c.estado === 'solicitada') pendientes += 1;
        else if (c.estado === 'activa' && c.no_leidos) pendientes += c.no_leidos;
    });
    badge.style.display = pendientes > 0 ? 'flex' : 'none';
    badge.textContent = pendientes > 9 ? '9+' : pendientes;
}

function renderChatSoporte() {
    const body = document.getElementById('chat-soporte-body');
    const foot = document.getElementById('chat-soporte-foot');
    const actions = document.getElementById('chat-soporte-actions');
    const estado = document.getElementById('chat-soporte-estado');
    body.innerHTML = '';
    foot.style.display = 'none';
    actions.innerHTML = '';
    const select = document.getElementById('chat-soporte-select');
    select.innerHTML = '<option value="">— Seleccionar conversación —</option>';
    soporteConversaciones.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = `#${c.id} ${c.nombre_cliente || ''} (${c.estado})`;
        select.appendChild(opt);
    });
    if (soporteChatActualId) select.value = String(soporteChatActualId);

    const c = soporteConversaciones.find(x => x.id === soporteChatActualId);
    if (!c) {
        estado.textContent = 'Selecciona una conversación';
        body.innerHTML = '<div class="chat-center"><div class="pill"><i class="fas fa-comments"></i> Las solicitudes de los clientes aparecerán aquí en tiempo real.</div></div>';
        return;
    }
    estado.textContent = `${c.nombre_cliente || ''} · ${c.estado}`;
    (c.mensajes || []).forEach(m => {
        const div = document.createElement('div');
        div.className = 'chat-msg ' + (m.emisor === 'agente' ? 'agente' : 'cliente');
        div.innerHTML = `${m.contenido}<span class="hora">${m.fecha ? m.fecha.slice(11, 16) : ''}${m.emisor === 'agente' ? ' · ' + (c.nombre_agente || 'Agente') : ' · Cliente'}</span>`;
        body.appendChild(div);
    });
    body.scrollTop = body.scrollHeight;

    const esMiChat = c.id_agente === currentUser.id || (c.estado !== 'solicitada' && c.estado !== 'activa');
    if (c.estado === 'solicitada') {
        actions.innerHTML = '<button class="btn btn-primary" style="width:100%;" onclick="aceptarChatSoporte()"><i class="fas fa-check-circle"></i> Aceptar y atender esta conversación</button>';
        body.innerHTML += '<div class="chat-center"><div class="pill"><i class="fas fa-hourglass-half"></i> Solicitud esperando ser aceptada</div></div>';
    } else if (c.estado === 'activa') {
        if (esMiChat) {
            foot.style.display = 'flex';
            actions.innerHTML = '<button class="btn btn-outline" onclick="finalizarChatSoporte()"><i class="fas fa-flag-checkered"></i> Finalizar conversación</button>';
        } else {
            actions.innerHTML = '<div class="chat-center"><div class="pill"><i class="fas fa-user-shield"></i> Esta conversación la atiende ' + (c.nombre_agente || 'otro miembro del personal') + '</div></div>';
        }
    } else if (c.estado === 'calificada') {
        body.innerHTML += '<div class="chat-center"><div class="pill"><i class="fas fa-star"></i> Cliente calificó: ' + '★'.repeat(c.calificacion || 0) + (c.comentario_calificacion ? ' — ' + c.comentario_calificacion : '') + '</div></div>';
    }
}

async function refrescarChatSoporte() {
    if (!currentUser || ['Administrador', 'Encargado', 'Vendedor'].indexOf(currentUser.rol) === -1) return;
    try {
        const data = await fetch(`${API_BASE_URL}/api/chat/soporte?cedula=${encodeURIComponent(currentUser.cedula)}`).then(r => r.json());
        soporteConversaciones = data.conversaciones || [];
        actualizarBadgeChatSoporte();
        soporteConversaciones.forEach(c => {
            if (c.estado === 'solicitada' && !soporteSolicitudesVistas.has(c.id)) {
                soporteSolicitudesVistas.add(c.id);
                showToast(`Nueva solicitud de chat #${c.id} de ${c.nombre_cliente || 'un cliente'} — abre el chat de soporte para aceptarla`, 'info');
                const launcher = document.getElementById('chat-soporte-launcher');
                if (launcher) {
                    launcher.classList.add('pulse');
                    setTimeout(() => launcher.classList.remove('pulse'), 4000);
                }
            }
        });
        if (soporteWidgetAbierto) renderChatSoporte();
    } catch (e) { }
}

async function aceptarChatSoporte() {
    if (!soporteChatActualId) return;
    try {
        const response = await fetch(`${API_BASE_URL}/api/chat/${soporteChatActualId}/aceptar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: currentUser.cedula })
        });
        const data = await response.json();
        if (response.ok) {
            alert(data.message || 'Conversación aceptada');
            refrescarChatSoporte();
            loadSoporteChats();
            loadNotificaciones();
        } else {
            alert(data.message || 'Error al aceptar');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function enviarMensajeSoporte() {
    const input = document.getElementById('chat-soporte-input');
    const contenido = input.value.trim();
    if (!contenido || !soporteChatActualId) return;
    input.value = '';
    try {
        const response = await fetch(`${API_BASE_URL}/api/chat/${soporteChatActualId}/mensaje`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: currentUser.cedula, contenido })
        });
        const data = await response.json();
        if (!response.ok) alert(data.message || 'Error al enviar');
        refrescarChatSoporte();
    } catch (error) {
        alert('Error de conexión');
    }
}

async function finalizarChatSoporte() {
    if (!confirm('¿Finalizar esta conversación? El cliente podrá calificar la atención.')) return;
    try {
        const response = await fetch(`${API_BASE_URL}/api/chat/${soporteChatActualId}/finalizar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: currentUser.cedula })
        });
        const data = await response.json();
        if (response.ok) {
            alert(data.message || 'Conversación finalizada');
            refrescarChatSoporte();
            loadSoporteChats();
        } else {
            alert(data.message || 'Error al finalizar');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function marcarLeidoChatSoporte(id) {
    try {
        await fetch(`${API_BASE_URL}/api/chat/${id}/leer`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: currentUser.cedula })
        });
    } catch (e) { }
}

function iniciarPollingChatSoporte() {
    if (soporteChatTimer) clearInterval(soporteChatTimer);
    soporteChatTimer = setInterval(() => {
        if (!currentUser || ['Administrador', 'Encargado', 'Vendedor'].indexOf(currentUser.rol) === -1) return;
        refrescarChatSoporte().then(() => {
            if (soporteWidgetAbierto) {
                const conv = soporteConversaciones.find(c => c.id === soporteChatActualId);
                if (conv && (conv.estado === 'activa' || conv.estado === 'solicitada')) marcarLeidoChatSoporte(conv.id);
            }
        });
    }, 5000);
}

iniciarPollingChatSoporte();

// =====================================================
// SESIÓN 10 — SOPORTE: TICKETS (personal)
// =====================================================
async function loadSoporteTickets() {
    const body = document.getElementById('soporte-tickets-body');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="11" style="text-align: center; padding: 30px;"><i class="fas fa-spinner fa-spin"></i> Cargando tickets...</td></tr>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/tickets?cedula=${encodeURIComponent(currentUser.cedula)}`).then(r => r.json());
        const tickets = data.tickets || [];
        if (tickets.length === 0) {
            body.innerHTML = '<tr><td colspan="11" style="text-align: center; padding: 30px; color: var(--muted);">No hay tickets de clientes.</td></tr>';
            return;
        }
        body.innerHTML = '';
        tickets.forEach(t => {
            const tr = document.createElement('tr');
            const badge = t.estado === 'abierto' ? '<span class="badge warn">Abierto</span>'
                : t.estado === 'en_proceso' ? '<span class="badge proceso">En proceso</span>'
                : t.estado === 'resuelto' ? '<span class="badge resuelto">Resuelto</span>'
                : '<span class="badge cerrado">Cerrado</span>';
            tr.innerHTML = `
                <td>#${t.id}</td>
                <td>${t.fecha_creacion || ''}</td>
                <td><strong>${t.nombre_cliente || ''}</strong></td>
                <td>${t.cedula || ''}</td>
                <td>${t.telefono || t.telefono_cliente || '—'}</td>
                <td>${t.direccion || t.direccion_cliente || '—'}</td>
                <td>${t.categoria || 'General'}</td>
                <td title="${(t.descripcion || '').replace(/"/g, '&quot;')}">${t.asunto || ''}</td>
                <td>${badge}</td>
                <td>${t.nombre_agente || '—'}</td>
                <td style="white-space:nowrap;">
                    <button class="btn btn-primary" style="padding:5px 10px; font-size:.75rem;" onclick="abrirResponderTicket(${t.id})"><i class="fas fa-reply"></i> Responder</button>
                    <select style="margin-top:4px; padding:5px; font-size:.75rem; border:1.5px solid var(--border); border-radius:8px;" onchange="cambiarEstadoTicket(${t.id}, this.value)">
                        <option value="abierto" ${t.estado === 'abierto' ? 'selected' : ''}>Abierto</option>
                        <option value="en_proceso" ${t.estado === 'en_proceso' ? 'selected' : ''}>En proceso</option>
                        <option value="resuelto" ${t.estado === 'resuelto' ? 'selected' : ''}>Resuelto</option>
                        <option value="cerrado" ${t.estado === 'cerrado' ? 'selected' : ''}>Cerrado</option>
                    </select>
                </td>
            `;
            body.appendChild(tr);
        });
    } catch (error) {
        body.innerHTML = '<tr><td colspan="11" style="text-align: center; padding: 30px; color: var(--danger);">Error al cargar tickets</td></tr>';
    }
}

async function abrirResponderTicket(id) {
    const ticket = (await fetch(`${API_BASE_URL}/api/tickets?cedula=${encodeURIComponent(currentUser.cedula)}`).then(r => r.json())).tickets.find(t => t.id === id);
    if (!ticket) return;
    const respuesta = prompt(`Responder al ticket #${id} de ${ticket.nombre_cliente || ''} (${ticket.asunto || ''}):\n\nDescripción: ${ticket.descripcion || ''}`, ticket.respuesta || '');
    if (respuesta === null) return;
    const texto = respuesta.trim();
    if (texto.length < 3) { alert('Escribe una respuesta'); return; }
    try {
        const response = await fetch(`${API_BASE_URL}/api/tickets/${id}/responder`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: currentUser.cedula, respuesta: texto })
        });
        const data = await response.json();
        if (response.ok) {
            alert('Respuesta enviada al cliente');
            loadSoporteTickets();
            loadNotificaciones();
        } else {
            alert(data.message || 'Error al responder');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}

async function cambiarEstadoTicket(id, estado) {
    try {
        const response = await fetch(`${API_BASE_URL}/api/tickets/${id}/estado`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: currentUser.cedula, estado })
        });
        const data = await response.json();
        if (response.ok) {
            alert(data.message || 'Estado actualizado');
            loadSoporteTickets();
        } else {
            alert(data.message || 'Error al actualizar estado');
        }
    } catch (error) {
        alert('Error de conexión');
    }
}
