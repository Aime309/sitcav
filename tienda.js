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
    const soporteBtn = document.getElementById('btn-soporte-tienda');
    if (tiendaUser) {
        authButtons.style.display = 'none';
        chip.style.display = 'flex';
        if (soporteBtn) soporteBtn.style.display = '';
        document.getElementById('chip-name').textContent = `${tiendaUser.nombre || ''} ${tiendaUser.apellidos || ''}`.trim() || tiendaUser.cedula;
    } else {
        authButtons.style.display = '';
        chip.style.display = 'none';
        if (soporteBtn) soporteBtn.style.display = 'none';
    }
}

function logoutTienda() {
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
            <div class="card-img" onclick="openProductoModal(${p.id})">${img}</div>
            <div class="card-body">
                <h4 onclick="openProductoModal(${p.id})">${p.nombre}</h4>
                <span class="card-cat">${p.categoria || 'Sin categoría'}${p.cantidad_apartada > 0 ? ' · <i class="fas fa-lock"></i> ' + p.cantidad_apartada + ' apartado(s)' : ''}</span>
                <div>${estrellasHtml(p.estrellas_promedio || 0, p.num_valoraciones || 0)}</div>
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
        slide.onclick = () => openProductoModal(p.id);
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
                <div style="display:flex; gap:10px; flex-wrap:wrap;">
                    <button class="btn btn-light" onclick="event.stopPropagation(); openProductoModal(${p.id})"><i class="fas fa-eye"></i> Ver detalles</button>
                    <button class="btn btn-light" onclick="event.stopPropagation(); openApartarModal(${p.id})"><i class="fas fa-hand-holding-usd"></i> Apartar ahora</button>
                </div>
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
// SESIÓN 10: ESTRELLAS (helpers compartidos)
// =====================================================
function estrellasHtml(promedio, num) {
    promedio = parseFloat(promedio) || 0;
    num = num || 0;
    let html = '<span class="stars-display" title="' + promedio.toFixed(1) + ' de 5">';
    for (let i = 1; i <= 5; i++) {
        const full = promedio >= i - 0.25;
        const half = !full && promedio >= i - 0.75;
        html += '<i class="fas ' + (full ? 'fa-star' : half ? 'fa-star-half-alt' : 'fa-star') + (full || half ? '' : ' off') + '"></i>';
    }
    html += '</span>';
    if (num > 0) html += ' <span class="stars-count">(' + num + ')</span>';
    return html;
}

// =====================================================
// SESIÓN 10: MODAL DE PRODUCTO (detalle + estrellas + QA + recomendados)
// =====================================================
let productoModalActual = null;
let misEstrellasSeleccion = 0;

function openProductoModal(productoId) {
    const p = catalogo.find(x => x.id === productoId);
    if (!p) return;
    productoModalActual = p;
    const apartarBtn = document.getElementById('producto-modal-apartar-btn');
    if (apartarBtn) apartarBtn.style.display = p.stock > 0 ? 'inline-flex' : 'none';
    const content = document.getElementById('producto-modal-content');
    const img = p.imagen_url
        ? '<img src="' + (p.imagen_url.startsWith('http') ? p.imagen_url : API_BASE_URL + p.imagen_url) + '" onerror="this.onerror=null;this.outerHTML=\'<i class=\\\'fas fa-box\\\'></i>\'">'
        : '<i class="fas fa-box"></i>';
    content.innerHTML = `
        <div class="prod-modal-grid">
            <div class="prod-modal-img">${img}</div>
            <div>
                <span class="card-cat">${p.categoria || 'Sin categoría'}</span>
                <h3 style="font-size:1.25rem; margin:4px 0 6px;">${p.nombre}</h3>
                ${estrellasHtml(p.estrellas_promedio || 0, p.num_valoraciones || 0)}
                <div class="prod-badges">
                    ${(p.vendidos || 0) > 0 ? '<span class="prod-badge vendido"><i class="fas fa-fire"></i> ' + p.vendidos + ' vendido(s)</span>' : '<span class="prod-badge stock-ok2"><i class="fas fa-star"></i> Nuevo</span>'}
                    ${p.stock === 0 ? '<span class="prod-badge stock-bajo2">Agotado</span>' : p.stock < 10 ? '<span class="prod-badge stock-bajo2">Quedan ' + p.stock + '</span>' : '<span class="prod-badge stock-ok2">En stock (' + p.stock + ')</span>'}
                    ${p.cantidad_apartada > 0 ? '<span class="prod-badge stock-bajo2"><i class="fas fa-lock"></i> ' + p.cantidad_apartada + ' apartado(s)</span>' : ''}
                </div>
                <p style="font-size:.9rem; color:var(--muted); margin:8px 0;">${p.descripcion || 'Sin descripción.'}</p>
                <div class="card-prices" style="margin:10px 0;">
                    <span class="price-usd" style="font-size:1.35rem;">$${p.precio_usd.toFixed(2)}</span>
                    <span class="price-bs">Bs ${(p.precio_bs || 0).toLocaleString('es-VE', { maximumFractionDigits: 2 })}</span>
                </div>
                <button class="btn btn-primary" ${p.stock === 0 ? 'disabled style="opacity:.5"' : ''} onclick="openApartarModal(${p.id})" style="width:100%;"><i class="fas fa-hand-holding-usd"></i> Apartar ahora</button>
            </div>
        </div>
        <div class="prod-tabs">
            <button class="prod-tab active" onclick="cambiarPestanaProducto('qa', this)"><i class="fas fa-question-circle"></i> Preguntas (${p.preguntas_count || 0})</button>
            <button class="prod-tab" onclick="cambiarPestanaProducto('val', this)"><i class="fas fa-star"></i> Valoraciones</button>
        </div>
        <div id="prod-qa-pane"></div>
        <div id="prod-val-pane" style="display:none;"></div>
        <div style="margin-top:22px;">
            <h4 style="margin-bottom:12px;"><i class="fas fa-thumbs-up"></i> Productos que podrían interesarte</h4>
            <div class="reco-row" id="recomendados-row"><div class="empty" style="padding:16px;"><i class="fas fa-spinner fa-spin"></i></div></div>
        </div>
    `;
    openModal('producto-modal');
    cargarPreguntasProducto(p.id);
    cargarValoracionesProducto(p.id);
    renderRecomendados(p.id);
}

function cambiarPestanaProducto(pestana, btn) {
    document.querySelectorAll('.prod-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.getElementById('prod-qa-pane').style.display = pestana === 'qa' ? '' : 'none';
    document.getElementById('prod-val-pane').style.display = pestana === 'val' ? '' : 'none';
}

async function cargarPreguntasProducto(productoId) {
    const pane = document.getElementById('prod-qa-pane');
    if (!pane) return;
    pane.innerHTML = '<div class="empty" style="padding:20px;"><i class="fas fa-spinner fa-spin"></i></div>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/tienda/productos/${productoId}/preguntas`).then(r => r.json());
        const preguntas = data.preguntas || [];
        pane.innerHTML = '';
        if (preguntas.length === 0) pane.innerHTML = '<div class="empty" style="padding:16px;"><i class="fas fa-comments"></i> Aún no hay preguntas. ¡Sé el primero en preguntar!</div>';
        preguntas.forEach(q => {
            const item = document.createElement('div');
            item.className = 'qa-item';
            item.innerHTML = `
                <div class="q"><i class="fas fa-user"></i> ${q.nombre_cliente || 'Cliente'}: ${q.pregunta}</div>
                ${q.respuesta ? '<div class="a"><i class="fas fa-reply"></i> ' + q.respuesta + '</div>' : '<div class="a" style="color:var(--muted); font-style:italic;">Esperando respuesta del personal...</div>'}
                <div class="meta">${q.fecha_pregunta || ''}${q.estado === 'respondida' ? ' · Respondida' : ' · Pendiente'}</div>
            `;
            pane.appendChild(item);
        });
        if (tiendaUser) {
            pane.innerHTML += `
                <div class="qa-item" style="background:var(--primary-soft); border-color:transparent;">
                    <label style="font-weight:700; font-size:.88rem; display:block; margin-bottom:6px;">¿Tienes una pregunta sobre este producto?</label>
                    <textarea id="nueva-pregunta" rows="2" style="width:100%; padding:9px 12px; border:1.5px solid var(--border); border-radius:10px; font-family:var(--font); font-size:.88rem; resize:vertical;"></textarea>
                    <button class="btn btn-primary" style="margin-top:8px;" onclick="enviarPregunta(${productoId})"><i class="fas fa-paper-plane"></i> Preguntar</button>
                </div>`;
        } else {
            pane.innerHTML += '<p style="font-size:.82rem; color:var(--muted); text-align:center; padding:8px;"><a href="#" onclick="openAuthModal(\'login\'); return false;" style="color:var(--primary); font-weight:700;">Inicia sesión</a> para hacer preguntas.</p>';
        }
    } catch (e) { pane.innerHTML = '<div class="empty" style="padding:16px;"><i class="fas fa-plug"></i> Error al cargar</div>'; }
}

async function enviarPregunta(productoId) {
    const pregunta = document.getElementById('nueva-pregunta').value.trim();
    if (pregunta.length < 5) { showToast('Escribe tu pregunta (mínimo 5 caracteres)', 'warning'); return; }
    try {
        const res = await fetch(`${API_BASE_URL}/api/tienda/productos/${productoId}/preguntas`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: tiendaUser.cedula, pregunta })
        });
        const data = await res.json();
        if (data.success) { showToast(data.message, 'success'); const el = document.getElementById('nueva-pregunta'); if (el) el.value = ''; await cargarPreguntasProducto(productoId); }
        else showToast(data.message || 'Error', 'warning');
    } catch (e) { showToast('Error de conexión', 'error'); }
}

