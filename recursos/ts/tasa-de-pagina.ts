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

    fetch("https://api.dolarvzla.com/public/exchange-rate")
      .then((respuesta) => {
        if (respuesta.ok) {
          return respuesta.json();
        }

        throw new Error();
      })
      .then((datos) => {
        this.tasaDePagina = `Bs. ${datos.current.usd.toFixed(2)}`;
      })
      .catch(() => {
        this.tasaDePagina = "Error de conexión";
      });
  },
}));
