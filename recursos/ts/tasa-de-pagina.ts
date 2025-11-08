import Alpine from "alpinejs";

Alpine.data("tasaDePagina", () => ({
  cargandoPagina: true,
  tasaDePagina: "Cargando",

  init() {
    this.cargandoPagina = false;
    this.cargarTasaDePagina();

    addEventListener("offline", () => {
      this.tasaDePagina = "Error de conexión";
    });

    addEventListener("online", () => {
      this.cargarTasaDePagina();
    });
  },

  cargarTasaDePagina() {
    this.tasaDePagina = "Cargando";

    fetch("https://ve.dolarapi.com/v1/dolares")
      .then((respuesta) => {
        if (respuesta.ok) {
          return respuesta.json();
        }

        throw new Error();
      })
      .then((datos) => {
        this.tasaDePagina = `Bs. ${datos[0].promedio.toFixed(2)}`;
      })
      .catch(() => {
        this.tasaDePagina = "Error de conexión";
      });
  },
}));
