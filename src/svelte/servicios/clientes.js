export async function obtenerClientes() {
  const respuesta = await fetch("./api/clientes");

  return await respuesta.json();
}

export async function eliminarCliente(id) {
  await fetch(`./api/clientes/${id}`, {
    method: "DELETE",
  });
}

export async function actualizarCliente(cliente) {
  return await fetch(`./api/clientes/${cliente.id}`, {
    method: "PATCH",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(cliente),
  });
}

export async function registrarCliente(cliente) {
  return await fetch("./api/clientes", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify(cliente),
  });
}
