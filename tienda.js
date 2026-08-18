// =====================================================
// SITCAV TIENDA ONLINE — storefront (estilo Amazon)
// =====================================================
// ONLINE: el frontend vive en tu web gratuita (GitHub Pages / aimee.42web.io)
// y el API en PythonAnywhere. REEMPLAZA Laikimist por tu usuario real de
// PythonAnywhere (sin / final):
const API_BASE_URL = 'https://Laikimist.pythonanywhere.com';
const TIENDA_USER_KEY = 'sitcav_tienda_user';
const TIENDA_TOKEN_KEY = 'sitcav_token';

// Sesión online: inyecta el token Bearer en TODAS las peticiones.
// Si el token expiró (401), cierra la sesión del cliente.
const __origFetch = window.fetch;

window.fetch = function (url, opts) {
    opts = opts || {};
    opts.headers = opts.headers || {};
    const token = localStorage.getItem(TIENDA_TOKEN_KEY);
    if (token) opts.headers['Authorization'] = 'Bearer ' + token;
    return __origFetch(url, opts).then(function (res) {
        if (res.status === 401 && token && !String(url).includes('/login')) {
            localStorage.removeItem(TIENDA_TOKEN_KEY);
            localStorage.removeItem(TIENDA_USER_KEY);
            tiendaUser = null;
            renderAuth();
        }
        return res;
    });
};

let tiendaUser = null;
let catalogo = [];
let tasaActual = 35.5;
let cotizacionCaducada = false;
let captchaToken = null;
let apartarProducto = null;

// =====================================================
// TOASTS
// =====================================================
function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `<i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>${message}`;
    container.appendChild(toast);
    setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity .3s'; setTimeout(() => toast.remove(), 320); }, 4000);
}

function alertTienda(message) {
    showToast(message, 'info');
}

// =====================================================
// SESIÓN
// =====================================================
function loadTiendaUser() {
    try {
        tiendaUser = JSON.parse(localStorage.getItem(TIENDA_USER_KEY));
    } catch (e) {
        tiendaUser = null;
    }
    renderAuth();
}

function renderAuth() {
    const authButtons = document.getElementById('auth-buttons');
    const chip = document.getElementById('user-chip');
    if (tiendaUser) {
        authButtons.style.display = 'none';
        chip.style.display = 'flex';
        document.getElementById('chip-name').textContent = `${tiendaUser.nombre || ''} ${tiendaUser.apellidos || ''}`.trim() || tiendaUser.cedula;
    } else {
        authButtons.style.display = '';
        chip.style.display = 'none';
    }
}

function logoutTienda() {
    try { fetch(`${API_BASE_URL}/logout`, { method: 'POST' }); } catch (e) { /* se limpia la sesión local igualmente */ }
    tiendaUser = null;
    localStorage.removeItem(TIENDA_USER_KEY);
    localStorage.removeItem(TIENDA_TOKEN_KEY);
    renderAuth();
    showToast('Sesión cerrada', 'info');
}

// =====================================================
// CATÁLOGO
// =====================================================
async function cargarCatalogo() {
    try {
        const data = await fetch(`${API_BASE_URL}/api/tienda/productos`).then(r => r.json());
        catalogo = data.productos || [];
        tasaActual = data.cotizacion ? data.cotizacion.tasa : 35.5;
        cotizacionCaducada = data.cotizacion ? data.cotizacion.caducada : false;

        const badge = document.getElementById('tasa-badge');
        badge.innerHTML = cotizacionCaducada
            ? 'Cotización CADUCADA — precios por confirmar<strong id="tasa-valor">Bs ' + tasaActual.toLocaleString('es-VE', { maximumFractionDigits: 2 }) + '</strong>'
            : 'Cotización vigente (24h)<strong id="tasa-valor">Bs ' + tasaActual.toLocaleString('es-VE', { maximumFractionDigits: 2 }) + '</strong>';
        if (cotizacionCaducada) badge.classList.add('warning');
        renderProductos();
        renderDestacados();
    } catch (error) {
        document.getElementById('productos-grid').innerHTML = '<div class="empty"><i class="fas fa-plug"></i> No se pudo conectar con el servidor. Asegúrate de que SITCAV esté iniciado.</div>';
    }
}

