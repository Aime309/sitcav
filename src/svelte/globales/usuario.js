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

const usuario = readable(estadoInicial, (set) => {
  fetch("./api/perfil")
    .then((respuesta) => respuesta.json())
    .then((datosDelUsuario) =>
      set({
        ...estadoInicial,
        ...datosDelUsuario,
      }),
    );
});

export default usuario;
