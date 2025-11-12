import Alpine from "alpinejs";
import Toast from "bootstrap/js/dist/toast";

Alpine.data("mensajes", () => ({
  errores: [] as string[],
  exitos: [] as string[],
  advertencias: [] as string[],
  informaciones: [] as string[],

  init() {
    this.$watch("errores", this.mostrarMensajes);
    this.$watch("exitos", this.mostrarMensajes);
    this.$watch("advertencias", this.mostrarMensajes);
    this.$watch("informaciones", this.mostrarMensajes);

    document.addEventListener("DOMContentLoaded", () => {
      this.errores = JSON.parse(document.body.dataset.errores || "[]");
      this.exitos = JSON.parse(document.body.dataset.exitos || "[]");

      this.advertencias = JSON.parse(
        document.body.dataset.advertencias || "[]",
      );
      this.informaciones = JSON.parse(
        document.body.dataset.informaciones || "[]",
      );
    });

    window.addEventListener("offline", () => {
      this.errores.push("Has perdido la conexión a la red");
    });

    window.addEventListener("online", () => {
      this.exitos.push("Conexión a la red restablecida");
    });
  },

  mostrarMensajes() {
    for (const toast of document.querySelectorAll(".toast")) {
      new Toast(toast).show();
    }
  },
}));