function renderProductos() {
    const grid = document.getElementById('productos-grid');
    const filtro = (document.getElementById('tienda-search').value || '').toLowerCase();
    const visibles = catalogo.filter(p => p.nombre.toLowerCase().includes(filtro));

    if (visibles.length === 0) {
        grid.innerHTML = '<div class="empty"><i class="fas fa-box-open"></i> No hay productos disponibles</div>';
        return;
    }

    grid.innerHTML = '';
    visibles.forEach(p => {
        const card = document.createElement('div');
        card.className = 'card';

        const stockTag = p.stock === 0
            ? '<span class="stock-tag stock-no">Agotado</span>'
            : p.stock < 10
                ? `<span class="stock-tag stock-bajo">Quedan ${p.stock}</span>`
                : `<span class="stock-tag stock-ok">En stock (${p.stock})</span>`;

        const img = p.imagen_url
            ? `<img src="${p.imagen_url.startsWith('http') ? p.imagen_url : API_BASE_URL + p.imagen_url}" onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'fas fa-box\'>'">`
            : '<i class="fas fa-box"></i>';

        card.innerHTML = `
            <div class="card-img">${img}</div>
            <div class="card-body">
                <h4>${p.nombre}</h4>
                <span class="card-cat">${p.categoria || 'Sin categoría'}${p.cantidad_apartada > 0 ? ' · <i class="fas fa-lock"></i> ' + p.cantidad_apartada + ' apartado(s)' : ''}</span>
                <div class="card-prices">
                    <span class="price-usd">$${p.precio_usd.toFixed(2)}</span>
                    <span class="price-bs">Bs ${(p.precio_bs || 0).toLocaleString('es-VE', { maximumFractionDigits: 2 })}</span>
                </div>
                <div class="card-foot">
                    ${stockTag}
                    <button class="btn btn-primary btn-apartar" ${p.stock === 0 ? 'disabled style="opacity:.5"' : ''} onclick="openApartarModal(${p.id})">
                        <i class="fas fa-hand-holding-usd"></i> Apartar
                    </button>
                </div>
            </div>
        `;
        grid.appendChild(card);
    });
}

// =====================================================
// CARRUSEL DE TENDENCIAS (más vendidos / nuevos)
// =====================================================
let destacados = [];
let destacadoActual = 0;
let destacadoTimer = null;

function renderDestacados() {
    const section = document.getElementById('destacados-section');
    const track = document.getElementById('destacados-track');
    const dots = document.getElementById('destacados-dots');

    destacados = [...catalogo]
        .filter(p => p.stock > 0)
        .sort((a, b) => (b.vendidos || 0) - (a.vendidos || 0) || b.id - a.id)
        .slice(0, 5);

    if (destacados.length < 2) {
        section.style.display = 'none';
        detenerAutoDestacados();
        return;
    }
    section.style.display = '';
    track.innerHTML = '';
    dots.innerHTML = '';

    destacados.forEach((p, i) => {
        const img = p.imagen_url
            ? `<img src="${p.imagen_url.startsWith('http') ? p.imagen_url : API_BASE_URL + p.imagen_url}" onerror="this.onerror=null;this.parentElement.innerHTML='<i class=\'fas fa-box\'></i>'">`
            : '<i class="fas fa-box"></i>';
        const badge = (p.vendidos || 0) > 0
            ? `<span class="slide-badge"><i class="fas fa-fire"></i> Más vendido #${i + 1} · ${p.vendidos} vendido(s)</span>`
            : '<span class="slide-badge"><i class="fas fa-star"></i> Nuevo en SITCAV</span>';
        const stockTag = p.stock === 0 ? 'Agotado' : `En stock (${p.stock})`;

        const slide = document.createElement('div');
        slide.className = 'slide' + (i === 0 ? ' active' : '');
        slide.innerHTML = `
            <div class="slide-info">
                ${badge}
                <h2>${p.nombre}</h2>
                <p>${p.descripcion || (p.categoria ? 'Categoría: ' + p.categoria : '')}</p>
                <div class="slide-prices">
                    <span class="slide-price-usd">$${p.precio_usd.toFixed(2)}</span>
                    <span class="slide-price-bs">Bs ${(p.precio_bs || 0).toLocaleString('es-VE', { maximumFractionDigits: 2 })}</span>
                </div>
                <div class="slide-stock"><i class="fas fa-boxes"></i> ${stockTag}${p.cantidad_apartada > 0 ? ' · ' + p.cantidad_apartada + ' apartado(s)' : ''}</div>
                <button class="btn btn-light" onclick="openApartarModal(${p.id})"><i class="fas fa-hand-holding-usd"></i> Apartar ahora</button>
            </div>
            <div class="slide-img">${img}</div>
        `;
        track.appendChild(slide);

        const dot = document.createElement('button');
        dot.className = i === 0 ? 'active' : '';
        dot.title = `Ir al producto ${i + 1}`;
        dot.onclick = () => irADestacado(i);
        dots.appendChild(dot);
    });

    destacadoActual = 0;
    iniciarAutoDestacados();
}

