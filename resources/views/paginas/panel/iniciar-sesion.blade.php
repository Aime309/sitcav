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
                    <x-panel.campo
                        label="Correo electrónico"
                        required
                        autocomplete="email"
                        name="correo"
                        placeholder="_____@gmail.com"
                        type="email"
                        :value="old('correo')"
                        :message="$message ?? ''">
                        <x-slot:icono>
                            <x-iconos.correo />
                        </x-slot:icono>
                    </x-panel.campo>
                    <x-panel.campo
                        label="Contraseña"
                        required
                        autocomplete="current-password"
                        name="clave"
                        placeholder="••••••••"
                        type="password"
                        :message="$message ?? ''">
                        <x-slot:icono>
                            <x-iconos.clave />
                        </x-slot:icono>
                    </x-panel.campo>
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
