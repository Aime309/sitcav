<x-panel.estructuras.privada
    titulo="Empleados"
    :crumbs="['Empleados']"
    :negocio="$negocio"
    :usuario="$usuario">
    <main class="w3-row-padding">
        <section class="w3-half">
            <div class="w3-row-padding">
                @foreach ($empleados as $empleado)
                    <article class="w3-third w3-center">
                        {{ $empleado->correo }}
                        <form
                            method="post"
                            action="{{ route(
                                'panel.negocios.{negocio}.empleados.{empleado}',
                                [
                                    'negocio' => $negocio,
                                    'empleado' => $empleado,
                                ],
                            ) }}">
                            @csrf
                            <select name="rol" required class="w3-select">
                                <option
                                    value="encargado"
                                    @selected($empleado->roles->contains(
                                        'rol',
                                        'encargado'
                                    ))>
                                    Encargado
                                </option>
                                <option
                                    value="vendedor"
                                    @selected($empleado->roles->doesntContain(
                                        'rol',
                                        'encargado'
                                    ))>
                                    Vendedor
                                </option>
                            </select>
                            <select name="establecimiento" required class="w3-select">
                                @foreach ($usuario->negocios as $negocio)
                                    <optgroup label="{{ $negocio->nombre }}">
                                        <option
                                            value="{{ $negocio->slug }}"
                                            @selected(($empleado->negocios[0]?->slug ?? null) === $negocio->slug)>
                                            Principal
                                        </option>
                                        @foreach ($negocio->sucursales as $sucursal)
                                            <option
                                                value="{{ $sucursal->id }}"
                                                @selected(($empleado->sucursales[0]?->id ?? null) === $sucursal->id)>
                                                {{ $sucursal->nombre }}
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
            class="w3-half w3-card-4">
            @csrf
            <select name="rol" required class="w3-select">
                <option value="encargado">Encargado</option>
                <option value="vendedor">Vendedor</option>
            </select>
            <select name="establecimiento" required class="w3-select">
                @foreach ($usuario->negocios as $negocio)
                    <optgroup label="{{ $negocio->nombre }}">
                        <option value="{{ $negocio->slug }}">
                            Principal
                        </option>
                        @foreach ($negocio->sucursales as $sucursal)
                            <option value="{{ $sucursal->id }}">
                                {{ $sucursal->nombre }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
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
                type="submit"
                value="Registrar empleado"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-panel.estructuras.privada>
