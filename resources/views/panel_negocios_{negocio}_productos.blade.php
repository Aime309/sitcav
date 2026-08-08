<x-estructura-panel
    titulo="Productos"
    :crumbs="['Productos']"
    :negocio="$negocio"
    :usuario="$usuario">
    <main class="w3-row-padding">
        <table class="w3-half w3-table">
            @foreach ($negocio['productos'] as $producto)
                <tr>
                    <td>{{ $producto['nombre'] }}</td>
                    <td>{{ $producto['precio'] }}</td>
                    <td>
                        <a
                            href="{{ route('panel.negocios.{negocio}.productos.{producto}', ['negocio' => $negocio['id'], 'producto' => $producto['id']]) }}"
                            class="w3-button">
                            Editar
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        <form method="post" enctype="multipart/form-data" class="w3-half">
            <input
                name="nombre"
                placeholder="Nombre"
                class="w3-input"
                required
                minlength="1"
                pattern="[A-Za-z0-9\s]+"
                title="El nombre debe contener solo letras, números y espacios."
            />
            <textarea
                name="descripcion"
                pattern=".*"
                title="La descripción puede contener cualquier carácter."
                class="w3-input"
                placeholder="Descripción"></textarea>
            <input
                type="number"
                step=".01"
                name="precio"
                placeholder="Precio"
                required
                min="0.01"
                class="w3-input"
            />
            <input
                name="imagenes[]"
                type="file"
                accept="image/*"
                multiple
            />
            <input
                type="submit"
                value="Agregar Producto"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-estructura-panel>