async function cargarValoracionesProducto(productoId) {
    const pane = document.getElementById('prod-val-pane');
    if (!pane) return;
    pane.innerHTML = '<div class="empty" style="padding:20px;"><i class="fas fa-spinner fa-spin"></i></div>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/tienda/productos/${productoId}/valoraciones`).then(r => r.json());
        const valoraciones = data.valoraciones || [];
        pane.innerHTML = '';
        if (valoraciones.length === 0) pane.innerHTML = '<div class="empty" style="padding:16px;"><i class="fas fa-star"></i> Aún no hay calificaciones.</div>';
        valoraciones.forEach(v => {
            const item = document.createElement('div');
            item.className = 'val-item';
            item.innerHTML = `<div class="name">${v.nombre_cliente || 'Cliente'} <span style="float:right; color:var(--muted); font-size:.72rem;">${v.fecha || ''}</span></div>
                <div class="stars-display">${'★'.repeat(v.estrellas)}${'☆'.repeat(5 - v.estrellas)}</div>
                ${v.comentario ? '<div class="comment">' + v.comentario + '</div>' : ''}`;
            pane.appendChild(item);
        });
        pane.innerHTML += `
            <div class="val-item" style="background:var(--primary-soft); border-color:transparent;">
                <label style="font-weight:700; font-size:.88rem; display:block; margin-bottom:6px;">Califica este producto</label>
                <div class="stars-input" id="stars-input-producto"></div>
                ${tiendaUser ? `<input type="text" id="val-comentario" placeholder="Comentario (opcional)" style="width:100%; margin-top:8px; padding:9px 12px; border:1.5px solid var(--border); border-radius:10px; font-size:.88rem; font-family:var(--font);">
                <button class="btn btn-primary" style="margin-top:8px;" onclick="enviarValoracion(${productoId})"><i class="fas fa-star"></i> Enviar calificación</button>`
                : '<p style="font-size:.82rem; color:var(--muted); margin-top:6px;"><a href="#" onclick="openAuthModal(\'login\'); return false;" style="color:var(--primary); font-weight:700;">Inicia sesión</a> para calificar.</p>'}
            </div>`;
        misEstrellasSeleccion = 0;
        renderStarsInput('stars-input-producto');
    } catch (e) { pane.innerHTML = '<div class="empty" style="padding:16px;"><i class="fas fa-plug"></i> Error al cargar</div>'; }
}

