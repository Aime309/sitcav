<x-panel.estructuras.publica titulo="Iniciar sesión">
    <div class="auth-shell">
        <aside class="auth-aside">
            <div class="auth-brand">
                {{-- <div class="logo">
                    <x-panel.logo-marca />
                </div> --}}
                <div class="name">SITCAV</div>
            </div>
            <div class="auth-aside-body">
                <h1>
                    El panel administrativo que tu equipo actualmente quiere abrir.
                </h1>
                {{-- <p>
                    Faster builds, cleaner tokens, and a design system that scales from a single chart to a 12-screen ops cockpit.
                </p> --}}
                {{-- <div class="auth-quote">
                    "Reemplazamos cuatro herramientas administrativas a medida con un flujo de trabajo con SITCAV. El modo oscuro por sí solo se ganó su sustento."
                    <div class="auth-quote-author">
                        <div class="av">SK</div>
                        <div>Sara Kim · Head of Engineering, Northwind</div>
                    </div>
                </div> --}}
            </div>
            <div class="auth-aside-footer">
                <span>© {{ date('Y') }}</span>
                <span>CREADO EN MÉRIDA, VE</span>
            </div>
        </aside>
        <main class="auth-main">
            <div class="auth-main-top">
                <a
                    href="./"
                    style="
                        font-size: 12.5px;
                        color: var(--t-muted);
                        display: inline-flex;
                        align-items: center;
                        gap: 6px
                    ">
                    <x-iconos.flecha-izquierda />
                    Volver al inicio
                </a>
                <div class="switch-link">
                    ¿Nuevo aquí?
                    <a href="{{ route('panel.registrarse') }}">
                        Crear cuenta
                    </a>
                </div>
            </div>
            <div class="auth-card">
                <h2>Bienvenido de vuelta</h2>
                <p class="sub">
                    Inicia sesión en tu área de trabajo con SITCAV para continuar donde lo dejaste.
                </p>
                <form class="auth-form" method="post">
                    <div class="field">
                        <label class="field-label" for="correo">
                            Correo electrónico
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <x-iconos.correo />
                            </span>
                            <input
                                id="correo"
                                name="correo"
                                class="input"
                                type="email"
                                placeholder="___@gmail.com"
                                autocomplete="email"
                                required />
                        </div>
                    </div>
                    <div class="field">
                        <div class="field-row">
                            <label class="field-label" for="clave">
                                Contraseña
                            </label>
                            {{-- <a href="#">¿La olvidaste?</a> --}}
                        </div>
                        <div class="input-icon">
                            <span class="ico">
                                <x-iconos.clave />
                            </span>
                            <input
                                id="clave"
                                name="clave"
                                class="input"
                                type="password"
                                placeholder="••••••••"
                                autocomplete="current-password"
                                required />
                        </div>
                    </div>
                    {{-- <label class="check">
                        <input type="checkbox" checked="checked" />
                        <span class="box"></span>
                        Mantener mi sesión por 30 días
                    </label> --}}
                    <button
                        class="btn btn--primary auth-submit"
                        type="submit">
                        Iniciar sesión
                        <x-iconos.flecha-derecha />
                    </button>
                </form>
                {{-- <div class="auth-divider">o continúa con</div> --}}
                <!-- <div class="social-row">
                    <a
                        class="social-btn"
                        href="#"
                        aria-label="Continue with Google">
                        <x-iconos.google />
                        Google
                    </a>
                    <a
                        class="social-btn"
                        href="#"
                        aria-label="Continue with GitHub">
                        <x-iconos.github />
                        GitHub
                    </a>
                    <a
                        class="social-btn"
                        href="#"
                        aria-label="Continue with Apple">
                        <x-iconos.apple />
                        Apple
                    </a>
                </div> -->
            </div>
            {{-- <div class="auth-main-bottom">
                Iniciando sesión aceptas nuestros
                <a href="#">Términos</a>
                y
                <a href="#">´Política de Privacidad</a>
                .
            </div> --}}
        </main>
    </div>
</x-panel.estructuras.publica>
