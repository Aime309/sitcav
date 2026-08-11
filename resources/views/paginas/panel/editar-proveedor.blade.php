<x-panel.estructuras.privada
    :titulo="$proveedor->nombre"
    :crumbs="['Proveedores']"
    :negocio="$negocio"
    :usuario="$usuario">
    <form
        class="w3-container"
        method="post"
        x-data="{
            nombre: '{{ old('nombre', $proveedor->nombre) }}',

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
            value="Actualizar proveedor"
            class="w3-button w3-blue w3-hover-light-blue w3-block"
        />
    </form>
</x-panel.estructuras.privada>
