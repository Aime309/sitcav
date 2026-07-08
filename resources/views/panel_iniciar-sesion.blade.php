<x-estructura-panel titulo="Iniciar sesión">
    <form method="post" class="w3-container">
        <input
            name="correo"
            placeholder="Correo electrónico"
            required
            type="email"
            class="w3-input"
        />
        <input
            name="clave"
            placeholder="Contraseña"
            required
            type="password"
            class="w3-input"
        />
        <input
            type="submit"
            value="Iniciar sesión"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-estructura-panel>
