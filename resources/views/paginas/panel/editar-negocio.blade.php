<x-panel.estructuras.privada
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
        @csrf

        <x-panel.campo
            name="nombre"
            placeholder="Nombre"
            required
            :minlength="1"
            pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+"
            title="El nombre debe contener solo letras y espacios."
            :message="$message ?? ''"
            :value="old('nombre', $negocio->nombre)"
            model="nombre"
        />

        <x-panel.campo
            name="rif"
            placeholder="RIF"
            required
            :minlength="1"
            :message="$message ?? ''"
            :value="old('rif', $negocio->rif)"
        />

        <x-panel.campo
            name="direccion"
            placeholder="Dirección"
            required
            :minlength="1"
            :message="$message ?? ''"
            :value="old('direccion', $negocio->direccion)"
        />

        <x-panel.campo
            name="telefono"
            placeholder="Teléfono"
            required
            type="tel"
            pattern="\+58(416|426|414|424)\d{7}"
            title="El número de teléfono debe tener el formato +58(416|426|414|424) seguido de 7 dígitos."
            :message="$message ?? ''"
            :value="old('telefono', $negocio->telefono)"
        />

        <x-panel.campo
            placeholder="Slug"
            model="slug"
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
</x-panel.estructuras.privada>