function irADestacado(i) {
    const slides = document.querySelectorAll('#destacados-track .slide');
    const dots = document.querySelectorAll('#destacados-dots button');
    if (slides.length === 0) return;
    if (i < 0) i = slides.length - 1;
    if (i >= slides.length) i = 0;
    destacadoActual = i;
    slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
    dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
}

function slideDestacado(dir) {
    irADestacado(destacadoActual + dir);
}

function iniciarAutoDestacados() {
    detenerAutoDestacados();
    const slider = document.getElementById('destacados-slider');
    destacadoTimer = setInterval(() => irADestacado(destacadoActual + 1), 5000);
    slider.onmouseenter = detenerAutoDestacados;
    slider.onmouseleave = iniciarAutoDestacados;
}

function detenerAutoDestacados() {
    if (destacadoTimer) {
        clearInterval(destacadoTimer);
        destacadoTimer = null;
    }
}

// =====================================================
// MODALES
// =====================================================
function openModal(id) { document.getElementById(id).classList.add('open'); }
function closeModal(id) { document.getElementById(id).classList.remove('open'); }

document.querySelectorAll('.modal').forEach(m => {
    m.addEventListener('click', (e) => { if (e.target === m) m.classList.remove('open'); });
});

// =====================================================
// AUTH: LOGIN / REGISTRO
// =====================================================
function openAuthModal(mode) {
    document.getElementById('auth-error').classList.remove('show');
    switchAuthMode(mode);
    openModal('auth-modal');
}

function switchAuthMode(mode) {
    const isLogin = mode === 'login';
    document.getElementById('auth-login-form').style.display = isLogin ? '' : 'none';
    document.getElementById('auth-register-form').style.display = isLogin ? 'none' : '';
    document.getElementById('auth-modal-title').innerHTML = isLogin ? '<i class="fas fa-sign-in-alt"></i> Iniciar Sesión' : '<i class="fas fa-user-plus"></i> Crear Cuenta';
    document.getElementById('auth-error').classList.remove('show');
    if (!isLogin) cargarCaptcha();
}

function mostrarErrorAuth(msg) {
    const el = document.getElementById('auth-error');
    el.textContent = msg;
    el.classList.add('show');
}

async function tiendaLogin() {
    const cedula = document.getElementById('auth-cedula').value.trim();
    const contrasena = document.getElementById('auth-password').value;

    if (!cedula || !contrasena) { mostrarErrorAuth('Ingrese cédula y contraseña'); return; }

    try {
        const response = await fetch(`${API_BASE_URL}/api/tienda/login`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula, contrasena })
        });
        const data = await response.json();

        if (response.ok && data.rol === 'Cliente online') {
            tiendaUser = data;
            localStorage.setItem(TIENDA_USER_KEY, JSON.stringify(tiendaUser));
            if (data.token) localStorage.setItem(TIENDA_TOKEN_KEY, data.token);
            renderAuth();
            closeModal('auth-modal');
            showToast(`Bienvenido(a), ${data.nombre}!`, 'success');
            document.getElementById('auth-password').value = '';
        } else {
            mostrarErrorAuth(data.message || 'Credenciales inválidas');
        }
    } catch (error) {
        mostrarErrorAuth('Error de conexión con el servidor');
    }
}

