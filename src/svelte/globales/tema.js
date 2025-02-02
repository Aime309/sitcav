import { writable } from "svelte/store";

function crearTemaGlobal() {
  const { set, subscribe } = writable("light", (set) => {
    const temaPorDefecto = localStorage.getItem("tema") ?? "light";

    set(temaPorDefecto);
  });

  subscribe((temaActual) => {
    document.documentElement.setAttribute("data-bs-theme", temaActual);
    localStorage.setItem("tema", temaActual);
  });

  return {
    subscribe,
    activarTemaOscuro() {
      set("dark");
    },
    activarTemaClaro() {
      set("light");
    },
  };
}

const tema = crearTemaGlobal();

export default tema;
