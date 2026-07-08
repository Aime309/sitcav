<x-estructura-panel titulo="Registrarse">
    <form method="post" enctype="multipart/form-data">
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
            name="imagenes[]"
            type="file"
            accept="image/*"
            multiple
        />
        <input
            type="submit"
            value="Registrarse"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-estructura>
