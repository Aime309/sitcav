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
                        @if (empty($negocio->imagenes[0]))
                            <img
                                src="data:image/png;base64,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"
                                class="w3-image w3-block"
                            />
                        @else
                            <img
                                src="data:image/png;base64,{{ base64_encode($negocio['imagenes'][0]->imagen) }}"
                                class="w3-image w3-block"
                            />
                        @endif

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
                                    @if (empty($sucursal->imagenes[0]))
                                        <img
                                            src="data:image/avif;base64,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"
                                            class="w3-image w3-block"
                                        />
                                    @else
                                        <img
                                            src="data:image/png;base64,{{ base64_encode($sucursal->imagenes[0]->imagen) }}"
                                            class="w3-image w3-block"
                                        />
                                    @endif

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
