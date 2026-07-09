<x-estructura-panel titulo="{{ $producto['nombre'] }}" :negocio="$negocio" :usuario="$usuario">
    <form method="post" enctype="multipart/form-data" class="w3-container">
        <input
            name="nombre"
            placeholder="Nombre"
            class="w3-input"
            required
            minlength="1"
            pattern="[A-Za-z0-9\s]+"
            title="El nombre debe contener solo letras, números y espacios."
            value="{{ $producto['nombre'] }}"
        />
        <textarea
            name="descripcion"
            pattern=".*"
            title="La descripción puede contener cualquier carácter."
            class="w3-input"
            placeholder="Descripción">{{ $producto['descripcion'] }}</textarea>
        <input
            type="number"
            step=".01"
            name="precio"
            placeholder="Precio"
            required
            min="0.01"
            class="w3-input"
            value="{{ $producto['precio'] }}"
        />
        @if (!$negocio['carga_inicial_cerrada'])
            <input
                type="number"
                name="stock"
                placeholder="Unidades disponibles"
                required
                min="0"
                class="w3-input"
                value="{{ $producto['stock'] }}"
            />
        @endif
        <input
            type="submit"
            value="Actualizar"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-estructura-panel>
