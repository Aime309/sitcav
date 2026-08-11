<x-panel.estructuras.privada
    :titulo="$producto->nombre"
    :crumbs="['Productos', $producto->nombre, 'Editar']"
    :negocio="$negocio"
    :usuario="$usuario">
    <form method="post" class="w3-container">
        @csrf

        <x-panel.campo
            name="nombre"
            placeholder="Nombre"
            required
            :minlength="1"
            pattern="[A-Za-z0-9\s]+"
            title="El nombre debe contener solo letras, números y espacios."
            :message="$message ?? ''"
            :value="old('nombre', $producto->nombre)"
        />

        <x-panel.campo
            type="number"
            step=".01"
            name="precio"
            placeholder="Precio"
            required
            :min="0.01"
            :message="$message ?? ''"
            :value="old('precio', $producto->precio)"
        />

        <input
            type="submit"
            value="Actualizar Producto"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-panel.estructuras.privada>