async function cargarCaptcha() {
    try {
        const data = await fetch(`${API_BASE_URL}/api/tienda/captcha`).then(r => r.json());
        captchaToken = data.token;
        document.getElementById('captcha-img').src = data.svg;
        document.getElementById('captcha-respuesta').value = '';
    } catch (error) {
        showToast('No se pudo cargar el captcha', 'error');
    }
}

async function tiendaRegistro() {
    const nombre = document.getElementById('reg-nombre').value.trim();
    const apellidos = document.getElementById('reg-apellidos').value.trim();
    const cedula = document.getElementById('reg-cedula').value.trim();
    const telefono = document.getElementById('reg-telefono').value.trim();
    const contrasena = document.getElementById('reg-password').value;
    const confirmar = document.getElementById('reg-password-confirm').value;
    const terminos = document.getElementById('reg-terminos').checked;
    const captchaRespuesta = document.getElementById('captcha-respuesta').value.trim();

    if (!nombre || !apellidos) { mostrarErrorAuth('Ingrese nombre y apellidos'); return; }
    if (!cedula) { mostrarErrorAuth('La cédula es obligatoria'); return; }
    if (contrasena !== confirmar) { mostrarErrorAuth('Las contraseñas no coinciden'); return; }
    if (contrasena.length < 8 || !/[A-Z]/.test(contrasena) || !/[a-z]/.test(contrasena) || !/[^A-Za-z0-9]/.test(contrasena)) {
        mostrarErrorAuth('La contraseña debe tener 8+ caracteres con mayúscula, minúscula y carácter especial');
        return;
    }
    if (!terminos) { mostrarErrorAuth('Debe aceptar los Términos y Condiciones'); return; }
    if (!captchaToken || !captchaRespuesta) { mostrarErrorAuth('Complete el captcha'); return; }

    try {
        const response = await fetch(`${API_BASE_URL}/api/tienda/registro`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                nombre, apellidos, cedula, telefono, contrasena,
                terminos_aceptados: terminos,
                captcha_token: captchaToken,
                captcha_respuesta: captchaRespuesta
            })
        });
        const data = await response.json();

        if (response.ok) {
            showToast(data.message || 'Cuenta creada exitosamente', 'success');
            document.getElementById('reg-password').value = '';
            document.getElementById('reg-password-confirm').value = '';
            document.getElementById('reg-terminos').checked = false;
            document.getElementById('captcha-respuesta').value = '';
            document.getElementById('auth-cedula').value = cedula;
            switchAuthMode('login');
        } else {
            mostrarErrorAuth(data.message || 'Error al registrarse');
            cargarCaptcha();
        }
    } catch (error) {
        mostrarErrorAuth('Error de conexión con el servidor');
    }
}

// =====================================================
// APARTAR PRODUCTO
// =====================================================
function openApartarModal(productoId) {
    const p = catalogo.find(x => x.id === productoId);
    if (!p) return;
    apartarProducto = p;

    if (!tiendaUser) {
        showToast('Inicia sesión para apartar un producto', 'warning');
        openAuthModal('login');
        return;
    }

    document.getElementById('apartar-producto-info').innerHTML = `
        <div style="display:flex; gap:14px; align-items:center; margin-bottom:16px;">
            <div style="font-size:2rem; color:var(--primary);"><i class="fas fa-box-open"></i></div>
            <div>
                <h4 style="font-size:1.02rem;">${p.nombre}</h4>
                <p style="color:var(--muted); font-size:.88rem;">$${p.precio_usd.toFixed(2)} USD · Bs ${(p.precio_bs || 0).toLocaleString('es-VE', { maximumFractionDigits: 2 })}</p>
                <p style="color:var(--muted); font-size:.82rem;">Días de apartado: ${p.dias_apartado || 90} · Stock disponible: ${p.stock}</p>
            </div>
        </div>`;
    document.getElementById('apartar-cantidad').value = 1;
    document.getElementById('apartar-cantidad').max = p.stock;
    document.getElementById('apartar-abono').value = '0';
    document.getElementById('apartar-error').classList.remove('show');
    openModal('apartar-modal');
}

