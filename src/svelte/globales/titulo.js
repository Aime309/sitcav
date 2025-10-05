import { writable } from "svelte/store";

const titulo = writable("");

titulo.subscribe((tituloActual) => {
  document.title = `${tituloActual} | SITCAV`;
});

export default titulo;
