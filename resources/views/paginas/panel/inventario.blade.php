<x-panel.estructuras.privada
    titulo="Inventario"
    :crumbs="['Inventario']"
    :negocio="$negocio"
    :usuario="$usuario">
    <table class="w3-container w3-table">
        @foreach ($negocio->productos as $producto)
            <tr>
                <td>{{ $producto->nombre }}</td>
                <td>
                    @if ($negocio->carga_inicial_abierta)
                        <form
                            method="post"
                            action="{{ route(
                                'panel.negocios.{negocio}.inventario.{producto}',
                                [
                                    'negocio' => $negocio->id,
                                    'producto' => $producto->id,
                                ]
                            ) }}">
                            @csrf
                            <input
                                type="number"
                                name="stock"
                                value="{{ $producto['stock'] }}"
                                class="w3-input"
                            />
                            <input
                                type="submit"
                                value="Actualizar"
                                class="w3-button w3-blue w3-hover-light-blue w3-block"
                            />
                        </form>
                    @else
                        {{ $producto['stock'] }}
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
</x-panel.estructuras.privada>
