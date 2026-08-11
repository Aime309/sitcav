<x-panel.estructuras.privada
    :titulo="$cliente->nombre"
    :crumbs="['Clientes', $cliente->correo, 'Editar']"
    :negocio="$negocio"
    :usuario="$usuario">
    <form class="w3-container" method="post">
        <x-panel.campo
            name="nombre"
            placeholder="Nombre"
            required
            :value="old('nombre', $cliente->nombre)"
            :message="$message ?? ''"
            :minlength="1"
            pattern="[A-Z][a-záéíóúñ]+"
            title="El nombre debe comenzar con una letra mayúscula y contener solo letras minúsculas."
        />

        <x-panel.campo
            name="apellido"
            placeholder="Apellido"
            required
            :value="old('apellido', $cliente->apellido)"
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
            :value="old('correo', $cliente->correo)">
            <x-slot:icono>
                <x-iconos.correo />
            </x-slot:icono>
        </x-panel.campo>

        <x-panel.campo
            name="telefono"
            placeholder="Teléfono"
            required
            :value="old('telefono', $cliente->telefono)"
            type="tel"
            :message="$message ?? ''"
            pattern="+58(416|426|414|424)\d{7}"
            title="El número de teléfono debe tener el formato +58(416|426|414|424) seguido de 7 dígitos."
        />

        <input
            type="submit"
            value="Actualizar cliente"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-panel.estructuras.privada>
