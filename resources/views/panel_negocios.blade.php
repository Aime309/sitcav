<x-estructura-panel titulo="Seleccionar establecimiento" :usuario="$usuario">
    <main class="w3-row-padding">
        <section class="w3-half">
            <div class="w3-row-padding">
                @foreach ($usuario['negocios'] as $negocio)
                    <a
                        href="{{ route('panel.negocios.{negocio}', [
                            'negocio' => $negocio,
                        ]) }}"
                        class="w3-half w3-button">
                        {{ $negocio->nombre }}
                        <div class="w3-row-padding">
                            @foreach ($negocio->sucursales as $sucursal)
                                <a
                                    href="{{ route(
                                        'panel.negocios.{negocio}.sucursales.{sucursal}',
                                        [
                                            'negocio' => $negocio,
                                            'sucursal' => $sucursal,
                                        ],
                                    ) }}"
                                    class="w3-quarter w3-button">
                                    {{ $sucursal->nombre }}
                                </a>
                            @endforeach
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <form
            method="post"
            class="w3-half w3-card-4"
            x-data="{
                nombre: '',

                get slug() {
                    return this.nombre.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                },
            }">
            <input
                name="nombre"
                placeholder="Nombre"
                required
                minlength="1"
                pattern="[A-Za-zÁÉÍÓÚÑáéíóúñ\s]+"
                title="El nombre debe contener solo letras y espacios."
                class="w3-input"
                x-model="nombre"
            />
            <input
                name="rif"
                placeholder="RIF"
                required
                minlength="1"
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
                placeholder="Slug"
                minlength="1"
                class="w3-input"
                disabled
                x-model="slug"
            />
            <input
                type="submit"
                value="Agregar negocio"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-estructura>
