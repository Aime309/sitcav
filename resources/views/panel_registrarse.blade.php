<x-panel.estructuras.publica titulo="Registrarse">
    <div class="auth-shell">
        <aside class="auth-aside">
            <div class="auth-brand">
                {{-- <div class="logo">
                    <x-iconos.a />
                </div> --}}
                <div class="name">SITCAV</div>
            </div>
            <div class="auth-aside-body">
                <span class="auth-aside-eyebrow">
                    {{ date('Y') }} · v4.3.0 preview
                </span>
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
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('panel.iniciar-sesion') }}">
                        Ingresar
                    </a>
                </div>
            </div>
            <div class="auth-card">
                <h2>Crea tu espacio de trabajo</h2>
                {{-- <p class="sub">
                    14-day trial · no card required · invite your team in one click.
                </p> --}}
                <form class="auth-form" method="post">
                    {{-- <div class="field">
                        <label
                            class="field-label"
                            for="name">
                            Full name
                        </label>
                        <input
                            id="name"
                            class="input"
                            type="text"
                            placeholder="Jane Doe"
                            autocomplete="name"
                            required />
                    </div> --}}
                    <div class="field">
                        <label
                            class="field-label"
                            for="correo">
                            Correo electrónico de trabajo
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <x-iconos.correo />
                            </span>
                            <input
                                name="correo"
                                id="correo"
                                class="input"
                                type="email"
                                placeholder="_____@gmail.com"
                                autocomplete="email"
                                minlength="11"
                                pattern=".+@gmail.com"
                                title="El correo electrónico debe ser una dirección de Gmail válida."
                                required />
                        </div>
                    </div>
                    {{-- <div class="field">
                        <label
                            class="field-label"
                            for="workspace">
                            Workspace URL
                        </label>
                        <div class="input-group">
                            <span class="addon">adminator.app/</span>
                            <input
                                id="workspace"
                                class="input"
                                type="text"
                                placeholder="acme"
                                required />
                        </div>
                        <div class="field-help">
                            Lowercase letters, numbers, and hyphens only.
                        </div>
                    </div> --}}
                    <div class="field">
                        <label
                            class="field-label"
                            for="clave">
                            Contraseña
                            <span class="req">*</span>
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <x-iconos.clave />
                            </span>
                            <input
                                id="clave"
                                name="clave"
                                class="input"
                                type="password"
                                placeholder="Al menos 12 caracteres" autocomplete="new-password"
                                required
                                minlength="8"
                                pattern=".{8,}"
                                title="La contraseña debe tener al menos 8 caracteres." />
                        </div>
                        {{-- <div class="progress thin">
                            <div
                                class="progress-fill success"
                                style="width: 75%">
                            </div>
                        </div> --}}
                    </div>
                    <!-- <label class="check">
                        <input type="checkbox" checked="checked" required />
                        <span class="box"></span>
                        Estoy de acuerdo con los
                        <a
                            href="#"
                            style="
                                color: var(--primary);
                                font-weight: 600;
                                margin:0 4px
                            ">
                            Términos
                        </a>
                        y
                        <a
                            href="#"
                            style="
                                color: var(--primary);
                                font-weight: 600;
                                margin-left: 4px
                            ">
                            Política de Privacidad
                        </a>
                    </label> -->
                    <button
                        class="btn btn--primary auth-submit"
                        type="submit">
                        Crear cuenta
                        <x-iconos.flecha-derecha />
                    </button>
                </form>
                <!-- <div class="auth-divider">o registrate con</div> -->
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
            <div class="auth-main-bottom">
                ¿Ya eres un empleado?
                <a
                    href="{{ route('panel.iniciar-sesion') }}"
                    style="
                        color: var(--primary);
                        font-weight: 600
                    ">
                    Ingresar
                </a>
            </div>
        </main>
    </div>
</x-panel.estructuras.publica>