function renderStarsInput(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('i');
        star.className = 'fas fa-star' + (i <= misEstrellasSeleccion ? ' on' : '');
        star.onclick = () => { misEstrellasSeleccion = i; renderStarsInput(id); };
        el.appendChild(star);
    }
}

async function enviarValoracion(productoId) {
    if (misEstrellasSeleccion < 1) { showToast('Selecciona de 1 a 5 estrellas', 'warning'); return; }
    const comentario = document.getElementById('val-comentario') ? document.getElementById('val-comentario').value.trim() : '';
    try {
        const res = await fetch(`${API_BASE_URL}/api/tienda/productos/${productoId}/valoraciones`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: tiendaUser.cedula, estrellas: misEstrellasSeleccion, comentario })
        });
        const data = await res.json();
        if (data.success) { showToast(data.message, 'success'); cargarCatalogo(); cargarValoracionesProducto(productoId); }
        else showToast(data.message || 'Error', 'warning');
    } catch (e) { showToast('Error de conexión', 'error'); }
}

function renderRecomendados(productoId) {
    const row = document.getElementById('recomendados-row');
    if (!row) return;
    const p = catalogo.find(x => x.id === productoId);
    if (!p) { row.innerHTML = ''; return; }
    const candidatos = catalogo
        .filter(x => x.id !== productoId && x.stock > 0)
        .sort((a, b) => (a.categoria === p.categoria ? -1 : 1) || (b.vendidos || 0) - (a.vendidos || 0) || b.id - a.id)
        .slice(0, 4);
    if (candidatos.length === 0) { row.innerHTML = '<div class="empty" style="padding:12px;"><i class="fas fa-box-open"></i> Por ahora no hay más productos.</div>'; return; }
    row.innerHTML = '';
    candidatos.forEach(x => {
        const card = document.createElement('div');
        card.className = 'reco-card';
        card.onclick = () => openProductoModal(x.id);
        const img = x.imagen_url
            ? '<img src="' + (x.imagen_url.startsWith('http') ? x.imagen_url : API_BASE_URL + x.imagen_url) + '" onerror="this.onerror=null;this.outerHTML=\'<div class=\\\'ico\\\'><i class=\\\'fas fa-box\\\'></i></div>\'">'
            : '<div class="ico"><i class="fas fa-box"></i></div>';
        card.innerHTML = `${img}<h5>${x.nombre}</h5><div class="price">$${x.precio_usd.toFixed(2)}</div>${estrellasHtml(x.estrellas_promedio || 0, x.num_valoraciones || 0)}`;
        row.appendChild(card);
    });
}

