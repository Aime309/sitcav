@php

$crumbs ??= [];

@endphp

<header class="d-topbar">
    <div class="crumbs">
        <button
            class="hamburger"
            data-drawer-open
            aria-label="Open navigation">
            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6" />
                <line x1="3" y1="12" x2="21" y2="12" />
                <line x1="3" y1="18" x2="21" y2="18" />
            </svg>
        </button>
        @foreach ($crumbs as $indice => $crumb)
            @if ($indice > 0)
                <svg
                    class="sep"
                    width="10"
                    height="10"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2">
                    <path d="m9 18 6-6-6-6"/>
                </svg>
            @endif

            @if ($indice === count($crumbs) - 1)
                <span class="current">{{ $crumb }}</span>
            @else
                <span>{{ $crumb }}</span>
            @endif
        @endforeach
    </div>
    <div class="topbar-actions">
        <button class="cmd" data-palette-open>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <circle cx="11" cy="11" r="7" />
                <path d="m21 21-4.3-4.3" />
            </svg>
            <span>Buscar...</span>
            <kbd class="kbd">⌘K</kbd>
        </button>

        <div class="dd-wrap">
            <button class="icon-btn" data-dropdown aria-label="Reservas">
                <svg viewBox="0 0 24 24">
                    <rect x="3" y="5" width="18" height="14" rx="2"/>
                    <path d="m3 7 9 6 9-6"/>
                </svg>
                <span class="count info">{{ count($reservas ?? []) }}</span>
            </button>
            <div class="dd-menu" role="menu">
                <div class="dd-head">
                    <svg viewBox="0 0 24 24">
                        <rect x="3" y="5" width="18" height="14" rx="2"/>
                        <path d="m3 7 9 6 9-6"/>
                    </svg>
                    Reservas
                </div>
                <div class="dd-list">
                    @foreach ($reservas ?? [] as $reserva)
                        <a class="dd-item" href="#">
                            <div class="dd-avatar a1">JD</div>
                            <div class="dd-body">
                                <div class="dd-row-head">
                                    <strong>John Doe</strong>
                                    <span class="dd-time">5 MIN</span>
                                </div>
                                <div class="dd-preview">
                                    Want to create your own customized data generator for your app…
                                </div>
                            </div>
                        </a>
                        <a class="dd-item" href="#">
                            <div class="dd-avatar a2">MD</div>
                            <div class="dd-body">
                                <div class="dd-row-head">
                                    <strong>Moo Doe</strong>
                                    <span class="dd-time">15 MIN</span>
                                </div>
                                <div class="dd-preview">
                                    Want to create your own customized data generator for your app…
                                </div>
                            </div>
                        </a>
                        <a class="dd-item" href="#">
                            <div class="dd-avatar a3">LD</div>
                            <div class="dd-body">
                                <div class="dd-row-head">
                                    <strong>Lee Doe</strong>
                                    <span class="dd-time">25 MIN</span>
                                </div>
                                <div class="dd-preview">
                                    Want to create your own customized data generator for your app…
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            <a
                class="dd-footer"
                href="{{ route(
                    'panel.negocios.{negocio}.reservas',
                    ['negocio' => $negocio],
                ) }}">
                Ver todas las reservas →
            </a>
        </div>
    </div>

    <div class="dd-wrap">
        <div
            class="avatar"
            data-dropdown
            tabindex="0"
            role="button"
            aria-label="Account menu">
        </div>
        <div class="dd-menu dd-profile" role="menu">
            <div class="dd-profile-head">
                <div class="dd-profile-name"></div>
                <div class="dd-profile-email">{{ $usuario->correo }}</div>
            </div>
            <a
                class="dd-menu-item"
                href="{{ route(
                    'panel.negocios.{negocio}.editar',
                    ['negocio' => $negocio],
                ) }}">
                <svg viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="3" />
                    <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z" />
                </svg>
                Configuraciones
            </a>
            <a
                class="dd-menu-item"
                href="{{ route(
                    'panel.negocios.{negocio}.perfil',
                    ['negocio' => $negocio],
                ) }}">
                <svg viewBox="0 0 24 24">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                Perfil
            </a>
            <div class="dd-divider" style="margin: 0"></div>
            <a
                class="dd-menu-item danger"
                href="{{ route('panel.cerrar-sesion') }}">
                <svg viewBox="0 0 24 24">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9" />
                </svg>
                Cerrar sesión
            </a>
        </div>
    </div>
</header>
