<x-panel.estructuras.privada
    titulo="Productos"
    :crumbs="['Productos']"
    :negocio="$negocio"
    :usuario="$usuario">
    <main class="w3-row-padding">
        <table class="w3-half w3-table">
            @foreach ($negocio->productos as $producto)
                <tr>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->precio }}</td>
                    <td>
                        <a
                            href="{{ route(
                                'panel.negocios.{negocio}.productos.{producto}',
                                [
                                    'negocio' => $negocio,
                                    'producto' => $producto
                                ],
                            ) }}"
                            class="w3-button">
                            Editar
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        <form method="post" class="w3-half">
            @csrf

            <x-panel.campo
                name="nombre"
                placeholder="Nombre"
                required
                :minlength="1"
                pattern="[A-Za-z0-9\s]+"
                title="El nombre debe contener solo letras, números y espacios."
                :message="$message ?? ''"
                :value="old('nombre')"
            />

            <x-panel.campo
                type="number"
                step=".01"
                name="precio"
                placeholder="Precio"
                required
                :min="0.01"
                :message="$message ?? ''"
                :value="old('precio')"
            />

            <input
                type="submit"
                value="Agregar Producto"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-panel.estructuras.privada>