// =====================================================
// SESIÓN 10: CHAT FLOTANTE (tiempo real por polling)
// =====================================================
let miConversacion = null;
let chatTimer = null;
let chatWidgetAbierto = false;
let chatCalificando = 0;

function toggleChatWidget(forzar) {
    chatWidgetAbierto = forzar !== undefined ? forzar : !chatWidgetAbierto;
    const win = document.getElementById('chat-window');
    win.classList.toggle('open', chatWidgetAbierto);
    if (chatWidgetAbierto) {
        refrescarChat();
        if (miConversacion && (miConversacion.estado === 'activa' || miConversacion.estado === 'solicitada')) marcarChatLeido();
    }
}

async function refrescarChat() {
    if (!tiendaUser) { renderChatSinSesion(); return; }
    try {
        const data = await fetch(`${API_BASE_URL}/api/chat/mi?cedula=${encodeURIComponent(tiendaUser.cedula)}`).then(r => r.json());
        miConversacion = data.conversacion || null;
        actualizarBadgeChat();
        if (chatWidgetAbierto) renderChat();
    } catch (e) { }
}

function actualizarBadgeChat() {
    const badge = document.getElementById('chat-launcher-badge');
    if (!badge) return;
    const n = miConversacion && (miConversacion.estado === 'activa' || miConversacion.estado === 'solicitada') ? (miConversacion.no_leidos || 0) : 0;
    badge.style.display = n > 0 ? 'flex' : 'none';
    badge.textContent = n > 9 ? '9+' : n;
}

