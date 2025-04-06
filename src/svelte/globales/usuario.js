// @ts-nocheck

import { readable } from "svelte/store";

/** @type {{id?: number, cedula?: number, estaAutenticado: boolean}} */
const estadoInicial = {
  id: undefined,
  cedula: undefined,

  get estaAutenticado() {
    return this.id !== undefined;
  },
};

const usuario = readable(estadoInicial, async (_, actualizar) => {
  const respuesta = await fetch("./api/perfil");
  const datosDelUsuario = await respuesta.json();

  actualizar((estadoInicial) => {
    estadoInicial.id = datosDelUsuario.id;
    estadoInicial.cedula = datosDelUsuario.cedula;

    return estadoInicial;
  });
});

export default usuario;
