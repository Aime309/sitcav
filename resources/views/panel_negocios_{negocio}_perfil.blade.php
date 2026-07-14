<x-estructura-panel titulo="Editar perfil" :negocio="$negocio" :usuario="$usuario">
    <form method="post" enctype="multipart/form-data" class="w3-container">
        <input
            name="nombre"
            placeholder="Nombre"
            required
            minlength="1"
            pattern="[A-Z][a-záéíóúñ]+"
            title="El nombre debe comenzar con una letra mayúscula y contener solo letras minúsculas."
            class="w3-input"
            value="{{ $usuario['nombre'] }}"
        />
        <input
            name="apellido"
            placeholder="Apellido"
            required
            minlength="1"
            pattern="[A-Z][a-záéíóúñ]+"
            title="El apellido debe comenzar con una letra mayúscula y contener solo letras minúsculas."
            class="w3-input"
            value="{{ $usuario['apellido'] }}"
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
            value="{{ $usuario['correo'] }}"
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
            value="{{ $usuario['telefono'] }}"
        />
        <input
            type="submit"
            value="Actualizar"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-estructura-panel>