function renderChat() {
    const body = document.getElementById('chat-body');
    const foot = document.getElementById('chat-foot');
    const actions = document.getElementById('chat-actions');
    const estadoTexto = document.getElementById('chat-estado-texto');
    body.innerHTML = '';
    foot.style.display = 'none';
    if (!tiendaUser) { renderChatSinSesion(); return; }
    if (!miConversacion) {
        estadoTexto.textContent = '¿Necesitas ayuda?';
        body.innerHTML = '<div class="chat-center"><div class="pill">¡Hola ' + tiendaUser.nombre + '! Un asesor te atenderá en breve.</div></div>';
        actions.innerHTML = '<button class="btn btn-primary" style="width:100%;" onclick="solicitarChat()"><i class="fas fa-headset"></i> Iniciar conversación</button>';
        return;
    }
    const c = miConversacion;
    estadoTexto.textContent = c.estado === 'solicitada' ? 'Esperando que un agente te acepte...' : c.estado === 'activa' ? (c.nombre_agente ? 'Con ' + c.nombre_agente : 'Conversación activa') : 'Conversación finalizada';
    (c.mensajes || []).forEach(m => {
        const div = document.createElement('div');
        div.className = 'chat-msg ' + m.emisor;
        div.innerHTML = `${m.contenido}<span class="hora">${m.fecha ? m.fecha.slice(11, 16) : ''}${m.emisor === 'agente' ? ' · ' + (c.nombre_agente || 'Agente') : ''}</span>`;
        body.appendChild(div);
    });
    body.scrollTop = body.scrollHeight;
    if (c.estado === 'solicitada') {
        body.innerHTML += '<div class="chat-center"><div class="pill"><i class="fas fa-spinner fa-spin"></i> Esperando que un miembro del personal acepte tu solicitud...</div></div>';
        actions.innerHTML = '';
    } else if (c.estado === 'activa') {
        foot.style.display = 'flex';
        actions.innerHTML = '<button class="btn btn-outline" onclick="finalizarChat()"><i class="fas fa-flag-checkered"></i> Finalizar conversación</button>';
    } else if (c.estado === 'finalizada') {
        if (!document.getElementById('chat-rate-stars')) {
            actions.innerHTML = `<div class="rate-box">
                <p style="font-weight:700; margin-bottom:6px;">¿Cómo fue la atención?</p>
                <div class="stars-input" id="chat-rate-stars"></div>
                <input type="text" id="chat-rate-comment" placeholder="Comentario (opcional)">
                <button class="btn btn-primary" style="margin-top:8px; width:100%;" onclick="calificarChat()">Enviar calificación</button>
                <a href="#" onclick="solicitarChat(); return false;" style="display:block; text-align:center; font-size:.78rem; color:var(--muted); margin-top:8px;">Omitir e iniciar nueva conversación</a>
            </div>`;
            chatCalificando = 0;
            renderChatStarsInput('chat-rate-stars');
        }
    } else if (c.estado === 'calificada') {
        body.innerHTML += '<div class="chat-center"><div class="pill"><i class="fas fa-star"></i> ¡Gracias por calificar! ' + '★'.repeat(c.calificacion || 0) + '</div></div>';
        actions.innerHTML = '<button class="btn btn-primary" style="width:100%;" onclick="solicitarChat()"><i class="fas fa-comments"></i> Iniciar nueva conversación</button>';
    }
    body.scrollTop = body.scrollHeight;
}

