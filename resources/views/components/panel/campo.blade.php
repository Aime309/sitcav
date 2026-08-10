@props([
    'label',
    'placeholder',
    'value',
    'name',
    'disabled',
    'icono',
    'autocomplete',
    'minlength',
    'message',
    'pattern',
    'required',
    'title',
    'type',
])

@php

$id = uniqid();

@endphp

<div class="field">
    <label class="field-label" for="{{ $id }}">
        {{ $label ?? '' }}
        @if ($required ?? false)
            <span class="req">*</span>
        @endif
    </label>

    @if (empty($icono))
        <input
            {{ empty($autocomplete) ?: "autocomplete=$autocomplete" }}
            id="{{ $id }}"
            class="input @error($name ?? '') is-invalid @enderror"
            placeholder="{{ $placeholder ?? '' }}"
            value="{{ $value ?? '' }}"
            name="{{ $name ?? '' }}"
            @disabled($disabled ?? false)
            minlength="{{ $minlength ?? null }}"
            {{ empty($pattern) ?: "pattern=$pattern" }}
            @required($required ?? false)
            title="{{ $title ?? '' }}"
            type="{{ $type ?? 'text' }}"
        />
    @else
        <div class="input-icon">
            <span class="ico">
                {!! $icono ?? '' !!}
            </span>
            <input
                {{ empty($autocomplete) ?: "autocomplete=$autocomplete" }}
                id="{{ $id }}"
                class="input @error($name ?? '') is-invalid @enderror"
                placeholder="{{ $placeholder ?? '' }}"
                value="{{ $value ?? '' }}"
                name="{{ $name ?? '' }}"
                @disabled($disabled ?? false)
                minlength="{{ $minlength ?? null }}"
                {{ empty($pattern) ?: "pattern=$pattern" }}
                @required($required ?? false)
                title="{{ $title ?? '' }}"
                type="{{ $type ?? 'text' }}"
            />
        </div>
    @endif

    @error($name ?? '')
        <x-panel.mensaje-error-campo>
            {{ $message }}
        </x-panel.mensaje-error-campo>
    @enderror
</div>
