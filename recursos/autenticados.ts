import "../node_modules/simplebar/dist/simplebar.min";
import "iconify-icon/dist/iconify-icon.min";
import $ from "jquery";
import Toast from "bootstrap/js/dist/toast";
import Dropdown from "bootstrap/js/dist/dropdown";
import Tooltip from "bootstrap/js/dist/tooltip";
import Modal from "bootstrap/js/dist/modal";
import ApexCharts from "apexcharts";
import Alpine from "alpinejs";

window.$ = $;
window.ApexCharts = ApexCharts;
window.Toast = Toast;
window.Alpine = Alpine;
window.Tooltip = Tooltip;

for (const element of document.querySelectorAll(".dropdown-toggle")) {
  new Dropdown(element);
}

for (const element of document.querySelectorAll("[data-bs-toggle='tooltip']")) {
  new Tooltip(element);
}

for (const element of document.querySelectorAll(".modal")) {
  new Modal(element);
}

const NOTIFICACIONES_INICIALES: string[] = [];

Alpine.data("SITCAV", () => ({
  errores: [...NOTIFICACIONES_INICIALES],
  exitos: [...NOTIFICACIONES_INICIALES],
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
        this.tasaDePagina = `Bs. ${datos[0].promedio}`;
      })
      .catch(() => {
        this.tasaDePagina = "Error de conexión";
      });
  },
}));

Alpine.start();
