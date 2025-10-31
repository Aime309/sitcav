import "@fontsource/inter/latin.css";
import "simplebar/dist/simplebar.min.css";
import "tabler-icons/iconfont/tabler-icons.min.css";
import "pure-css-loader/dist/loader-bouncing.css";

import "iconify-icon/dist/iconify-icon.min";
import "alpinejs/dist/cdn.min";
import Toast from "bootstrap/js/dist/toast";
import Tab from "bootstrap/js/dist/tab";
import zxcvbn from "zxcvbn";
import Alpine from "alpinejs";

for (const element of document.querySelectorAll(".toast")) {
  const toast = new Toast(element);

  toast.show();
}

for (const element of document.querySelectorAll('[data-bs-toggle="tab"]')) {
  element.addEventListener("click", (event) => {
    event.preventDefault();

    new Tab(element).show();
  });
}

// @ts-ignore
window.zxcvbn = zxcvbn;

window.bootstrap = {
  ...(window.bootstrap || {}),
  Toast: Toast,
};

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
