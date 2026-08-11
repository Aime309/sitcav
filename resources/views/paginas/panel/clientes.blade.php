<x-panel.estructuras.privada
    titulo="Clientes"
    :crumbs="['Clientes']"
    :negocio="$negocio"
    :usuario="$usuario">
    <main class="w3-row-padding">
        <table class="w3-half w3-table">
            @foreach($negocio->clientes as $cliente)
                <tr>
                    <td>{{ $cliente->nombre }}</td>
                    <td>{{ $cliente->apellido }}</td>
                    <td>
                        <a
                            href="{{ route(
                                'panel.negocios.{negocio}.clientes.{cliente}',
                                [
                                    'negocio' => $negocio,
                                    'cliente' => $cliente,
                                ],
                            ) }}"
                            class="w3-button">
                            Editar
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>

        <form class="w3-half" method="post">
            <x-panel.campo
                name="nombre"
                placeholder="Nombre"
                required
                :value="old('nombre')"
                :message="$message ?? ''"
                :minlength="1"
                pattern="[A-Z][a-záéíóúñ]+"
                title="El nombre debe comenzar con una letra mayúscula y contener solo letras minúsculas."
            />

            <x-panel.campo
                name="apellido"
                placeholder="Apellido"
                required
                :value="old('apellido')"
                :message="$message ?? ''"
                :minlength="1"
                pattern="[A-Z][a-záéíóúñ]+"
                title="El apellido debe comenzar con una letra mayúscula y contener solo letras minúsculas."
            />

            <x-panel.campo
                label="Correo electrónico"
                autocomplete="email"
                name="correo"
                :message="$message ?? ''"
                minlength="11"
                pattern=".+@gmail.com"
                placeholder="_____@gmail.com"
                required
                title="El correo electrónico debe ser una dirección de Gmail válida."
                type="email"
                :value="old('correo')">
                <x-slot:icono>
                    <x-iconos.correo />
                </x-slot:icono>
            </x-panel.campo>

            <x-panel.campo
                label="Contraseña"
                required
                name="clave"
                type="password"
                placeholder="Al menos 8 caracteres"
                autocomplete="new-password"
                minlength="8"
                pattern=".{8,}"
                title="La contraseña debe tener al menos 8 caracteres"
                :message="$message ?? ''">
                <x-slot:icono>
                    <x-iconos.clave />
                </x-slot:icono>
            </x-panel.campo>

            <x-panel.campo
                name="telefono"
                placeholder="Teléfono"
                required
                :value="old('telefono')"
                type="tel"
                :message="$message ?? ''"
                pattern="+58(416|426|414|424)\d{7}"
                title="El número de teléfono debe tener el formato +58(416|426|414|424) seguido de 7 dígitos."
            />

            <input
                type="submit"
                value="Registrar cliente"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-panel.estructuras.privada>
