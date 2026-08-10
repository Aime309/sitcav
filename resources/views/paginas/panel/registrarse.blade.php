<x-panel.estructuras.publica titulo="Registrarse">
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
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('panel.iniciar-sesion') }}">
                        Ingresar
                    </a>
                </div>
            </div>
            <div class="auth-card">
                <h2>Crea tu espacio de trabajo</h2>
                <form class="auth-form" method="post">
                    @csrf
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
                                autocomplete="email"
                                class="
                                    input
                                    @error('correo') is-invalid @enderror
                                "
                                id="correo"
                                minlength="11"
                                name="correo"
                                pattern=".+@gmail.com"
                                placeholder="_____@gmail.com"
                                required
                                title="El correo electrónico debe ser una dirección de Gmail válida."
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
                                class="
                                    input
                                    @error('clave') is-invalid @enderror
                                "
                                type="password"
                                placeholder="Al menos 8 caracteres" autocomplete="new-password"
                                required
                                minlength="8"
                                pattern=".{8,}"
                                title="La contraseña debe tener al menos 8 caracteres."
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
                        Crear cuenta
                        <x-iconos.flecha-derecha />
                    </button>
                </form>
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
