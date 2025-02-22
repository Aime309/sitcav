<script>
  import {
    obtenerClientes,
    eliminarCliente,
    actualizarCliente,
    registrarCliente,
  } from "../servicios/clientes";

  let clientes = [];

  let cliente = {
    id: undefined,
    cedula: undefined,
    nombres: undefined,
    apellidos: undefined,
    telefono: undefined,
  };

  let cargando = true;

  $: obtenerClientes().then((respuesta) => {
    clientes = respuesta;
    cargando = false;
  });
</script>

<div class="container py-5">
  <div class="row">
    <div class="col-lg-8 table-responsive shadow-lg">
      <table class="table table-hover table-striped">
        <thead>
          <tr>
            <th>Cédula</th>
            <th>Nombres</th>
            <th>Apellidos</th>
            <th>Teléfono</th>
            <th>Dirección</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          {#if cargando}
            <tr>
              <td colspan="5">Cargando...</td>
            </tr>
          {:else}
            {#each clientes as clienteIterado}
              <tr>
                <td>{clienteIterado.cedula}</td>
                <td>{clienteIterado.nombres}</td>
                <td>{clienteIterado.apellidos}</td>
                <td>{clienteIterado.telefono}</td>
                <td>
                  {clienteIterado.localidad.nombre}
                  {clienteIterado.sector
                    ? `- ${clienteIterado.sector.nombre}`
                    : ""}
                </td>
                <td class="p-0 btn-group">
                  <button
                    class="btn btn-primary"
                    on:click={() => (cliente = clienteIterado)}>Editar</button
                  >
                  {#if !clienteIterado.ventas.length}
                    <button
                      class="btn btn-danger"
                      on:click={() => {
                        eliminarCliente(clienteIterado.id);
                        clientes = clientes.filter((clienteGuardado) => {
                          return clienteGuardado != clienteIterado;
                        });
                      }}
                    >
                      Eliminar
                    </button>
                  {/if}
                </td>
              </tr>
            {/each}
          {/if}
        </tbody>
      </table>
    </div>

    <div class="col-lg-4">
      {#if cliente.id}
        <form
          class="card card-body border-0 shadow-lg gap-3"
          on:submit|preventDefault={({ target: formulario }) => {
            actualizarCliente(cliente).then(async (respuesta) => {
              if (!respuesta.ok) {
                return alert(await respuesta.text());
              }

              clientes = await obtenerClientes();
              formulario?.reset();
              cliente.id = undefined;
            });
          }}
        >
          <input
            class="form-control"
            type="number"
            bind:value={cliente.cedula}
            placeholder="Introduce tu cédula"
            required
          />
          <input
            class="form-control"
            bind:value={cliente.nombres}
            placeholder="Introduce tus nombres"
            required
          />
          <input
            class="form-control"
            bind:value={cliente.apellidos}
            placeholder="Introduce tus apellidos"
            required
          />
          <input
            class="form-control"
            type="tel"
            bind:value={cliente.telefono}
            placeholder="Introduce tu teléfono"
            required
          />
          <button class="btn btn-success">Actualizar</button>
        </form>
      {/if}

      <form
        class="card card-body border-0 shadow-lg gap-3"
        on:submit|preventDefault={({ target: formulario }) => {
          const nuevoCliente = {
            cedula: formulario?.cedula.value,
            nombres: formulario?.nombres.value,
            apellidos: formulario?.apellidos.value,
            telefono: formulario?.telefono.value,
            id_localidad: 523,
          };

          registrarCliente(nuevoCliente).then(async (respuesta) => {
            if (!respuesta.ok) {
              return alert(await respuesta.text());
            }

            clientes = await obtenerClientes();
            formulario.reset();
          });
        }}
      >
        <input
          type="number"
          name="cedula"
          placeholder="Introduce tu cédula"
          required
          class="form-control"
        />
        <input
          class="form-control"
          name="nombres"
          placeholder="Introduce tus nombres"
          required
        />
        <input
          name="apellidos"
          placeholder="Introduce tus apellidos"
          required
          class="form-control"
        />
        <input
          type="tel"
          name="telefono"
          placeholder="Introduce tu teléfono"
          required
          class="form-control"
        />
        <button class="btn btn-success">Registrar</button>
      </form>
    </div>
  </div>
</div>