function renderChatSinSesion() {
    const body = document.getElementById('chat-body');
    if (!body) return;
    body.innerHTML = '<div class="chat-center"><div class="pill"><i class="fas fa-user-lock"></i> Inicia sesión para hablar con el personal</div></div>';
    document.getElementById('chat-estado-texto').textContent = 'Soporte en vivo';
    document.getElementById('chat-actions').innerHTML = '<button class="btn btn-primary" style="width:100%;" onclick="toggleChatWidget(false); openAuthModal(\'login\');"><i class="fas fa-sign-in-alt"></i> Iniciar sesión</button>';
    document.getElementById('chat-foot').style.display = 'none';
}

async function solicitarChat() {
    try {
        const res = await fetch(`${API_BASE_URL}/api/chat/solicitar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: tiendaUser.cedula })
        });
        const data = await res.json();
        if (data.success) { showToast(data.message, 'success'); refrescarChat(); }
        else showToast(data.message || 'No se pudo iniciar el chat', 'warning');
    } catch (e) { showToast('Error de conexión', 'error'); }
}

async function enviarMensajeChat() {
    const input = document.getElementById('chat-input');
    const contenido = input.value.trim();
    if (!contenido || !miConversacion) return;
    input.value = '';
    try {
        await fetch(`${API_BASE_URL}/api/chat/${miConversacion.id}/mensaje`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: tiendaUser.cedula, contenido })
        });
        refrescarChat();
    } catch (e) { showToast('Error de conexión', 'error'); }
}

async function finalizarChat() {
    if (!confirm('¿Finalizar esta conversación?')) return;
    try {
        const res = await fetch(`${API_BASE_URL}/api/chat/${miConversacion.id}/finalizar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: tiendaUser.cedula })
        });
        const data = await res.json();
        showToast(data.message || 'Conversación finalizada', 'success');
        refrescarChat();
    } catch (e) { showToast('Error de conexión', 'error'); }
}

function renderChatStarsInput(id) {
    const el = document.getElementById(id);
    if (!el) return;
    el.innerHTML = '';
    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('i');
        star.className = 'fas fa-star' + (i <= chatCalificando ? ' on' : '');
        star.onclick = () => { chatCalificando = i; renderChatStarsInput(id); };
        el.appendChild(star);
    }
}

async function calificarChat() {
    const comentario = document.getElementById('chat-rate-comment') ? document.getElementById('chat-rate-comment').value.trim() : '';
    if (chatCalificando < 1) { showToast('Selecciona de 1 a 5 estrellas', 'warning'); return; }
    try {
        const res = await fetch(`${API_BASE_URL}/api/chat/${miConversacion.id}/calificar`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: tiendaUser.cedula, estrellas: chatCalificando, comentario })
        });
        const data = await res.json();
        showToast(data.message || '¡Gracias por tu calificación!', 'success');
        chatCalificando = 0;
        refrescarChat();
    } catch (e) { showToast('Error de conexión', 'error'); }
}

async function marcarChatLeido() {
    if (!miConversacion || !miConversacion.no_leidos) return;
    try {
        await fetch(`${API_BASE_URL}/api/chat/${miConversacion.id}/leer`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: tiendaUser.cedula })
        });
        miConversacion.no_leidos = 0;
        actualizarBadgeChat();
    } catch (e) { }
}

function iniciarPollingChat() {
    if (chatTimer) clearInterval(chatTimer);
    chatTimer = setInterval(() => {
        if (!tiendaUser) return;
        refrescarChat().then(() => {
            if (chatWidgetAbierto && miConversacion && (miConversacion.estado === 'activa' || miConversacion.estado === 'solicitada')) marcarChatLeido();
        });
    }, 4000);
}

// =====================================================
// SESIÓN 10: TICKETS DEL CLIENTE
// =====================================================
function openMisTickets() {
    if (!tiendaUser) { showToast('Inicia sesión para reportar', 'warning'); openAuthModal('login'); return; }
    switchTicketsTab('list');
    openModal('tickets-modal');
    cargarMisTickets();
}

function switchTicketsTab(tab) {
    document.getElementById('tickets-list').style.display = tab === 'list' ? '' : 'none';
    document.getElementById('tickets-new').style.display = tab === 'new' ? '' : 'none';
    document.getElementById('tab-tickets-list').classList.toggle('active', tab === 'list');
    document.getElementById('tab-tickets-new').classList.toggle('active', tab === 'new');
    if (tab === 'list') cargarMisTickets();
}

