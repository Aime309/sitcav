<x-panel.estructuras.privada
    titulo="Proveedores"
    :crumbs="['Proveedores']"
    :negocio="$negocio"
    :usuario="$usuario">
    <main class="w3-row-padding">
        <table class="w3-half w3-table">
            @foreach($negocio->proveedores as $proveedor)
                <tr>
                    <td>{{ $proveedor->nombre }}</td>
                    <td>
                        <a
                            href="{{ route(
                                'panel.negocios.{negocio}.proveedores.{proveedor}',
                                [
                                    'negocio' => $negocio,
                                    'proveedor' => $proveedor
                                ],
                            ) }}"
                            class="w3-button">
                            Editar
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        <form
            class="w3-half"
            method="post"
            x-data="{
                nombre: @json(old('nombre')),

                get slug() {
                    return this.nombre.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                },
            }">
            <x-panel.campo
                name="nombre"
                required
                placeholder="Nombre"
                :message="$message ?? ''"
                model="nombre"
            />

            <x-panel.campo
                placeholder="Slug"
                disabled
                model="slug"
            />

            <input
                type="submit"
                value="Agregar proveedor"
                class="w3-button w3-blue w3-hover-light-blue w3-block"
            />
        </form>
    </main>
</x-panel.estructuras.privada>
