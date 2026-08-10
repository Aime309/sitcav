<x-panel.estructuras.publica titulo="Iniciar sesión">
    <div class="auth-shell">
        <aside class="auth-aside">
            <div class="auth-brand">
                <div class="name">SITCAV</div>
            </div>
            <div class="auth-aside-body">
                <h1>
                    El panel administrativo que tu equipo actualmente quiere abrir.
                </h1>
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
                    @csrf
                    <div class="field">
                        <label class="field-label" for="correo">
                            Correo electrónico
                        </label>
                        <div class="input-icon">
                            <span class="ico">
                                <x-iconos.correo />
                            </span>
                            <input
                                autocomplete="email"
                                class="
                                    input
                                    @error('correo') is-invalid @enderror
                                "
                                id="correo"
                                name="correo"
                                placeholder="___@gmail.com"
                                required
                                type="email"
                                value="{{ old('correo') }}"
                            />
                        </div>
                        @error('correo')
                            <x-panel.mensaje-error-campo>
                                {{ $message }}
                            </x-panel.mensaje-error-campo>
                        @enderror
                    </div>
                    <div class="field">
                        <div class="field-row">
                            <label class="field-label" for="clave">
                                Contraseña
                            </label>
                        </div>
                        <div class="input-icon">
                            <span class="ico">
                                <x-iconos.clave />
                            </span>
                            <input
                                autocomplete="current-password"
                                class="
                                    input
                                    @error('clave') is-invalid @enderror
                                "
                                id="clave"
                                name="clave"
                                placeholder="••••••••"
                                required
                                type="password"
                            />
                        </div>
                        @error('clave')
                            <x-panel.mensaje-error-campo>
                                {{ $message }}
                            </x-panel.mensaje-error-campo>
                        @enderror
                    </div>
                    <button
                        class="btn btn--primary auth-submit"
                        type="submit">
                        Iniciar sesión
                        <x-iconos.flecha-derecha />
                    </button>
                </form>
            </div>
        </main>
    </div>
</x-panel.estructuras.publica>
