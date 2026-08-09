<x-panel.estructuras.privada
    titulo="Editar perfil"
    :crumbs="['Perfil']"
    :negocio="$negocio"
    :usuario="$usuario">
    <form method="post" enctype="multipart/form-data" class="w3-container">
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
            type="submit"
            value="Actualizar"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-panel.estructuras.privada>
