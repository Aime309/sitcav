<x-estructura-panel
    titulo="Editar negocio"
    :crumbs="['Editar']"
    :negocio="$negocio"
    :usuario="$usuario">
    <form
        method="post"
        class="w3-container"
        action="{{ route('panel.negocios.{negocio}', [
            'negocio' => $negocio,
        ]) }}"
        x-data='{
            nombre: @json($negocio->nombre),

            get slug() {
                return this.nombre.toLowerCase().replace(/\s+/, "-");
            },
        }'>
        <input
            name="nombre"
            placeholder="Nombre"
            required
            minlength="1"
            pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+"
            title="El nombre debe contener solo letras y espacios."
            class="w3-input"
            value="{{ $negocio->nombre }}"
            x-model="nombre"
        />
        <input
            name="rif"
            placeholder="RIF"
            required
            minlength="1"
            class="w3-input"
            value="{{ $negocio->rif }}"
        />
        <input
            name="direccion"
            placeholder="Dirección"
            required
            minlength="1"
            class="w3-input"
            value="{{ $negocio->direccion }}"
        />
        <input
            name="telefono"
            placeholder="Teléfono"
            required
            type="tel"
            class="w3-input"
            pattern="\+58(416|426|414|424)\d{7}"
            title="El número de teléfono debe tener el formato +58(416|426|414|424) seguido de 7 dígitos."
            value="{{ $negocio->telefono }}"
        />
        <input
            placeholder="Slug"
            class="w3-input"
            value="{{ $negocio->slug }}"
            x-model="slug"
            disabled
        />
        <label>
            <input
                name="carga_inicial_abierta"
                type="checkbox"
                class="w3-check"
                @checked($negocio->carga_inicial_abierta)
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
