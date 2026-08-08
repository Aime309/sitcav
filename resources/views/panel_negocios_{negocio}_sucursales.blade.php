<x-panel.estructuras.privada
    titulo="Sucursales"
    :crumbs="['Sucursales']"
    :negocio="$negocio"
    :usuario="$usuario">
    <main class="w3-row-padding">
        <section class="w3-half">
            <div class="w3-row-padding">
                @foreach ($negocio->sucursales as $sucursal)
                    <a
                        href="{{ route(
                            'panel.negocios.{negocio}.sucursales.{sucursal}.editar',
                            [
                                'negocio' => $negocio,
                                'sucursal' => $sucursal,
                            ],
                        ) }}"
                        class="w3-third w3-button">
                        {{ $sucursal->nombre }}
                    </a>
                @endforeach
            </div>
        </section>

        <form method="post" class="w3-half">
            <input
                name="nombre"
                placeholder="Nombre"
                required
                minlength="1"
                pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+"
                title="El nombre debe contener solo letras y espacios."
                class="w3-input"
            />
            <input
                name="direccion"
                placeholder="Dirección"
                required
                minlength="1"
                class="w3-input"
            />
            <input
                name="telefono"
                placeholder="Teléfono"
                required
                type="tel"
                class="w3-input"
                pattern="\+58(416|426|414|424)\d{7}"
                title="El número de teléfono debe tener el formato +58(416|426|414|424) seguido de 7 dígitos."
            />
            <input
                type="submit"
                value="Agregar sucursal"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-panel.estructuras.privada>
