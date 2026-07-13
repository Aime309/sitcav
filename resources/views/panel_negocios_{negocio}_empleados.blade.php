<x-estructura-panel titulo="Empleados" :negocio="$negocio" :usuario="$usuario">
    <main class="w3-row-padding">
        <section class="w3-half">
            <div class="w3-row-padding">
                @foreach ($empleados as $empleado)
                    <article class="w3-third w3-center">
                        @if ($empleado->imagen)
                            <img
                                src="data:image/png;base64,{{ base64_encode($empleado->imagen) }}"
                                class="w3-image w3-block" />
                        @else
                            <img
                                src="data:image/png;base64,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"
                                class="w3-image w3-block" />
                        @endif
                        {{ $empleado->nombre }} {{ $empleado->apellido }}

                        <form
                            method="post"
                            action="{{ route(
                                'panel.negocios.{negocio}.empleados.{empleado}',
                                [
                                    'negocio' => $negocio,
                                    'empleado' => $empleado
                                ],
                            ) }}">
                            <label>
                                <input
                                    type="checkbox"
                                    name="activo"
                                    @checked($empleado->activo) />
                                Activo
                            </label>

                            <select name="rol" required class="w3-select">
                                <option
                                    value="encargado"
                                    @selected($empleado->roles === ['encargado', 'vendedor'])>
                                    Encargado
                                </option>
                                <option
                                    value="vendedor"
                                    @selected($empleado->roles === ['vendedor'])>
                                    Vendedor
                                </option>
                            </select>
                            <select name="establecimiento" required class="w3-select">
                                @foreach ($usuario->negocios as $negocio)
                                    <optgroup label="{{ $negocio['nombre'] }}">
                                        <option
                                            value="{{ $negocio['id'] }}"
                                            @selected($empleado['asignaciones'][0]['negocio_id'] === $negocio['id'])>
                                            Principal
                                        </option>
                                        @foreach ($negocio->sucursales as $sucursal)
                                            <option
                                                value="{{ $sucursal['id'] }}"
                                                @selected($empleado['asignaciones'][0]['sucursal_id'] === $sucursal['id'])>
                                                {{ $sucursal['nombre'] }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>

                            <input
                                type="submit"
                                value="Actualizar"
                                class="w3-button w3-blue w3-hover-light-blue w3-block"
                            />
                        </form>
                    </article>
                @endforeach
            </div>
        </section>

        <form
            method="post"
            enctype="multipart/form-data"
            class="w3-half w3-card-4">
            <select name="rol" required class="w3-select">
                <option value="encargado">Encargado</option>
                <option value="vendedor">Vendedor</option>
            </select>
            <select name="establecimiento" required class="w3-select">
                @foreach ($usuario->negocios as $negocio)
                    <optgroup label="{{ $negocio['nombre'] }}">
                        <option value="{{ $negocio['id'] }}">
                            Principal
                        </option>
                        @foreach ($negocio->sucursales as $sucursal)
                            <option value="{{ $sucursal['id'] }}">
                                {{ $sucursal['nombre'] }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            <input
                name="nombre"
                placeholder="Nombre"
                required
                minlength="1"
                pattern="[A-Z][a-záéíóúñ]+"
                title="El nombre debe comenzar con una letra mayúscula y contener solo letras minúsculas."
                class="w3-input"
            />
            <input
                name="apellido"
                placeholder="Apellido"
                required
                minlength="1"
                pattern="[A-Z][a-záéíóúñ]+"
                title="El apellido debe comenzar con una letra mayúscula y contener solo letras minúsculas."
                class="w3-input"
            />
            <input
                name="correo"
                placeholder="Correo electrónico"
                required
                type="email"
                class="w3-input"
                minlength="11"
                pattern=".+@gmail.com"
                title="El correo electrónico debe ser una dirección de Gmail válida."
            />
            <input
                name="clave"
                placeholder="Contraseña"
                required
                type="password"
                class="w3-input"
                minlength="8"
                pattern=".{8,}"
                title="La contraseña debe tener al menos 8 caracteres."
            />
            <input
                name="telefono"
                placeholder="Teléfono"
                required
                type="tel"
                class="w3-input"
                pattern="+58(416|426|414|424)\d{7}"
                title="El número de teléfono debe tener el formato +58(416|426|414|424) seguido de 7 dígitos."
            />
            <input
                name="imagen"
                type="file"
                accept="image/*"
            />
            <input
                type="submit"
                value="Registrar empleado"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-estructura-panel>
