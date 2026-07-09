<x-estructura-panel titulo="Seleccionar establecimiento" :usuario="$usuario">
    <main class="w3-row-padding">
        <section class="w3-half">
            <div class="w3-row-padding">
                @foreach ($usuario['negocios'] as $negocio)
                    <a
                        href="{{ route('panel.negocios.{negocio}', [
                            'negocio' => $negocio['id']]
                        ) }}"
                        class="w3-half w3-button">
                        <img
                            src="{{ $negocio['imagenes'][0] ?? '' }}"
                            class="w3-image w3-block"
                        />
                        {{ $negocio['nombre'] }}

                        <div class="w3-row-padding">
                            @foreach ($negocio['sucursales'] as $sucursal)
                                <a
                                    href="{{ route(
                                        'panel.negocios.{negocio}.sucursales.{sucursal}',
                                        [
                                            'negocio' => $negocio['id'],
                                            'sucursal' => $sucursal['id']
                                        ],
                                    ) }}"
                                    class="w3-half w3-button">
                                    <img
                                        src="{{ $sucursal['imagenes'][0] ?? '' }}"
                                        class="w3-image w3-block"
                                    />
                                    {{ $sucursal['nombre'] }}
                                </a>
                            @endforeach
                        </div>
                    </a>
                @endforeach
            </div>
        </section>

        <form
            method="post"
            enctype="multipart/form-data"
            class="w3-half w3-card-4">
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
                name="slug"
                placeholder="Slug"
                required
                minlength="1"
                pattern="[a-z0-9\-]+"
                title="El slug debe contener solo letras minúsculas, números y guiones."
                class="w3-input"
            />
            <input
                name="imagenes[]"
                type="file"
                accept="image/*"
                multiple
            />
            <input
                type="submit"
                value="Agregar negocio"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-estructura>
