import Alpine from "alpinejs";

// @ts-ignore
window.Alpine = Alpine;

Alpine.data("SITCAV", () => ({
  errores: JSON.parse(document.body.dataset.errores || "[]") as string[],
  exitos: JSON.parse(document.body.dataset.exitos || "[]") as string[],
  cargandoPagina: true,
  tasaDePagina: "Cargando",

  init() {
    this.cargandoPagina = false;
    this.cargarTasaDePagina();

    window.addEventListener("offline", () => {
      this.errores.push("Has perdido la conexión a la red");
      this.tasaDePagina = "Error de conexión";
    });

    window.addEventListener("online", () => {
      this.exitos.push("Conexión a la red restablecida");
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

Alpine.start();