async function confirmarApartar() {
    if (!tiendaUser || !apartarProducto) return;
    const cantidad = parseInt(document.getElementById('apartar-cantidad').value || '1');
    const abono = parseFloat(document.getElementById('apartar-abono').value || '0');
    const errorEl = document.getElementById('apartar-error');

    errorEl.classList.remove('show');
    if (cantidad < 1) { errorEl.textContent = 'Cantidad inválida'; errorEl.classList.add('show'); return; }
    if (cantidad > apartarProducto.stock) { errorEl.textContent = `Stock disponible: ${apartarProducto.stock}`; errorEl.classList.add('show'); return; }
    if (abono < 0) { errorEl.textContent = 'Abono inválido'; errorEl.classList.add('show'); return; }

    try {
        const response = await fetch(`${API_BASE_URL}/api/tienda/apartar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                cedula: tiendaUser.cedula,
                productos: [{ id_producto: apartarProducto.id, cantidad }],
                abono_inicial: abono
            })
        });
        const data = await response.json();

        if (response.ok) {
            showToast(data.message || 'Producto apartado exitosamente', 'success');
            closeModal('apartar-modal');
            cargarCatalogo();
        } else {
            errorEl.textContent = data.message || 'Error al apartar';
            errorEl.classList.add('show');
            cargarCatalogo();
        }
    } catch (error) {
        errorEl.textContent = 'Error de conexión con el servidor';
        errorEl.classList.add('show');
    }
}

// =====================================================
// MIS APARTADOS
// =====================================================
async function openMisApartados() {
    const list = document.getElementById('apartados-list');
    const errorEl = document.getElementById('apartados-error');
    errorEl.classList.remove('show');

    if (!tiendaUser) {
        showToast('Inicia sesión para ver tus apartados', 'warning');
        openAuthModal('login');
        return;
    }

    openModal('mis-apartados-modal');
    list.innerHTML = '<div class="empty"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';

    try {
        const data = await fetch(`${API_BASE_URL}/api/tienda/apartados?cedula=${encodeURIComponent(tiendaUser.cedula)}`).then(r => r.json());
        if (!data.apartados || data.apartados.length === 0) {
            list.innerHTML = '<div class="empty"><i class="fas fa-box-open"></i> No tienes apartados. ¡Aparta tu primer producto!</div>';
            return;
        }
        list.innerHTML = '';
        data.apartados.forEach(a => {
            const estadoBadge = a.estado === 'activo'
                ? (a.dias_restantes < 5 ? '<span class="badge warn">¡Quedan pocos días!</span>' : '<span class="badge active">Activo</span>')
                : a.estado === 'completado' || a.estado === 'completada'
                    ? '<span class="badge completado">Completado</span>'
                    : '<span class="badge cancelado">Cancelado</span>';

            const card = document.createElement('div');
            card.className = 'apartado-card';
            card.innerHTML = `
                <div class="head">
                    <h4>Apartado #${a.id}</h4>
                    ${estadoBadge}
                </div>
                <p>${(a.productos || []).map(pd => `${pd.producto} ×${pd.cantidad} ($${pd.precio.toFixed(2)})`).join(' · ') || 'Sin detalles'}</p>
                <p>Total: <strong>$${parseFloat(a.monto_total).toFixed(2)}</strong> · Pagado: <strong>$${parseFloat(a.monto_pagado).toFixed(2)}</strong> · Pendiente: <strong>$${parseFloat(a.monto_total - a.monto_pagado).toFixed(2)}</strong></p>
                ${a.estado === 'activo' ? `<p>Fecha límite: ${a.fecha_limite || 'N/A'} · <strong>${a.dias_restantes} día(s) restantes</strong></p>` : ''}
            `;
            list.appendChild(card);
        });
    } catch (error) {
        errorEl.textContent = 'Error al cargar tus apartados';
        errorEl.classList.add('show');
    }
}

// =====================================================
// INIT
// =====================================================
loadTiendaUser();
cargarCatalogo();
setInterval(() => {
    if (document.visibilityState === 'visible') cargarCatalogo();
}, 45000);
