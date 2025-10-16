import "@fontsource/inter/latin.css";
import "simplebar/dist/simplebar.min.css";
import "tabler-icons/iconfont/tabler-icons.min.css";
import "pure-css-loader/dist/loader-bouncing.css";

import "iconify-icon/dist/iconify-icon.min";
import Toast from "bootstrap/js/dist/toast";
import Alpine from "alpinejs";

for (const element of document.querySelectorAll(".toast")) {
  const toast = new Toast(element);

  toast.show();
}

Alpine.data("notificaciones", () => ({
  errores: [],
  exitos: [],

  init() {
    window.addEventListener("offline", () => {
      this.errores.push("Has perdido la conexión a la red");
    });

    window.addEventListener("online", () => {
      this.exitos.push("Conexión a la red restablecida");
    });
  },
}));

Alpine.start();
