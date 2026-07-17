<x-estructura-panel
    titulo="Editar sucursal"
    :negocio="$negocio"
    :usuario="$usuario">
    <form
        method="post"
        class="w3-container"
        action="{{ route('panel.negocios.{negocio}.sucursales.{sucursal}', [
            'negocio' => $negocio,
            'sucursal' => $sucursal,
        ]) }}">
        <input
            name="nombre"
            placeholder="Nombre"
            required
            minlength="1"
            pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+"
            title="El nombre debe contener solo letras y espacios."
            class="w3-input"
            value="{{ $sucursal->nombre }}"
        />
        <input
            name="direccion"
            placeholder="Dirección"
            required
            minlength="1"
            class="w3-input"
            value="{{ $sucursal->direccion }}"
        />
        <input
            name="telefono"
            placeholder="Teléfono"
            required
            type="tel"
            class="w3-input"
            pattern="\+58(416|426|414|424)\d{7}"
            title="El número de teléfono debe tener el formato +58(416|426|414|424) seguido de 7 dígitos."
            value="{{ $sucursal->telefono }}"
        />
        <input
            type="submit"
            value="Actualizar"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-estructura-panel>
