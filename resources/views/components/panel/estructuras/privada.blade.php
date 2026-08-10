@props(['titulo', 'usuario', 'negocio', 'sucursal', 'crumbs', 'reservas'])

@php

$nombreEstablecimiento = match (true) {
    !empty($sucursal) => $sucursal->nombre,
    !empty($negocio) => $negocio->nombre,
    default => '',
};

@endphp

<x-panel.estructuras.publica :titulo="$titulo">
    <div class="shell">
        <x-panel.barra-lateral
            :usuario="$usuario"
            :negocio="$negocio"
            :sucursal="$sucursal ?? null" />
        <div class="main">
            <x-panel.barra-superior
                :crumbs="[$nombreEstablecimiento, ...$crumbs ?? []]"
                :negocio="$negocio"
                :usuario="$usuario"
                :reservas="$reservas ?? []" />
            <main class="content">
                {{ $slot }}
            </main>
            <x-panel.pie-pagina />
        </div>
    </div>
</x-panel.estructuras.publica>
