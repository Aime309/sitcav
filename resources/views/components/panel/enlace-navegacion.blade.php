@props(['uri', 'icono', 'texto', 'insignia'])

@php

$activo = str_ends_with($uri, $_SERVER['REQUEST_URI']);

@endphp

<a
    class="nav-link {{ !$activo ?: 'is-active' }}"
    href="{{ $uri }}">
    <svg viewBox="0 0 24 24">{!! $icono !!}</svg>
    <span>{{ $texto }}</span>
    @if (!empty($insignia))
        <span class="nav-badge {{ $insignia['tipo'] ?? '' }}">
            {{ $insignia['texto'] ?? '' }}
        </span>
    @endif
</a>
