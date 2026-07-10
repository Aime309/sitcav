<x-estructura-panel titulo="Editar negocio" :negocio="$negocio" :usuario="$usuario">
    <form
        method="post"
        enctype="multipart/form-data"
        class="w3-container"
        action="{{ route('panel.negocios.{negocio}', ['negocio' => $negocio['id']]) }}">
        <input
            name="nombre"
            placeholder="Nombre"
            required
            minlength="1"
            pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+"
            title="El nombre debe contener solo letras y espacios."
            class="w3-input"
            value="{{ $negocio['nombre'] }}"
        />
        <input
            name="rif"
            placeholder="RIF"
            required
            minlength="1"
            class="w3-input"
            value="{{ $negocio['rif'] }}"
        />
        <input
            name="direccion"
            placeholder="Dirección"
            required
            minlength="1"
            class="w3-input"
            value="{{ $negocio['direccion'] }}"
        />
        <input
            name="telefono"
            placeholder="Teléfono"
            required
            type="tel"
            class="w3-input"
            pattern="\+58(416|426|414|424)\d{7}"
            title="El número de teléfono debe tener el formato +58(416|426|414|424) seguido de 7 dígitos."
            value="{{ $negocio['telefono'] }}"
        />
        <input
            name="slug"
            placeholder="Slug"
            required
            minlength="1"
            pattern="[a-z0-9\-]+"
            title="El slug debe contener solo letras minúsculas, números y guiones."
            class="w3-input"
            value="{{ $negocio['slug'] }}"
        />
        <label>
            <input
                name="carga_inicial_abierta"
                type="checkbox"
                class="w3-check"
                {{ $negocio['carga_inicial_abierta'] ? 'checked' : '' }}
            />

            Carga inicial abierta
        </label>
        <input
            type="submit"
            value="Actualizar"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-estructura-panel>