async function cargarMisTickets() {
    const cont = document.getElementById('tickets-list');
    cont.innerHTML = '<div class="empty"><i class="fas fa-spinner fa-spin"></i> Cargando...</div>';
    try {
        const data = await fetch(`${API_BASE_URL}/api/tickets/mis?cedula=${encodeURIComponent(tiendaUser.cedula)}`).then(r => r.json());
        if (!data.tickets || data.tickets.length === 0) { cont.innerHTML = '<div class="empty"><i class="fas fa-life-ring"></i> No tienes reportes. Crea uno con el botón «Nuevo reporte».</div>'; return; }
        cont.innerHTML = '';
        data.tickets.forEach(t => {
            const card = document.createElement('div');
            card.className = 'ticket-card';
            const badge = t.estado === 'abierto' ? '<span class="badge warn">Abierto</span>' : t.estado === 'en_proceso' ? '<span class="badge proceso">En proceso</span>' : t.estado === 'resuelto' ? '<span class="badge resuelto">Resuelto</span>' : '<span class="badge cerrado">Cerrado</span>';
            card.innerHTML = `
                <div class="head"><h4><i class="fas fa-ticket-alt"></i> #${t.id} — ${t.asunto}</h4>${badge}</div>
                <p>${t.categoria || 'General'} · ${t.fecha_creacion || ''}</p>
                <p>${t.descripcion}</p>
                ${t.respuesta ? '<div class="resp"><strong>' + (t.nombre_agente || 'Personal') + ':</strong> ' + t.respuesta + '</div>' : '<p style="color:var(--muted); font-style:italic;">Esperando respuesta del personal...</p>'}
            `;
            cont.appendChild(card);
        });
    } catch (e) { cont.innerHTML = '<div class="empty"><i class="fas fa-plug"></i> Error al cargar</div>'; }
}

async function crearTicket() {
    const asunto = document.getElementById('ticket-asunto').value.trim();
    const descripcion = document.getElementById('ticket-descripcion').value.trim();
    const categoria = document.getElementById('ticket-categoria').value;
    const direccion = document.getElementById('ticket-direccion').value.trim();
    const telefono = document.getElementById('ticket-telefono').value.trim();
    const errorEl = document.getElementById('ticket-error');
    errorEl.classList.remove('show');
    if (asunto.length < 4) { errorEl.textContent = 'Escribe un asunto (mínimo 4 caracteres)'; errorEl.classList.add('show'); return; }
    if (descripcion.length < 10) { errorEl.textContent = 'Describe el reporte (mínimo 10 caracteres)'; errorEl.classList.add('show'); return; }
    try {
        const res = await fetch(`${API_BASE_URL}/api/tickets`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ cedula: tiendaUser.cedula, asunto, descripcion, categoria, direccion, telefono })
        });
        const data = await res.json();
        if (data.success) {
            showToast(data.message, 'success');
            document.getElementById('ticket-asunto').value = '';
            document.getElementById('ticket-descripcion').value = '';
            document.getElementById('ticket-direccion').value = '';
            document.getElementById('ticket-telefono').value = '';
            switchTicketsTab('list');
        } else { errorEl.textContent = data.message || 'Error al enviar'; errorEl.classList.add('show'); }
    } catch (e) { errorEl.textContent = 'Error de conexión'; errorEl.classList.add('show'); }
}

// =====================================================
// SESIÓN 10: OJITO (mostrar/ocultar contraseña)
// =====================================================
function togglePassword(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    const ver = input.type === 'password';
    input.type = ver ? 'text' : 'password';
    icon.className = ver ? 'fas fa-eye-slash' : 'fas fa-eye';
}

// =====================================================
// INIT
// =====================================================
loadTiendaUser();
cargarCatalogo();
setInterval(() => {
    if (document.visibilityState === 'visible') cargarCatalogo();
}, 45000);
iniciarPollingChat();
